<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;

use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use App\Rules\MatchOldPassword;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Delivery;
use App\Models\UserAccount;

use App\Notifications\SMSNotification;

use Carbon\Carbon;


class AdminController extends Controller
{
    public function SMS()
    {
        // // SMS
        // $productsss = Products::all();
        // $lowQuantityNotifications = [];

        // foreach ($productsss as $product) {
        //     if ($product->quantity <= 20) {
        //         $notification = [
        //             'message' => $product->name . "'s quantity is too low!",
        //             'productId' => $product->id,
        //         ];
        //         $lowQuantityNotifications[] = $notification;

        //         // Access user's phone number from the product's user relation
        //         $phoneNumber = $product->user->phone_number; // Assuming a 'phone_number' field in the User model

        //         // Call sendSMSNotification using $this
        //         $this->sendSMSNotification($phoneNumber, $product->name);
        //     }
        // }
    }


    // private function forecastSalesForAllCustomers()
    // {
    //     // Get all unique customer names
    //     $uniqueCustomerNames = Transaction::distinct()->pluck('customer_name_id');

    //     $forecasts = [];

    //     foreach ($uniqueCustomerNames as $customerId) {
    //         // Fetch transactions for the given customer, ordered by transaction date in descending order
    //         $customerTransactions = Transaction::where('customer_name_id', $customerId)
    //             ->orderBy('created_at', 'desc')
    //             ->get();

    //         // Check if there are more than one transaction for the customer
    //         if ($customerTransactions->count() > 1) {
    //             // Get the timestamps of the first and last transactions
    //             $firstTransactionDate = Carbon::parse($customerTransactions->last()->created_at);
    //             $lastTransactionDate = Carbon::parse($customerTransactions->first()->created_at);

    //             // Calculate the average time between transactions
    //             $averageTimeBetweenTransactions = $firstTransactionDate->diffInDays($lastTransactionDate) / ($customerTransactions->count() - 1);

    //             // Determine the most suitable timeframe based on the average time between transactions
    //             $timeFrame = ($averageTimeBetweenTransactions <= 7) ? 'week' : 'month';

    //             // Calculate the end date based on the selected time frame
    //             $endDate = ($timeFrame == 'week') ? $lastTransactionDate->copy()->addWeek() : $lastTransactionDate->copy()->addMonth();

    //             // Check if the current date is within the forecast period
    //             if (Carbon::now()->lte($endDate)) {
    //                 $forecasts[] = '<span class="bold-text">ATTENTION!</span> </br>Data indicates <span class="bold-text">' . $customerId . '</span> will transact again next <span class="bold-text">' . $timeFrame . '</span>.';
    //             }
    //         }
    //     }

    //     return $forecasts;
    // }


    private function forecastSalesForAllCustomers()
    {
        // Get all unique customer names
        $uniqueCustomerNames = Transaction::distinct()->pluck('customer_name_id');

        $forecasts = [];

        foreach ($uniqueCustomerNames as $customerId) {
            // Fetch transactions for the given customer, ordered by transaction date in descending order
            $customerTransactions = Transaction::where('customer_name_id', $customerId)
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
        $bestSeller = Product::select('products.id', 'products.product_name_id')
            ->join('transactions', 'products.product_name_id', '=', 'transactions.product_name')
            ->selectRaw('SUM(transactions.qty) as total_qty')
            ->groupBy('products.id', 'products.product_name_id')
            ->orderByDesc('total_qty')
            ->first();

        $forecastMessages = null;

        foreach ($productsss as $product) {
            // Check if there are forecasts for the customer
            $customerId = $product->customer_name_id; // Assuming customer_name_id is the customer identifier

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
                    'message' => '<span class="bold-text">' . ($bestSeller->product_name_id) . '</span> is your best seller. It might be wise to increase stock levels to meet the high demand and capitalize on its popularity.',
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
        $totalEarnings = Transaction::sum('profit');

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

        $transactions = Transaction::all();

        // Pass both arrays to the view
        return view('navbar.dashboard', $arr + [
            'username' => $nm,
            'productCount' => $productCount,
            'transactionCount' => $transactionCount,
            'transactions' => $transactions,
            'totalSalesQty' => $totalSalesQty,
            'totalEarnings' => $totalEarnings,
            'datasets' => $datasets, // Adding the datasets for the bar chart
            'labels' => $labels, // Adding the labels for the bar chart
            'totalNotifications' => $totalNotifications,
            'nextMonthStartDate' => $nextMonthStartDate,
            'nextMonthEndDate' => $nextMonthEndDate,
        ] + $notifications);
    }

    public function getTransactions()
    {
        $transactions = Transaction::all(); // Adjust this query based on your actual model structure
        return response()->json($transactions);
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

        if ($sortOption === 'product_name_id_asc') {
            $query->orderBy('product_name_id', 'asc');
        } elseif ($sortOption === 'category_asc') {
            $query->orderBy('category', 'asc');
        } elseif ($sortOption === 'default_asc') {
            return redirect()->route('admin.product');
        }


        $suppliers = Supplier::all();
        $products = $query->paginate(5);
        $searchQuery = $request->input('search');

        return view('navbar.product', [
            'username' => $nm,
            'searchQuery' => $searchQuery,
            'products' => $products,
            'suppliers' => $suppliers,
            'totalNotifications' => $totalNotifications,
        ] + $notifications);
    }

    public function filterProductsByCategory(Request $request, $category)
    {
        $nm = Session::get('name');
        $query = Product::query();

        // Add additional filters as needed
        $query->where('category', $category);

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

        if ($sortOption === 'product_name_id_asc') {
            $query->orderBy('product_name_id', 'asc');
        } elseif ($sortOption === 'default_asc') {
            return redirect()->route('admin.product');
        } elseif ($sortOption === 'category_asc') {
            $query->orderBy('category', 'asc');
        }

        $suppliers = Supplier::all();
        $products = $query->paginate(5);

        return view('navbar.product', [
            'username' => $nm,
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
            'product_name_id' => 'required|unique:products,product_name_id,NULL,id',
            'brand_name' => 'required',
            'description' => 'required',
            'unit' => 'required',
            'category' => 'required',
            'quantity' => 'required|numeric|min:1',
            'low_quantity_threshold' => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|min:1',
        ], [
            'product_name_id.unique' => 'You already have :input in your table.',
        ]);

        // Fetch all suppliers and their product names
        $suppliers = Supplier::all();
        $allProductNames = [];

        foreach ($suppliers as $supplier) {
            $productNames = json_decode($supplier->product_name_id, true);
            if (is_array($productNames)) {
                $allProductNames = array_merge($allProductNames, $productNames);
            }
        }

        $allProductNames = array_unique($allProductNames);

        // Validate if the product name is within the supplier's products
        if (!in_array($request->input('product_name_id'), $allProductNames)) {
            return redirect()->route('admin.product')->withErrors(['product_name_id' => 'Invalid product name selected.'])->withInput();
        }

        if ($validator->fails()) {
            return redirect()->route('admin.product')->withErrors($validator)->withInput();
        }

        $products = new Product;
        $products->code = $request->input('code');
        $products->product_name_id = $request->input('product_name_id');
        $products->brand_name = $request->input('brand_name');
        $products->description = $request->input('description');
        $products->unit = $request->input('unit');
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
        return redirect()->route('admin.product')->with('success', 'Product added successfully.');
    }



    // public function searchSupplierProduct(Request $request)
    // {
    //     $searchTerm = $request->input('query');

    //     // Fetch all suppliers to ensure we check through all products
    //     $suppliers = Supplier::all();

    //     $matchingProductNames = [];

    //     foreach ($suppliers as $supplier) {
    //         // Decode the JSON-encoded product_name array
    //         $productNames = json_decode($supplier->product_name, true);

    //         // Check if productNames is an array and not empty
    //         if (is_array($productNames) && !empty($productNames)) {
    //             // Filter product names based on the search term
    //             $filteredProductNames = array_filter($productNames, function($productName) use ($searchTerm) {
    //                 return stripos($productName, $searchTerm) !== false;
    //             });

    //             // Merge the filtered product names
    //             $matchingProductNames = array_merge($matchingProductNames, $filteredProductNames);
    //         }
    //     }

    //     // Remove duplicate product names to ensure uniqueness
    //     $uniqueProductNames = array_unique($matchingProductNames);

    //     // Prepare the product names to match the expected format for suggestions
    //     $formattedProducts = array_map(function ($productName) {
    //         return ['value' => $productName];
    //     }, $uniqueProductNames);

    //     return response()->json($formattedProducts);
    // }


    public function searchSupplierProduct(Request $request)
    {
        $searchTerm = $request->input('query');

        // Fetch all suppliers to ensure we check through all products
        $suppliers = Supplier::all();

        $matchingProductNames = [];

        foreach ($suppliers as $supplier) {
            // Decode the JSON-encoded product_name array
            $productNames = json_decode($supplier->product_name_id, true);

            // Check if productNames is an array and not empty
            if (is_array($productNames) && !empty($productNames)) {
                // Filter product names based on the search term
                $filteredProductNames = array_filter($productNames, function ($productName) use ($searchTerm) {
                    // Change the condition to check if the product name starts with the search term
                    return stripos($productName, $searchTerm) === 0;
                });

                // Merge the filtered product names
                $matchingProductNames = array_merge($matchingProductNames, $filteredProductNames);
            }
        }

        // Remove duplicate product names to ensure uniqueness
        $uniqueProductNames = array_unique($matchingProductNames);

        // Prepare the product names to match the expected format for suggestions
        $formattedProducts = array_map(function ($productName) {
            return ['value' => $productName];
        }, $uniqueProductNames);

        return response()->json($formattedProducts);
    }



    public function searchProductName(Request $request)
    {
        $search = $request->input('query');

        $products = Supplier::where('product_name_id', 'LIKE', "%{$search}%") // Using '%' before and after for broader matching
            ->get(['product_name_id as value', 'selling_price']); // Ensure these column names match your database

        return response()->json($products);
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

        return view('navbar.product-edit', [
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
            'product_name_id' => 'required|unique:products,product_name_id,' . $id . ',id',
            'brand_name' => 'required',
            'description' => 'required',
            'unit' => 'required',
            'category' => 'required',
            'quantity' => 'required|numeric',
            'low_quantity_threshold' => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric|min:1',
            'selling_price' => 'required|numeric|min:1',
        ], [
            'product_name_id.unique' => 'You already have :input in your table.',
        ]);

        if ($updateValidator->fails()) {
            return redirect()->route('admin.product')->withErrors($updateValidator)->withInput();
        }

        $product = Product::find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        $product->code = $request->code;
        $product->product_name_id = $request->product_name_id;
        $product->brand_name = $request->brand_name;
        $product->description = $request->description;
        $product->unit = $request->unit;
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
        return redirect()->route('admin.product')->with("message", "Product updated successfully!");
    }

    public function productDestroy(string $id)
    {
        $products = Product::findOrFail($id);
        $products->delete();
        return redirect()->route('admin.product');
        // return back()->withSuccess('Account deleted successfully!');
    }

    public function productSearch(Request $request)
    {
        $searchTerm = $request->input('searchTerm');

        // Query the products table for matching records
        $results = Product::where('code', 'like', "%$searchTerm%")
            ->orWhere('product_name_id', 'like', "%$searchTerm%")
            ->orWhere('description', 'like', "%$searchTerm%")
            ->orWhere('category', 'like', "%$searchTerm%")
            ->get();

        // Return the results as JSON data
        return response()->json($results);
    }
    // public function searchProduct(Request $request)
    // {
    //     $searchQuery = $request->input('search');

    //     $products = Product::where('code', 'LIKE', '%' . $searchQuery . '%')
    //         ->orWhere('name', 'LIKE', '%' . $searchQuery . '%')
    //         ->orWhere('description', 'LIKE', '%' . $searchQuery . '%')
    //         ->orWhere('category', 'LIKE', '%' . $searchQuery . '%')
    //         ->orWhere('quantity', 'LIKE', '%' . $searchQuery . '%')
    //         ->orWhere('purchase_price', 'LIKE', '%' . $searchQuery . '%')
    //         ->orWhere('selling_price', 'LIKE', '%' . $searchQuery . '%')
    //         ->paginate(6);

    //     return redirect()->route('admin.product')->with('products', $products);


    //     // return redirect()->back()->with('products', $products);
    //     // return view('product', ['products' => $products])->with('username', $nm)->with('lowQuantityNotifications', $lowQuantityNotifications)->with('searchQuery', $searchQuery);
    // }





    // Transaction Controllers
    public function transaction(Request $request)
    {
        $nm = Session::get('name');
        $acc = Session::get('acc');

        $sortOption = $request->input('sort');

        $query = Transaction::query();

        if ($sortOption === 'customer_name_id_asc') {
            $query->orderBy('customer_name_id', 'asc');
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
        } elseif ($sortOption === 'date_asc') {
            $query->orderBy('created_at', 'asc');
        } elseif ($sortOption === 'date_desc') {
            $query->orderBy('created_at', 'desc');
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

        $products = Product::all();
        $customers = Customer::all();
        $searchQuery = $request->input('search');
        $transactions = $query->paginate(8);

        return view('navbar.transaction', [
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

        return view('navbar.transaction-edit', [
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

    public function calculateLastMonthSales()
    {
        // Get the first and last day of the last month
        $firstDayLastMonth = now()->subMonth()->firstOfMonth();
        $lastDayLastMonth = now()->subMonth()->lastOfMonth();

        // Query transactions for the last month
        $lastMonthTransactions = Transaction::whereBetween('created_at', [$firstDayLastMonth, $lastDayLastMonth])->get();

        // Calculate total sales for the last month
        $totalSalesLastMonth = $lastMonthTransactions->sum(function ($transaction) {
            return $transaction->qty * $transaction->selling_price;
        });

        return $totalSalesLastMonth;
    }

    public function transactionStore(Request $request)
    {
        // Validation rules
        $rules = [
            'product_name' => 'required|string',
            'selling_price' => 'required|numeric|min:0',
            'qty' => 'required|integer|min:1',
            'customer_name_id' => 'required|string',
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
        $customerName = $request->input('customer_name_id');

        // Retrieve the product's information from the Products table
        $product = Product::where('product_name_id', $productName)->where('selling_price', $selling_price)->first();

        if ($product) {
            // Calculate total price
            $totalPrice = $selling_price * $qty;

            // Calculate total earned
            $purchase_price = $product->purchase_price;
            $profit = ($selling_price - $purchase_price) * $qty;

            // Create a new Transactions record and save it to the database
            $transaction = new Transaction;
            $transaction->customer_name_id = $customerName;
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

    // public function searchProduct(Request $request)
    // {
    //     $search = $request->input('query');
    //     $products = Product::where('name', 'LIKE', "{$search}%") // Only starts with
    //         ->get(['name as value', 'selling_price']);

    //     return response()->json($products);
    // }

    public function searchProduct(Request $request)
    {
        $search = $request->input('query');
        $products = Product::where('product_name_id', 'LIKE', "{$search}%")
            ->get(['product_name_id as value', 'selling_price']);

        return response()->json($products);
    }

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
            'customer_name_id' => 'required|string',
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
            return redirect()->route('admin.transaction')->with("error", "Transaction not found!");
        }

        // Retrieve data from the request
        $productName = $request->input('product_name');
        $selling_price = $request->input('selling_price');
        $qty = $request->input('qty');
        $customerName = $request->input('customer_name_id');

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

                return redirect()->route('admin.transaction')->with("message", "Transaction updated successfully!");
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
        // return redirect()->route('admin.transaction')->withSuccess('Account deleted successfully!');
        return back()->withSuccess('Account deleted successfully!');
    }

    public function generateReport(Request $request)
    {
        $fromDate = $request->input('from_date');
        $toDate = $request->input('to_date');
        $customerName = $request->input('customer_name_id');

        // Increment the toDate by one day to include records on the toDate itself
        $toDate = date('Y-m-d', strtotime($toDate . ' +1 day'));

        $transactions = Transaction::where('customer_name_id', $customerName)
            ->whereBetween('created_at', [$fromDate, $toDate])
            ->get();

        return view('navbar.report', [
            'transactions' => $transactions,
            'customerName' => $customerName,
            'fromDate' => Carbon::parse($fromDate),
            'toDate' => Carbon::parse($toDate),
        ]);
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

        return view('navbar.customer', [
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

        return view('navbar.customer-edit', [
            'customers' => $customers,
            'username' => $nm,
            'totalNotifications' => $totalNotifications,
            'customerss' => $customerss,
        ] + $notifications);
    }

    public function customerStore(Request $request)
    {

        $request->validate([
            "customer_name_id" => "required",
            "contact_name" => "required",
            // "contact_num" => "required|between:5,11",
            "address" => "required",
        ]);

        $customers = new Customer;
        $customers->customer_name_id = $request->input('customer_name_id');
        $customers->contact_name = $request->input('contact_name');
        $customers->address = $request->input('address');
        $customers->contact_num = $request->input('contact_num');
        // $customers->item_sold = $request->input('item_sold');
        $customers->save();
        return redirect()->route('admin.customer')->with("message", "Customer added successfully!");
    }



    public function customerUpdate(Request $request, string $id)
    {

        $request->validate([
            "customer_name_id" => "required",
            "contact_name" => "required",
            // "contact_num" => "required|between:5,11",
            "address" => "required",
        ]);

        $customers = Customer::find($id);
        $customers->customer_name_id = $request->customer_name_id;
        $customers->contact_name = $request->contact_name;
        $customers->address = $request->address;
        $customers->contact_num = $request->contact_num;
        // $customers->item_sold = $request->item_sold;
        $customers->save();
        return redirect()->route('admin.customer')->with("message", "Customer updated successfully!");
    }

    public function customerDestroy(string $id)
    {
        $customers = Customer::findOrFail($id);
        $customers->delete();
        return back();
        // return redirect()->route('admin.customer')->withSuccess('Account deleted successfully!');
    }


    // Supplier Controllers
    public function supplier(Request $request)
    {
        $nm = Session::get('name');

        $sortOption = $request->input('sort');

        $query = Supplier::query();

        if ($sortOption === 'company_name_asc') {
            $query->orderBy('company_name', 'asc');
        } elseif ($sortOption === 'contact_name_asc') {
            $query->orderBy('contact_name', 'asc');
        } elseif ($sortOption === 'address_asc') {
            $query->orderBy('address', 'asc');
        } elseif ($sortOption === 'product_name_id_asc') {
            $query->orderBy('product_name_id', 'asc');
        }

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
        $suppliers = $query->paginate(8);
        $products = Product::all();
        // $suppliers = Supplier::paginate(8);

        return view('navbar.supplier', [
            'suppliers' => $suppliers,
            'totalNotifications' => $totalNotifications,
            'username' => $nm,
            'products' => $products,
        ] + $notifications);
    }

    public function supplierEdit(Request $request, $id)
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

        $supplierss = Supplier::find($id);
        $suppliers = Supplier::paginate(8);

        return view('navbar.supplier-edit', [
            'suppliers' => $suppliers,
            'supplierss' => $supplierss,
            'totalNotifications' => $totalNotifications,
            'username' => $nm,
        ] + $notifications);
    }


    // public function supplierStore(Request $request)
    // {
    //     $request->validate([
    //         "company_name" => "required",
    //         "contact_name" => "required|min:1",
    //         "product_name_id.*" => "required", // Validate each product name in the array
    //         "address" => "required",
    //         // "contact_num" => "required|digits_between:5,11",
    //     ]);

    //     $suppliers = new Supplier;
    //     $suppliers->company_name = $request->input('company_name');
    //     $suppliers->contact_name = $request->input('contact_name');
    //     $suppliers->address = $request->input('address');

    //     // Convert the product_name_id array to JSON before saving
    //     $suppliers->product_name_id = json_encode($request->input('product_name_id'));

    //     // Convert the unit array to JSON before saving
    //     $suppliers->unit = json_encode($request->input('unit'));

    //     $suppliers->contact_num = $request->input('contact_num');
    //     $suppliers->save();

    //     return back()->with("message", "Supplier added successfully!");
    // }

    public function supplierStore(Request $request)
    {
        $request->validate([
            "company_name" => "required",
            "contact_name" => "required|min:1",
            "product_name_id.*" => "required", // Validate each product name in the array
            "address" => "required",
            "date_received" => "required|date", // Validate date_received field
            // "contact_num" => "required|digits_between:5,11", // Un-comment and adjust validation for contact_num if needed
        ]);

        $suppliers = new Supplier;
        $suppliers->company_name = $request->input('company_name');
        $suppliers->contact_name = $request->input('contact_name');
        $suppliers->address = $request->input('address');
        $suppliers->date_received = $request->input('date_received'); // Save date_received field

        // Convert the product_name_id array to JSON before saving
        $suppliers->product_name_id = json_encode($request->input('product_name_id'));

        // Convert the unit array to JSON before saving
        $suppliers->unit = json_encode($request->input('unit'));

        $suppliers->contact_num = $request->input('contact_num');
        $suppliers->save();

        return back()->with("message", "Supplier added successfully!");
    }


    public function supplierUpdate(Request $request, $id)
    {
        $request->validate([
            "company_name" => "required",
            "contact_name" => "required|min:1",
            // "product_name_id" => "required",
            "address" => "required",
            // "contact_num" => "required|numeric|digits_between:5,15",
            "quantity" => "nullable|numeric|min:0", // Allow quantity to be nullable
        ]);

        // Check if the selected product exists
        $productExists = Product::where('name', $request->product_name_id)->exists();

        if (!$productExists) {
            // If the product does not exist, redirect back with an error message
            return back()->withErrors(['product_name_id' => 'The selected product does not exist.'])->withInput();
        }

        $supplier = Supplier::findOrFail($id);
        $supplier->company_name = $request->company_name;
        $supplier->contact_name = $request->contact_name;
        $supplier->address = $request->address;
        // $supplier->product_name_id = $request->product_name_id;
        $supplier->contact_num = $request->contact_num;

        // Assuming you want to keep track of which products a supplier has in a different way,
        // since the 'quantity' might be more relevant to the 'Product' model rather than 'Supplier'
        // So, this line might need adjustment or removal depending on your actual app structure
        // $supplier->quantity = $request->quantity === null ? null : $request->quantity;

        $supplier->save();

        // Update the product's quantity
        $product = Product::where('name', $request->product_name_id)->first();
        if ($request->quantity !== null) {
            $product->quantity += $request->quantity;
            $product->save();
        }

        return redirect()->route('admin.supplier')->with("message", "Supplier updated successfully!");
    }


    public function supplierDestroy(string $id)
    {
        $suppliers = Supplier::findOrFail($id);
        $suppliers->delete();
        return back();
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


        return view('navbar.delivery', [
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

    // public function getDeliveryDetails($id)
    // {
    //     $delivery = Delivery::with(['products'])->find($id); // Assuming a 'products' relationship exists on your Delivery model

    //     if (!$delivery) {
    //         return response()->json(['error' => 'Delivery not found'], 404);
    //     }

    //     // Modify the structure of your $delivery object here to include selling_price for each product
    //     // This is just an example and might need to be adjusted based on your actual database and model structure
    //     $deliveryDetails = $delivery->toArray();
    //     foreach ($delivery->products as $product) {
    //         // Assuming you have access to selling_price in your product model
    //         $deliveryDetails['products_with_prices'][] = [
    //             'product_id' => $product->id,
    //             'name' => $product->name,
    //             'selling_price' => $product->selling_price, // Or however you access the selling price
    //             'quantity' => $product->pivot->quantity, // Assuming there's a pivot table for many-to-many relationship
    //         ];
    //     }

    //     return response()->json($deliveryDetails);
    // }



    public function deliveryStore(Request $request)
    {
        $request->validate([
            'delivery_id' => ['required', 'unique:' . Delivery::class],
            'customer_name_id' => ['required'],
            'product' => ['required'],
            'quantity' => ['required'],
            'mode_of_payment' => ['required'],
            'status' => ['required'],
        ]);

        $deliveries = new Delivery;
        $deliveries->delivery_id = $request->input('delivery_id');
        $deliveries->customer_name_id = $request->input('customer_name_id');

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

        return back()->with("message", "Delivery added successfully!");
    }

    // public function deliveryStore(Request $request)
    // {
    //     $request->validate([
    //         'delivery_id' => ['required', 'unique:' . Delivery::class],
    //         'name' => ['required'],
    //         'product' => ['required', 'array'],
    //         'quantity' => ['required', 'array'],
    //         'address' => ['required'],
    //         'status' => ['required'],
    //     ]);

    //     $deliveries = new Delivery;
    //     $deliveries->delivery_id = $request->input('delivery_id');
    //     $deliveries->name = $request->input('name');
    //     $deliveries->address = $request->input('address');
    //     $deliveries->status = $request->input('status');

    //     $productList = $request->input('product');
    //     $quantityList = $request->input('quantity');

    //     $filteredQuantity = array_filter($quantityList, function ($key) use ($productList) {
    //         return in_array($key, array_keys($productList));
    //     }, ARRAY_FILTER_USE_KEY);

    //     $errors = [];
    //     foreach ($productList as $index => $productName) {
    //         if (isset($filteredQuantity[$index])) {
    //             $deliveryQuantity = $filteredQuantity[$index];
    //             $transactions = Transaction::where('product_name', $productName)->get();

    //             foreach ($transactions as $transaction) {
    //                 if ($deliveryQuantity <= 0) {
    //                     break;
    //                 }

    //                 $deductibleQuantity = min($transaction->transacted_qty, $deliveryQuantity);
    //                 $transaction->transacted_qty -= $deductibleQuantity;
    //                 $transaction->save();

    //                 $deliveryQuantity -= $deductibleQuantity;
    //             }

    //             if ($deliveryQuantity > 0) {
    //                 // Collect the error message
    //                 $errors['error_delivery_' . $index] = "Delivery quantity for product '$productName' exceeds transacted quantity.";
    //             }
    //         }
    //     }

    //     if (!empty($errors)) {
    //         // Return with all collected errors
    //         return back()->withErrors($errors);
    //     }

    //     // If all quantities are valid, proceed with saving
    //     $deliveries->product = json_encode($productList);
    //     $deliveries->quantity = json_encode($filteredQuantity);

    //     $deliveries->save();

    //     return back()->with('success', 'Delivery created successfully');
    // }


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


    // User Controllers

    public function user()
    // public function user($id)
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

        $users = User::paginate(8);
        // $usersss = User::find($id);

        return view('navbar.user', [
            'users' => $users,
            // 'usersss' => $usersss,
            'totalNotifications' => $totalNotifications,
            'username' => $nm,
        ] + $notifications);
    }

    public function userEdit(Request $request, $id)
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

        $userss = User::find($id);
        $users = User::paginate(8);

        return view('navbar.user-edit', [
            'users' => $users,
            'userss' => $userss,
            'totalNotifications' => $totalNotifications,
            'username' => $nm,
        ] + $notifications);
    }


    public function userStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'unique:' . User::class],
            // 'password' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required'],
        ]);

        $users = new User;
        $users->name = $request->input('name');
        $users->email = $request->input('email');
        $users->role = $request->input('role');
        $users->password = Hash::make($request->input('password'));

        if ($request->hasFile('photo')) {
            $fileName = time() . $request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('images', $fileName, 'public');
            $users->photo = '/storage/' . $path;
        }

        $users->save();

        // return redirect()->route('admin.user');
        return back()->with('success', 'User added successfully');
    }

    // public function userStore(Request $request): RedirectResponse
    // {
    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    //         'password' => ['required', 'confirmed', Rules\Password::defaults()],
    //     ]);

    //     $user = User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role' => $request->role,
    //     ]);

    //     event(new Registered($user));

    //     Auth::login($user);

    //     return redirect()->route('admin.user');
    //     // return redirect(RouteServiceProvider::HOME);
    // }

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

        return redirect()->route('admin.user')->with('success', 'User updated successfully');
    }

    // public function userUpdate(Request $request, string $id)
    // {
    //     $user = User::find($id);

    //     $request->validate([
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'unique:' . User::class . ',email,' . $id],
    //         'old_password' => ['required', new MatchOldPassword],
    //         'new_password' => ['nullable', 'confirmed', Rules\Password::defaults()],
    //         'role' => ['required'],
    //     ]);

    //     $user->update([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'role' => $request->role,
    //         'password' => $request->filled('new_password') ? Hash::make($request->new_password) : $user->password,
    //     ]);

    //     return redirect()->route('admin.user');
    // }



    public function userDestroy(string $id)
    {
        $users = User::findOrFail($id);
        $users->delete();
        return back();
    }
}
