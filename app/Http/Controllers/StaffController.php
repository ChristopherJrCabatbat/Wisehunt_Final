<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;


use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Delivery;
use App\Models\UserAccount;

use App\Notifications\SMSNotification;

use Carbon\Carbon;

class StaffController extends Controller
{


    private function forecastSalesForAllCustomers()
    {
        // Get all unique customer names
        $uniqueCustomerNames = Transaction::distinct()->pluck('customer_name');

        $forecasts = [];

        foreach ($uniqueCustomerNames as $customerId) {
            // Fetch transactions for the given customer, ordered by transaction date in descending order
            $customerTransactions = Transaction::where('customer_name', $customerId)
                ->orderBy('created_at', 'desc')
                ->get();

            // Check if there are more than one transaction for the customer
            if ($customerTransactions->count() > 1) {
                // Get the timestamps of the first and last transactions
                $firstTransactionDate = Carbon::parse($customerTransactions->last()->created_at);
                $lastTransactionDate = Carbon::parse($customerTransactions->first()->created_at);

                // Calculate the average time between transactions
                $averageTimeBetweenTransactions = $firstTransactionDate->diffInDays($lastTransactionDate) / ($customerTransactions->count() - 1);

                // Determine the most suitable timeframe based on the average time between transactions
                $timeFrame = ($averageTimeBetweenTransactions <= 7) ? 'week' : 'month';

                // Calculate the end date based on the selected time frame
                $endDate = ($timeFrame == 'week') ? $lastTransactionDate->copy()->addWeek() : $lastTransactionDate->copy()->addMonth();

                // Check if the current date is within the forecast period
                if (Carbon::now()->lte($endDate)) {
                    $forecasts[] = '<span class="bold-text">ATTENTION!</span> </br>Data indicates <span class="bold-text">' . $customerId . '</span> will transact again next <span class="bold-text">' . $timeFrame . '</span>.';
                }
            }
        }

        return $forecasts;
    }


    public function getCurrentSales()
    {
        // Fetch the current month
        $currentMonth = now()->format('m');

        // Retrieve current month sales logic
        $currentMonthSales = Transaction::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', now()->year)
            ->sum(DB::raw('qty * purchase_price'));

        return response()->json(['data' => $currentMonthSales]);
    }

    public function getForecastSales()
    {
        // Fetch the current month
        $currentMonth = now()->format('m');

        // Retrieve current month sales logic
        $currentMonthSales = Transaction::whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', now()->year)
            ->sum(DB::raw('qty * purchase_price'));

        // Simple growth rate (you can adjust this percentage based on your needs)
        $growthRate = 0.1; // 10% growth

        // Forecasted sales for the next month
        $forecastMonthSales = $currentMonthSales * (1 + $growthRate);

        return response()->json(['data' => $forecastMonthSales]);
    }

    private function generateNotifications($productsss, $forecasts)
    {
        // Notification arrays
        $lowQuantityNotifications = [];
        $bestSellerNotifications = [];
        $salesForecastNotifications = [];

        // No need to redefine $forecasts, as it's already passed as a parameter

        // Find the best-selling product
        $bestSeller = Product::select('products.id', 'products.name')
            ->join('transactions', 'products.name', '=', 'transactions.product_name')
            ->selectRaw('SUM(transactions.qty) as total_qty')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->first();

        $forecastMessages = null;

        foreach ($productsss as $product) {
            // Check if there are forecasts for the customer
            $customerId = $product->customer_name; // Assuming customer_name is the customer identifier

            $forecastMessages = null; // Initialize as null

            // Generate forecast messages for the current product
            foreach ($forecasts as $forecast) {
                // Customize the following condition based on your criteria
                if (strpos($forecast, $customerId) !== false) {
                    $forecastMessages[] = $forecast;
                }
            }

            // Create a single message by joining the forecast messages
            $forecastMessage = $forecastMessages ? implode('<br>', $forecastMessages) : null; // Check if not null

            // If the product quantity is zero, add a specific message
            if ($product->quantity == 0) {
                $outOfStockNotification = [
                    'message' => '<span class="bold-text">OUT OF STOCK!<br> Update: ' . $product->name . '</span> is out of stock. Urgently needs restocking!',
                    'productId' => $product->id,
                ];

                $lowQuantityNotifications[] = $outOfStockNotification;
            } elseif ($product->quantity <= $product->low_quantity_threshold) {
                // If the quantity is low, add it to low quantity notifications
                $notification = [
                    'message' => '<span class="bold-text">LOW STOCK!</span><br> We wish to inform you that your inventory <span class="bold-text">' . $product->name . "</span> is running critically low. Its time for a restock!",
                    'forecastMessage' => $forecastMessage,
                    'productId' => $product->id,
                ];

                $lowQuantityNotifications[] = $notification;
            }

            // Display the best seller quantity sold in the notification
            if ($bestSeller && $bestSeller->id == $product->id && $bestSeller->total_qty > 0) {
                $bestSellerNotification = [
                    'message' => '<span class="bold-text">' . e($bestSeller->name) . '</span> is your best seller. It might be wise to increase stock levels to meet the high demand and capitalize on its popularity.',
                    'productId' => $bestSeller->id,
                ];

                $bestSellerNotifications[] = $bestSellerNotification;
            }
        }

        $totalLowQuantityNotifications = count($lowQuantityNotifications);
        $totalBestSellerNotifications = count($bestSellerNotifications);
        $totalForecastMessages = $forecastMessages ? count($forecastMessages) : 0;

        // Calculate the total number of notifications
        $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;

        return [
            'lowQuantityNotifications' => $lowQuantityNotifications,
            'bestSellerNotifications' => $bestSellerNotifications,
            'totalForecastMessages' => $totalForecastMessages,
            'totalNotifications' => $totalNotifications, // Include totalNotifications in the returned array
        ];
    }



    // Dashboard Controller

    public function dashboard()
    {
        $nm = Session::get('name');
        $acc = Session::get('acc');

        // Count the total quantity sold for the day
        $totalSalesQty = Transaction::selectRaw('SUM(qty) as total_qty')
            ->whereDate('created_at', today()) // Change this to match your date format
            ->value('total_qty') ?? 0;

        $productCount = Product::count();
        $transactionCount = Transaction::count();
        $totalEarnings = Transaction::sum(DB::raw('total_price'));

        // Bar Chart/Graph
        $currentYear = date('Y');

        $earnings = Transaction::selectRaw('MONTH(created_at) as month, SUM(profit) as profit')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];
        $colors = ['#2c5c78', '#2dc0d0', '#6c6c6c', '#2c5c78', '#2dc0d0', '#6c6c6c', '#2c5c78', '#2dc0d0', '#6c6c6c', '#2c5c78', '#2dc0d0', '#6c6c6c'];

        for ($i = 1; $i <= 12; $i++) {
            $month = date('F', mktime(0, 0, 0, $i, 1));
            $earningsPerMonth = 0;

            foreach ($earnings as $earning) {
                if ($earning->month == $i) {
                    $earningsPerMonth = $earning->profit;
                    break;
                }
            }

            array_push($labels, $month);
            array_push($data, $earningsPerMonth);
        }

        $datasets = [
            [
                'label' => 'Monthly earnings (' . $currentYear . ')',
                'data' => $data,
                'backgroundColor' => $colors,
            ]
        ];

        // Pie Chart Logic
        $highDemandProducts = DB::table('transactions')
            ->select('product_name', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $pieChartData = "";
        foreach ($highDemandProducts as $product) {
            $pieChartData .= "['" . $product->product_name . "', " . $product->total_qty . "],";
        }

        $arr['pieChartData'] = rtrim($pieChartData, ",");

        $nextMonthStartDate = now()->addMonth()->startOfMonth();
        $nextMonthEndDate = now()->addMonth()->endOfMonth();

        // Assuming you retrieve $productsss and $forecasts here or from another method
        $productsss = Product::all();
        $forecasts = $this->forecastSalesForAllCustomers();

        // Call the method to generate notifications
        $notifications = $this->generateNotifications($productsss, $forecasts);

        // Extract total counts from the generated notifications
        $totalLowQuantityNotifications = count($notifications['lowQuantityNotifications']);
        $totalBestSellerNotifications = count($notifications['bestSellerNotifications']);
        $totalForecastMessages = $notifications['totalForecastMessages'];

        // Calculate the total number of notifications
        $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;

        // Pass both arrays to the view
        return view('staff-navbar.dashboard', $arr + [
            'username' => $nm,
            'productCount' => $productCount,
            'transactionCount' => $transactionCount,
            'totalSalesQty' => $totalSalesQty,
            'totalEarnings' => $totalEarnings,
            'datasets' => $datasets, // Adding the datasets for the bar chart
            'labels' => $labels, // Adding the labels for the bar chart
            'totalNotifications' => $totalNotifications,
            'nextMonthStartDate' => $nextMonthStartDate,
            'nextMonthEndDate' => $nextMonthEndDate,
        ] + $notifications);
    }

    public function userEdit(Request $request, $id)
    {

        $nm = Session::get('name');
        $acc = Session::get('acc');

        // Count the total quantity sold for the day
        $totalSalesQty = Transaction::selectRaw('SUM(qty) as total_qty')
            ->whereDate('created_at', today()) // Change this to match your date format
            ->value('total_qty') ?? 0;

        $productCount = Product::count();
        $transactionCount = Transaction::count();
        $totalEarnings = Transaction::sum(DB::raw('total_price'));

        // Bar Chart/Graph
        $currentYear = date('Y');

        $earnings = Transaction::selectRaw('MONTH(created_at) as month, SUM(profit) as profit')
            ->whereYear('created_at', $currentYear)
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $labels = [];
        $data = [];
        $colors = ['#2c5c78', '#2dc0d0', '#6c6c6c', '#2c5c78', '#2dc0d0', '#6c6c6c', '#2c5c78', '#2dc0d0', '#6c6c6c', '#2c5c78', '#2dc0d0', '#6c6c6c'];

        for ($i = 1; $i <= 12; $i++) {
            $month = date('F', mktime(0, 0, 0, $i, 1));
            $earningsPerMonth = 0;

            foreach ($earnings as $earning) {
                if ($earning->month == $i) {
                    $earningsPerMonth = $earning->profit;
                    break;
                }
            }

            array_push($labels, $month);
            array_push($data, $earningsPerMonth);
        }

        $datasets = [
            [
                'label' => 'Monthly earnings (' . $currentYear . ')',
                'data' => $data,
                'backgroundColor' => $colors,
            ]
        ];

        // Pie Chart Logic
        $highDemandProducts = DB::table('transactions')
            ->select('product_name', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $pieChartData = "";
        foreach ($highDemandProducts as $product) {
            $pieChartData .= "['" . $product->product_name . "', " . $product->total_qty . "],";
        }

        $arr['pieChartData'] = rtrim($pieChartData, ",");

        $nextMonthStartDate = now()->addMonth()->startOfMonth();
        $nextMonthEndDate = now()->addMonth()->endOfMonth();

        // Assuming you retrieve $productsss and $forecasts here or from another method
        $productsss = Product::all();
        $forecasts = $this->forecastSalesForAllCustomers();

        // Call the method to generate notifications
        $notifications = $this->generateNotifications($productsss, $forecasts);

        // Extract total counts from the generated notifications
        $totalLowQuantityNotifications = count($notifications['lowQuantityNotifications']);
        $totalBestSellerNotifications = count($notifications['bestSellerNotifications']);
        $totalForecastMessages = $notifications['totalForecastMessages'];

        // Calculate the total number of notifications
        $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;

        $userss = User::find($id);


        // Pass both arrays to the view
        return view('staff-navbar.user-edit', $arr + [
            'username' => $nm,
            'productCount' => $productCount,
            'transactionCount' => $transactionCount,
            'totalSalesQty' => $totalSalesQty,
            'totalEarnings' => $totalEarnings,
            'datasets' => $datasets, // Adding the datasets for the bar chart
            'labels' => $labels, // Adding the labels for the bar chart
            'totalNotifications' => $totalNotifications,
            'nextMonthStartDate' => $nextMonthStartDate,
            'nextMonthEndDate' => $nextMonthEndDate,
            'userss' => $userss,
        ] + $notifications);
    }


    // // Assuming you retrieve $productsss and $forecasts here or from another method
    // $productsss = Product::all();
    // $forecasts = $this->forecastSalesForAllCustomers();

    // // Call the method to generate notifications
    // $notifications = $this->generateNotifications($productsss, $forecasts);

    // // Extract total counts from the generated notifications
    // $totalLowQuantityNotifications = count($notifications['lowQuantityNotifications']);
    // $totalBestSellerNotifications = count($notifications['bestSellerNotifications']);
    // $totalForecastMessages = $notifications['totalForecastMessages'];

    // // Calculate the total number of notifications
    // $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;

    // $userss = User::find($id);
    // $users = User::paginate(8);

    // return view('staff-navbar.user-edit', [
    //     'users' => $users,
    //     'userss' => $userss,
    //     'totalNotifications' => $totalNotifications,
    //     'username' => $nm,
    // ] + $notifications);

    public function userUpdate(Request $request, string $id)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email,' . $id],
            // 'email' => ['required','unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required'],
        ]);

        $users = User::find($id);
        $users->name = $request->name;
        $users->email = $request->email;
        $users->role = $request->role;
        $users->password = Hash::make($request->password);

        if ($request->hasFile('photo')) {
            $fileName = time() . $request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('images', $fileName, 'public');
            $users->photo = '/storage/' . $path;
        }

        $users->save();

        return redirect()->route('staff.dashboard');
    }

    public function getEarningsForecast()
    {
        $forecastData = [];

        // Loop through the months and fetch forecasted earnings
        for ($i = 1; $i <= 12; $i++) {
            $forecastData[$i] = Transaction::whereMonth('created_at', $i)
                ->whereYear('created_at', today()->year + 1)
                ->sum('profit') ?? 0;
        }

        return response()->json($forecastData);
    }

    public function forecastSalesForNextMonth()
    {
        // Get the current date
        $currentDate = Carbon::now();

        // Calculate the start and end date for the next month
        $startDate = $currentDate->copy()->addMonthNoOverflow()->startOfMonth();
        $endDate = $currentDate->copy()->addMonthNoOverflow()->endOfMonth();

        // Query the database to get historical sales data for the next month
        $forecastedSales = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('qty');

        // You can apply your own forecasting algorithm or adjustments here

        return $forecastedSales;
    }

    public function product(Request $request)
    {
        $nm = Session::get('name');
        $query = Product::query();

        // Assuming you retrieve $productsss and $forecasts here or from another method
        $productsss = Product::all();
        $forecasts = $this->forecastSalesForAllCustomers();

        // Call the method to generate notifications
        $notifications = $this->generateNotifications($productsss, $forecasts);

        // Extract total counts from the generated notifications
        $totalLowQuantityNotifications = count($notifications['lowQuantityNotifications']);
        $totalBestSellerNotifications = count($notifications['bestSellerNotifications']);
        $totalForecastMessages = $notifications['totalForecastMessages'];

        // Calculate the total number of notifications
        $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;


        // Sort
        $sortOption = $request->input('sort');

        if ($sortOption === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sortOption === 'category_asc') {
            $query->orderBy('category', 'asc');
        } elseif ($sortOption === 'quantity_asc') {
            $query->orderBy('quantity', 'asc');
        } elseif ($sortOption === 'purchase_price_asc') {
            $query->orderBy('purchase_price', 'asc');
        } elseif ($sortOption === 'selling_price_asc') {
            $query->orderBy('selling_price', 'asc');
        }

        $suppliers = Supplier::all();
        $products = $query->paginate(5);
        $searchQuery = $request->input('search');

        return view('staff-navbar.product', [
            'username' => $nm,
            'searchQuery' => $searchQuery,
            'products' => $products,
            'suppliers' => $suppliers,
            'totalNotifications' => $totalNotifications,
        ] + $notifications);
    }

    public function productStore(Request $request)
    {
        // Your validation logic here
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required|unique:products,name,NULL,id',
            'brand_name' => 'required',
            'description' => 'required',
            'category' => 'required',
            'quantity' => 'required|numeric|min:1',
            'low_quantity_threshold' => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|min:1',
        ], [
            'name.unique' => 'You already have :input in your table.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('staff.product')->withErrors($validator)->withInput();
        }

        $products = new Product;
        $products->code = $request->input('code');
        $products->name = $request->input('name');
        $products->brand_name = $request->input('brand_name');
        $products->description = $request->input('description');
        $products->category = $request->input('category');
        $products->quantity = $request->input('quantity');
        $products->low_quantity_threshold = $request->input('low_quantity_threshold');
        $products->purchase_price = $request->input('purchase_price');
        $products->selling_price = $request->input('selling_price');

        if ($request->hasFile('photo')) {
            $fileName = time() . $request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('images', $fileName, 'public');
            $products->photo = '/storage/' . $path;
        }

        $products->save();
        return redirect()->route('staff.product')->with('success', 'Product created successfully.');
    }

    public function productEdit(Request $request, $id)
    {
        $nm = Session::get('name');
        $query = Product::query();

        // Assuming you retrieve $productsss and $forecasts here or from another method
        $productsss = Product::all();
        $forecasts = $this->forecastSalesForAllCustomers();

        // Call the method to generate notifications
        $notifications = $this->generateNotifications($productsss, $forecasts);

        // Extract total counts from the generated notifications
        $totalLowQuantityNotifications = count($notifications['lowQuantityNotifications']);
        $totalBestSellerNotifications = count($notifications['bestSellerNotifications']);
        $totalForecastMessages = $notifications['totalForecastMessages'];

        // Calculate the total number of notifications
        $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;

        $suppliers = Supplier::all();
        $products = $query->paginate(5);
        $searchQuery = $request->input('search');
        $productss = Product::find($id);

        return view('staff-navbar.product-edit', [
            'username' => $nm,
            'searchQuery' => $searchQuery,
            'products' => $products,
            'productss' => $productss,
            'suppliers' => $suppliers,
            'totalNotifications' => $totalNotifications,
        ] + $notifications);
    }

    public function productUpdate(Request $request, string $id)
    {
        $updateValidator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required|unique:products,name,' . $id . ',id',
            'brand_name' => 'required',
            'description' => 'required',
            'category' => 'required',
            'quantity' => 'required|numeric|min:1',
            'low_quantity_threshold' => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|min:1',
        ], [
            'name.unique' => 'You already have :input in your table.',
        ]);

        if ($updateValidator->fails()) {
            return redirect()->route('staff.product')->withErrors($updateValidator)->withInput();
        }

        $product = Product::find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        $product->code = $request->code;
        $product->name = $request->name;
        $product->brand_name = $request->brand_name;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->quantity = $request->quantity;
        $product->low_quantity_threshold = $request->low_quantity_threshold;
        $product->purchase_price = $request->purchase_price;
        $product->selling_price = $request->selling_price;

        if ($request->hasFile('photo')) {
            $fileName = time() . $request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('images', $fileName, 'public');
            $product->photo = '/storage/' . $path;
        }

        $product->save();
        return redirect()->route('staff.product');
    }

    public function productDestroy(string $id)
    {
        $products = Product::findOrFail($id);
        $products->delete();
        return redirect()->route('staff.product');
        // return back()->withSuccess('Account deleted successfully!');
    }

    // Transaction Controllers
    public function transaction(Request $request)
    {
        $nm = Session::get('name');
        $acc = Session::get('acc');

        $sortOption = $request->input('sort');

        $query = Transaction::query();

        if ($sortOption === 'customer_name_asc') {
            $query->orderBy('customer_name', 'asc');
        } elseif ($sortOption === 'product_name_asc') {
            $query->orderBy('product_name', 'asc');
        } elseif ($sortOption === 'qty_asc') {
            $query->orderBy('qty', 'asc');
        } elseif ($sortOption === 'selling_price_asc') {
            $query->orderBy('selling_price', 'asc');
        } elseif ($sortOption === 'total_price_asc') {
            $query->orderBy('total_price', 'asc');
        } elseif ($sortOption === 'profit_asc') {
            $query->orderByDesc('profit');
        }

        $selling_price = $request->input('selling_price');
        $qty = $request->input('qty');
        $totalPrice = $selling_price * $qty;


        // Assuming you retrieve $productsss and $forecasts here or from another method
        $productsss = Product::all();
        $forecasts = $this->forecastSalesForAllCustomers();

        // Call the method to generate notifications
        $notifications = $this->generateNotifications($productsss, $forecasts);

        // Extract total counts from the generated notifications
        $totalLowQuantityNotifications = count($notifications['lowQuantityNotifications']);
        $totalBestSellerNotifications = count($notifications['bestSellerNotifications']);
        $totalForecastMessages = $notifications['totalForecastMessages'];

        // Calculate the total number of notifications
        $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;

        // Filter transactions by today's date
        $today = Carbon::today();
        $query->whereDate('created_at', $today);

        $products = Product::all();
        $customers = Customer::all();
        $searchQuery = $request->input('search');
        $transactions = $query->paginate(8);

        return view('staff-navbar.transaction', [
            'transactions' => $transactions,
            'username' => $nm,
            'products' => $products,
            'totalNotifications' => $totalNotifications,
            'totalPrice' => $totalPrice,
            'customers' => $customers,
            'searchQuery' => $searchQuery,
        ] + $notifications);
    }

    public function transactionEdit(Request $request, $id)
    {
        $nm = Session::get('name');
        $query = Transaction::query();

        // Assuming you retrieve $productsss and $forecasts here or from another method
        $productsss = Product::all();
        $forecasts = $this->forecastSalesForAllCustomers();

        // Call the method to generate notifications
        $notifications = $this->generateNotifications($productsss, $forecasts);

        // Extract total counts from the generated notifications
        $totalLowQuantityNotifications = count($notifications['lowQuantityNotifications']);
        $totalBestSellerNotifications = count($notifications['bestSellerNotifications']);
        $totalForecastMessages = $notifications['totalForecastMessages'];

        // Calculate the total number of notifications
        $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;

        $selling_price = $request->input('selling_price');
        $qty = $request->input('qty');
        $totalPrice = $selling_price * $qty;

        $products = Product::all();
        $customers = Customer::all();
        $searchQuery = $request->input('search');
        $transactions = $query->paginate(8);
        $transactionss = Transaction::find($id);

        return view('staff-navbar.transaction-edit', [
            'transactions' => $transactions,
            'transactionss' => $transactionss,
            'username' => $nm,
            'products' => $products,
            'totalNotifications' => $totalNotifications,
            'totalPrice' => $totalPrice,
            'customers' => $customers,
            'searchQuery' => $searchQuery,
        ] + $notifications);
    }


    public function transactionStore(Request $request)
    {
        // Validation rules
        $rules = [
            'product_name' => 'required|string',
            'selling_price' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'customer_name' => 'required|string',
        ];

        // Custom error messages
        $messages = [
            'qty.min' => 'The quantity must be at least :min.',
        ];

        // Validate the request
        $request->validate($rules, $messages);

        // Retrieve data from the request
        $productName = $request->input('product_name');
        $selling_price = $request->input('selling_price');
        $qty = $request->input('qty');
        $customerName = $request->input('customer_name');

        // Retrieve the product's information from the Products table
        $product = Product::where('name', $productName)->where('selling_price', $selling_price)->first();

        if ($product) {
            // Calculate total price
            $totalPrice = $selling_price * $qty;

            // Calculate total earned
            $purchase_price = $product->purchase_price;
            $profit = ($selling_price - $purchase_price) * $qty;

            // Create a new Transactions record and save it to the database
            $transaction = new Transaction;
            $transaction->customer_name = $customerName;
            $transaction->product_name = $productName;
            $transaction->qty = $qty;
            $transaction->transacted_qty += $qty; // Assuming this is intended to increment a total, otherwise just assign $qty
            $transaction->selling_price = $selling_price;
            $transaction->total_price = $totalPrice;
            $transaction->profit = $profit;
            $transaction->save();

            // Update the product quantity by subtracting the sold quantity, allowing it to go negative
            $product->quantity -= $qty;
            $product->save();

            // Additional logic for forecasts, session management, etc.
            // Assuming these methods (calculateForecastedSales, calculateLastMonthSales, calculateMonthlyForecastedSales) are defined elsewhere in your controller

            // $transactionCount = session('transactionCount', 0) + 1;
            // session(['transactionCount' => $transactionCount]);

            // $monthlyTransactionCount = session('monthlyTransactionCount', 0) + 1;
            // session(['monthlyTransactionCount' => $monthlyTransactionCount]);

            // if ($transactionCount % 2 === 0) {
            //     // Logic to handle forecasted sales alert
            // }

            // if ($monthlyTransactionCount % 5 === 0) {
            //     // Logic to handle monthly forecasted sales alert
            // }

            // return back();
            return back()->with('success', 'Transaction recorded successfully.');
        } else {
            // If product is not found or another error occurs
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Selected product and unit price did not match.']);
        }
    }


    // public function transactionStore(Request $request)
    // {
    //     // Validation rules
    //     $rules = [
    //         'product_name' => 'required|string',
    //         'selling_price' => 'required|numeric|min:0',
    //         'qty' => 'required|integer|min:1',
    //         'customer_name' => 'required|string',
    //     ];

    //     // Custom error messages
    //     $messages = [
    //         'qty.min' => 'The quantity must be at least :min.',
    //     ];

    //     // Validate the request
    //     $request->validate($rules, $messages);

    //     // Retrieve data from the request
    //     $productName = $request->input('product_name');
    //     $selling_price = $request->input('selling_price');
    //     $qty = $request->input('qty');
    //     $customerName = $request->input('customer_name');

    //     // Retrieve the product's information from the Products table (assuming you have a 'Product' model)
    //     $product = Product::where('name', $productName)->where('selling_price', $selling_price)->first();

    //     if ($product) {
    //         // Check if there's enough quantity to subtract
    //         if ($product->quantity >= $qty) {
    //             // Calculate total price
    //             $totalPrice = $selling_price * $qty;

    //             // Calculate total earned
    //             $purchase_price = $product->purchase_price;
    //             $profit = ($selling_price - $purchase_price) * $qty;

    //             // Create a new Transactions record and save it to the database
    //             $transaction = new Transaction;
    //             $transaction->customer_name = $customerName;
    //             $transaction->product_name = $productName;
    //             $transaction->qty = $qty;
    //             $transaction->selling_price = $selling_price;
    //             $transaction->total_price = $totalPrice;
    //             $transaction->profit = $profit;
    //             $transaction->save();

    //             // Update the product quantity by subtracting the sold quantity
    //             $product->quantity -= $qty;
    //             $product->save();

    //             // Fetch past transactions for the current day
    //             $currentDayTransactions = Transaction::whereDate('created_at', now()->toDateString())->get();

    //             // Perform sales forecasting logic based on the past transactions
    //             $forecastedSales = $this->calculateForecastedSales($currentDayTransactions);

    //             $transactionCount = session('transactionCount', 0) + 1;
    //             session(['transactionCount' => $transactionCount]);

    //             // Increment the monthly transaction count in the session
    //             $monthlyTransactionCount = session('monthlyTransactionCount', 0) + 1;
    //             session(['monthlyTransactionCount' => $monthlyTransactionCount]);

    //             // Display alert with forecasted sales after every five transactions
    //             if ($forecastedSales !== null && $transactionCount % 2 === 0) {
    //                 $message = "Forecasted Sales for the day: ₱" . number_format($forecastedSales, 2);
    //                 session()->flash('forecastedSalesAlert', $message);
    //             }

    //             // Display alert with forecasted sales after every 5 transactions for monthly forecasting
    //             if ($forecastedSales !== null && $monthlyTransactionCount % 5 === 0) {
    //                 // Fetch all transactions for the current month
    //                 $allTransactions = Transaction::whereYear('created_at', now()->year)
    //                     ->whereMonth('created_at', now()->month)
    //                     ->get();

    //                 $monthlyForecastedSales = $this->calculateMonthlyForecastedSales($allTransactions);
    //                 $monthlyMessage = "Forecasted Sales for the month: ₱" . number_format($monthlyForecastedSales, 2);
    //                 session()->flash('monthlyForecastedSalesAlert', $monthlyMessage);
    //             }

    //             return back();
    //         } else {
    //             // Handle the case where the quantity is insufficient
    //             return redirect()->back()
    //                 ->withInput()
    //                 ->withErrors(['error_stock' => 'Insufficient quantity in stock. Remaining quantity: ' . $product->quantity]);
    //         }
    //     } else {
    //         // Keep the form data and repopulate the fields
    //         return redirect()->back()
    //             ->withInput()
    //             ->withErrors(['error' => 'Selected product and unit price did not match.']);
    //     }
    // }


    private function calculateForecastedSales($transactions)
    {
        // Check if there are enough transactions to calculate forecast
        if ($transactions->count() < 2) {
            return 0; // Not enough data for accurate forecasting
        }

        // Calculate total sales for the day
        $totalSales = $transactions->sum(function ($transaction) {
            return $transaction->qty * $transaction->selling_price;
        });

        // Calculate the average sales per transaction
        $averageSales = $totalSales / $transactions->count();

        // Calculate forecasted sales by adding a percentage of the average sales
        $forecastedSales = $totalSales + ($totalSales * 0.18); // You can adjust the factor (0.05) based on your preference

        return $forecastedSales;
    }

    private function calculateMonthlyForecastedSales($allTransactions)
    {
        // Check if there are enough transactions to calculate forecast
        if ($allTransactions->count() < 5) {
            return 0; // Not enough data for accurate forecasting
        }

        // Calculate total sales for the month
        $totalSales = $allTransactions->sum(function ($transaction) {
            return $transaction->qty * $transaction->selling_price;
        });

        // Calculate the average sales per transaction
        $averageSales = $totalSales / $allTransactions->count();

        // Calculate forecasted sales by adding a percentage of the average sales
        $monthlyForecastedSales = $totalSales + ($totalSales * 0.18); // You can adjust the factor (0.18) based on your preference

        return $monthlyForecastedSales;
    }


    public function transactionUpdate(Request $request, string $id)
    {
        // Validation rules
        $rules = [
            'product_name' => 'required|string',
            'selling_price' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'customer_name' => 'required|string',
        ];

        // Custom error messages
        $messages = [
            'qty.min' => 'The quantity must be at least :min.',
        ];

        // Validate the request
        $request->validate($rules, $messages);

        // Retrieve the existing transaction record by its ID
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return redirect()->route('staff.transaction')->with("error", "Transaction not found!");
        }

        // Retrieve data from the request
        $productName = $request->input('product_name');
        $selling_price = $request->input('selling_price');
        $qty = $request->input('qty');
        $customerName = $request->input('customer_name');

        // Retrieve the old 'qty' for the transaction
        $oldQty = $transaction->qty;

        // Retrieve the product's information from the Products table (assuming you have a 'Product' model)
        $product = Product::where('name', $productName)->where('selling_price', $selling_price)->first();

        if ($product) {
            // Check if there's enough quantity to subtract
            if ($product->quantity + $oldQty >= $qty) {
                // Calculate total price
                $totalPrice = $selling_price * $qty;

                // Calculate total earned
                $purchase_price = $product->purchase_price;
                $profit = ($selling_price - $purchase_price) * $qty;

                // Update the existing transaction record with the new data
                $transaction->product_name = $productName;
                $transaction->selling_price = $selling_price;
                $transaction->qty = $qty;
                $transaction->total_price = $totalPrice;
                $transaction->profit = $profit;
                $transaction->save();

                // Update the product quantity by adding the old 'qty' and subtracting the new 'qty'
                $product->quantity += $oldQty;
                $product->quantity -= $qty;
                $product->save();

                return redirect()->route('staff.transaction')->with("message", "Transaction updated successfully!");
            } else {
                // Calculate the remaining quantity as the sum of the past 'qty' and the current 'quantity'
                $remainingQuantity = $product->quantity + $oldQty;
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error_stock' => 'Insufficient quantity in stock. Remaining quantity: ' . $remainingQuantity]);
            }
        } else {
            // Keep the form data and repopulate the fields
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Selected product and unit price did not match.']);
        }
    }

    public function transactionDestroy(string $id)
    {
        $transactions = Transaction::findOrFail($id);
        $transactions->delete();
        // return redirect()->route('staff.transaction')->withSuccess('Account deleted successfully!');
        return back()->withSuccess('Account deleted successfully!');
    }


    // Customer Controllers
    public function customer()
    {
        $nm = Session::get('name');
        $acc = Session::get('acc');

        // Assuming you retrieve $productsss and $forecasts here or from another method
        $productsss = Product::all();
        $forecasts = $this->forecastSalesForAllCustomers();

        // Call the method to generate notifications
        $notifications = $this->generateNotifications($productsss, $forecasts);

        // Extract total counts from the generated notifications
        $totalLowQuantityNotifications = count($notifications['lowQuantityNotifications']);
        $totalBestSellerNotifications = count($notifications['bestSellerNotifications']);
        $totalForecastMessages = $notifications['totalForecastMessages'];

        // Calculate the total number of notifications
        $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;
        $customers = Customer::paginate(7);

        return view('staff-navbar.customer', [
            'customers' => $customers,
            'username' => $nm,
            'totalNotifications' => $totalNotifications,
        ] + $notifications);
    }

    public function customerEdit(Request $request, $id)
    {
        $nm = Session::get('name');

        // Assuming you retrieve $productsss and $forecasts here or from another method
        $productsss = Product::all();
        $forecasts = $this->forecastSalesForAllCustomers();

        // Call the method to generate notifications
        $notifications = $this->generateNotifications($productsss, $forecasts);

        // Extract total counts from the generated notifications
        $totalLowQuantityNotifications = count($notifications['lowQuantityNotifications']);
        $totalBestSellerNotifications = count($notifications['bestSellerNotifications']);
        $totalForecastMessages = $notifications['totalForecastMessages'];

        // Calculate the total number of notifications
        $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;

        $customerss = Customer::find($id);
        $customers = Customer::paginate(7);

        return view('staff-navbar.customer-edit', [
            'customers' => $customers,
            'username' => $nm,
            'totalNotifications' => $totalNotifications,
            'customerss' => $customerss,
        ] + $notifications);
    }

    public function customerStore(Request $request)
    {

        // $request -> validate([
        //     "fname"=>"required",
        //     "mname"=>"required|min:2|max:20",
        //     "lname"=>"required|min:2|max:30",
        //     "address"=>"required",
        //     "contact_num"=>"required|numeric|digits_between:5,11",
        // ]);

        $customers = new Customer;
        $customers->name = $request->input('name');
        $customers->contact_name = $request->input('contact_name');
        $customers->address = $request->input('address');
        $customers->contact_num = $request->input('contact_num');
        // $customers->item_sold = $request->input('item_sold');
        $customers->save();
        return redirect()->route('staff.customer')->with("success", "Customer added successfully!");
    }



    public function customerUpdate(Request $request, string $id)
    {
        $customers = Customer::find($id);
        $customers->name = $request->name;
        $customers->contact_name = $request->contact_name;
        $customers->address = $request->address;
        $customers->contact_num = $request->contact_num;
        // $customers->item_sold = $request->item_sold;
        $customers->save();
        return redirect()->route('staff.customer')->with("success", "Customer updated successfully!");
    }

    public function customerDestroy(string $id)
    {
        $customers = Customer::findOrFail($id);
        $customers->delete();
        return redirect()->route('staff.customer');
    }




    // Delivery Controllers
    public function delivery()
    {
        $nm = Session::get('name');

        // Assuming you retrieve $productsss and $forecasts here or from another method
        $productsss = Product::all();
        $forecasts = $this->forecastSalesForAllCustomers();

        // Call the method to generate notifications
        $notifications = $this->generateNotifications($productsss, $forecasts);

        // Extract total counts from the generated notifications
        $totalLowQuantityNotifications = count($notifications['lowQuantityNotifications']);
        $totalBestSellerNotifications = count($notifications['bestSellerNotifications']);
        $totalForecastMessages = $notifications['totalForecastMessages'];

        // Calculate the total number of notifications
        $totalNotifications = $totalLowQuantityNotifications + $totalBestSellerNotifications + $totalForecastMessages;

        $products = Product::all();
        $deliveries = Delivery::paginate(6);


        return view('staff-navbar.delivery', [
            'deliveries' => $deliveries,
            'products' => $products,
            'totalNotifications' => $totalNotifications,
            'username' => $nm,
        ] + $notifications);
    }

    public function getDeliveryDetails($id)
    {
        $delivery = Delivery::find($id);
        if (!$delivery) {
            return response()->json(['error' => 'Delivery not found'], 404);
        }
        return response()->json($delivery);
    }


    public function deliveryStore(Request $request)
    {
        $request->validate([
            'delivery_id' => ['required', 'unique:' . Delivery::class],
            'name' => ['required'],
            'product' => ['required'],
            'quantity' => ['required'],
            'mode_of_payment' => ['required'],
            'status' => ['required'],
        ]);

        $deliveries = new Delivery;
        $deliveries->delivery_id = $request->input('delivery_id');
        $deliveries->name = $request->input('name');

        // Remove null values from the quantity array
        $filteredQuantity = array_filter($request->input('quantity'), function ($value) {
            return $value !== null;
        });

        $deliveries->product = json_encode($request->input('product'));
        $deliveries->quantity = json_encode($filteredQuantity);

        // $productList = json_decode($request->input('product'));
        // $quantityList = json_decode($request->input('quantity'));

        // foreach ($productList as $index => $productName) {
        //     $transactedQty = Transaction::where('product_name', $productName)->sum('transacted_qty');

        //     if ($quantityList[$index] > $transactedQty) {
        //         return redirect()->back()
        //             ->withErrors(['error_delivery' => "Delivery quantity for product '$productName' exceeds transacted quantity. Transacted quantity: $transactedQty"]);
        //     }
        // }

        $deliveries->mode_of_payment = $request->input('mode_of_payment');
        $deliveries->status = $request->input('status');

        $deliveries->save();

        return back()->with('success', 'Delivery added successfully.');
    }

    public function deliveryUpdate(Request $request)
    {
        // Validate the request if needed

        $delivery = Delivery::findOrFail($request->input('delivery_id'));
        $delivery->status = $request->input('status');
        $delivery->save();

        return response()->json(['success' => true]);
    }

    public function deliveryDestroy(string $id)
    {
        $deliveries = Delivery::findOrFail($id);
        $deliveries->delete();
        return back();
    }
}