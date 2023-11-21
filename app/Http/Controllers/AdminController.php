<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Supplier;
use App\Models\User;
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

    // Add the forecastSales method to your controller
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

            // Check if there are any transactions for the customer
            if ($customerTransactions->count() > 0) {
                // Get the timestamps of the first and last transactions
                $firstTransactionDate = Carbon::parse($customerTransactions->last()->created_at);
                $lastTransactionDate = Carbon::parse($customerTransactions->first()->created_at);

                // Calculate the average time between transactions if there is more than one transaction
                $averageTimeBetweenTransactions = ($customerTransactions->count() > 1)
                    ? $firstTransactionDate->diffInDays($lastTransactionDate) / ($customerTransactions->count() - 1)
                    : 0;

                // Determine the most suitable timeframe based on the average time between transactions
                $timeFrame = ($averageTimeBetweenTransactions <= 7) ? 'week' : 'month';

                // Calculate the end date based on the selected time frame
                $endDate = ($timeFrame == 'week') ? $lastTransactionDate->copy()->addWeek() : $lastTransactionDate->copy()->addMonth();

                // Check if the current date is within the forecast period
                if (Carbon::now()->lte($endDate)) {
                    $forecasts[] = '<span class="bold-text">ATTENTION!</span> </br>Data indicates <span class="bold-text">' . $customerId . '</span> will transact again within a <span class="bold-text">' . $timeFrame . '</span>.';

                    // $forecasts[] = '<span class="bold-text">' . $customerId . '</span> is likely to transact again within the next ' . $timeFrame . '.';
                }
            }
        }

        return $forecasts;
    }


    // Dashboard Controller
    public function dashboard()
    {
        $nm = Session::get('name');
        $acc = Session::get('acc');

        // Notification arrays
        $lowQuantityNotifications = [];
        $bestSellerNotifications = [];
        $salesForecastNotifications = [];

        // Get forecasts outside the loop to avoid duplication
        $forecasts = $this->forecastSalesForAllCustomers();

        // Find the best-selling product
        $bestSeller = Product::select('products.id', 'products.name')
            ->join('transactions', 'products.name', '=', 'transactions.product_name')
            ->selectRaw('SUM(transactions.qty) as total_qty')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->first();

        $productsss = Product::all();

        foreach ($productsss as $product) {
            // Check if there are forecasts for the customer
            $customerId = $product->customer_name; // Assuming customer_name is the customer identifier

            // Create a single message by joining the forecasts array elements
            $forecastMessage = implode('<br>', $forecasts);

            // If the product quantity is zero, add a specific message
            if ($product->quantity == 0) {
                $outOfStockNotification = [
                    'message' => '<span class="bold-text">OUT OF STOCK!<br> Update: ' . $product->name . '</span> is out of stock. Urgently needs restocking!',
                    'productId' => $product->id,
                ];

                $lowQuantityNotifications[] = $outOfStockNotification;
            } elseif (!empty($forecastMessage) && $product->quantity <= 20) {
                // If there are forecasts and the quantity is low, add them to low quantity notifications
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


        // Count the total quantity sold for the day
        $totalSalesQty = Transaction::selectRaw('SUM(qty) as total_qty')
            ->whereDate('created_at', today()) // Change this to match your date format
            ->value('total_qty') ?? 0;

        $productCount = Product::count();
        $transactionCount = Transaction::count();
        $totalEarnings = Transaction::sum('total_earned');

        // Bar Chart/Graph
        $currentYear = date('Y');

        $earnings = Transaction::selectRaw('MONTH(created_at) as month, SUM(total_earned) as total_earned')
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
                    $earningsPerMonth = $earning->total_earned;
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

        // Pie Chart/Graph
        $productQuantities = DB::table('transactions')
            ->select('product_name', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        $productLabels = [];
        $productData = [];
        $productColors = ['#2c5c78', '#2dc0d0', '#6c6c6c', '#050A30', '#0000FF'];

        foreach ($productQuantities as $productQuantity) {
            array_push($productLabels, $productQuantity->product_name);
            array_push($productData, $productQuantity->total_qty);
        }

        $productDatasets = [
            [
                'label' => '',
                'data' => $productData,
                'backgroundColor' => $productColors,
            ]
        ];

        // Pass both arrays to the view
        return view('navbar.dashboard', [
            'username' => $nm,
            'productCount' => $productCount,
            'transactionCount' => $transactionCount,
            'totalSalesQty' => $totalSalesQty,
            'totalEarnings' => $totalEarnings,
            'lowQuantityNotifications' => $lowQuantityNotifications,
            'bestSellerNotifications' => $bestSellerNotifications,
            'datasets' => $datasets, // Adding the datasets for the bar chart
            'labels' => $labels, // Adding the labels for the bar chart
            'productLabels' => $productLabels,
            'salesForecastNotifications' => $salesForecastNotifications,
            'productDatasets' => $productDatasets,
            'bestSeller' => $bestSeller, // Pass the best seller information to the view
        ]);
    }

    // public function dashboard()
    // {
    //     $nm = Session::get('name');
    //     $acc = Session::get('acc');

    //     // Notification arrays
    //     $lowQuantityNotifications = [];
    //     $bestSellerNotifications = [];
    //     $salesForecastNotifications = [];

    //     // Get forecasts outside the loop to avoid duplication
    //     $forecasts = $this->forecastSalesForAllCustomers();

    //     // Find the best-selling product
    //     $bestSeller = Product::select('products.id', 'products.name')
    //         ->join('transactions', 'products.name', '=', 'transactions.product_name')
    //         ->selectRaw('SUM(transactions.qty) as total_qty')
    //         ->groupBy('products.id', 'products.name')
    //         ->orderByDesc('total_qty')
    //         ->first();

    //     $productsss = Product::all();

    //     foreach ($productsss as $product) {
    //         // Check if there are forecasts for the customer
    //         $customerId = $product->customer_name; // Assuming customer_name is the customer identifier

    //         // Create a single message by joining the forecasts array elements
    //         $forecastMessage = implode('<br>', $forecasts);

    //         // If there are forecasts, add them to low quantity notifications
    //         if (!empty($forecastMessage) && $product->quantity <= 20) {
    //             $notification = [
    //                 'message' => '<span class="bold-text">' . $product->name . "</span>'s quantity is too low!",
    //                 'forecastMessage' => $forecastMessage,
    //                 'productId' => $product->id,
    //             ];

    //             $lowQuantityNotifications[] = $notification;
    //         }

    //         // Display the best seller quantity sold in the notification
    //         if ($bestSeller && $bestSeller->id == $product->id && $bestSeller->total_qty > 0) {
    //             $bestSellerNotification = [
    //                 'message' => '<span class="bold-text">' . e($bestSeller->name) . '</span> is your best seller. It might be wise to increase stock levels to meet the high demand and capitalize on its popularity.',
    //                 'productId' => $bestSeller->id,
    //             ];

    //             $bestSellerNotifications[] = $bestSellerNotification;

    //         }
    //     }

    //     // Count the total quantity sold for the day
    //     $totalSalesQty = Transaction::selectRaw('SUM(qty) as total_qty')
    //         ->whereDate('created_at', today()) // Change this to match your date format
    //         ->value('total_qty') ?? 0;

    //     $productCount = Product::count();
    //     $transactionCount = Transaction::count();
    //     $totalEarnings = Transaction::sum('total_earned');

    //     // Bar Chart/Graph
    //     $currentYear = date('Y');

    //     $earnings = Transaction::selectRaw('MONTH(created_at) as month, SUM(total_earned) as total_earned')
    //         ->whereYear('created_at', $currentYear)
    //         ->groupBy('month')
    //         ->orderBy('month')
    //         ->get();

    //     $labels = [];
    //     $data = [];
    //     $colors = ['#2c5c78', '#2dc0d0', '#6c6c6c', '#2c5c78', '#2dc0d0', '#6c6c6c', '#2c5c78', '#2dc0d0', '#6c6c6c', '#2c5c78', '#2dc0d0', '#6c6c6c'];

    //     for ($i = 1; $i <= 12; $i++) {
    //         $month = date('F', mktime(0, 0, 0, $i, 1));
    //         $earningsPerMonth = 0;

    //         foreach ($earnings as $earning) {
    //             if ($earning->month == $i) {
    //                 $earningsPerMonth = $earning->total_earned;
    //                 break;
    //             }
    //         }

    //         array_push($labels, $month);
    //         array_push($data, $earningsPerMonth);
    //     }

    //     $datasets = [
    //         [
    //             'label' => 'Monthly earnings (' . $currentYear . ')',
    //             'data' => $data,
    //             'backgroundColor' => $colors,
    //         ]
    //     ];

    //     // Pie Chart/Graph
    //     $productQuantities = DB::table('transactions')
    //         ->select('product_name', DB::raw('SUM(qty) as total_qty'))
    //         ->groupBy('product_name')
    //         ->orderByDesc('total_qty')
    //         ->limit(5)
    //         ->get();

    //     $productLabels = [];
    //     $productData = [];
    //     $productColors = ['#2c5c78', '#2dc0d0', '#6c6c6c', '#050A30', '#0000FF'];

    //     foreach ($productQuantities as $productQuantity) {
    //         array_push($productLabels, $productQuantity->product_name);
    //         array_push($productData, $productQuantity->total_qty);
    //     }

    //     $productDatasets = [
    //         [
    //             'label' => '',
    //             'data' => $productData,
    //             'backgroundColor' => $productColors,
    //         ]
    //     ];

    //     // Pass both arrays to the view
    //     return view('navbar.dashboard', [
    //         'username' => $nm,
    //         'productCount' => $productCount,
    //         'transactionCount' => $transactionCount,
    //         'totalSalesQty' => $totalSalesQty,
    //         'totalEarnings' => $totalEarnings,
    //         'lowQuantityNotifications' => $lowQuantityNotifications,
    //         'bestSellerNotifications' => $bestSellerNotifications,
    //         'datasets' => $datasets, // Adding the datasets for the bar chart
    //         'labels' => $labels, // Adding the labels for the bar chart
    //         'productLabels' => $productLabels,
    //         'salesForecastNotifications' => $salesForecastNotifications,
    //         'productDatasets' => $productDatasets,
    //         'bestSeller' => $bestSeller, // Pass the best seller information to the view
    //     ]);
    // }





    // Product Controller
    public function product(Request $request)
    {
        $nm = Session::get('name');
        $query = Product::query();

        // Notification arrays
        $lowQuantityNotifications = [];
        $bestSellerNotifications = [];
        $salesForecastNotifications = [];

        // Get forecasts outside the loop to avoid duplication
        $forecasts = $this->forecastSalesForAllCustomers();

        // Find the best-selling product
        $bestSeller = Product::select('products.id', 'products.name')
            ->join('transactions', 'products.name', '=', 'transactions.product_name')
            ->selectRaw('SUM(transactions.qty) as total_qty')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->first();

        $productsss = Product::all();

        foreach ($productsss as $product) {
            // Check if there are forecasts for the customer
            $customerId = $product->customer_name; // Assuming customer_name is the customer identifier

            // Create a single message by joining the forecasts array elements
            $forecastMessage = implode('<br>', $forecasts);

            // If there are forecasts, add them to low quantity notifications
            if (!empty($forecastMessage) && $product->quantity <= 20) {
                $notification = [
                    'message' => '<span class="bold-text">' . $product->name . "</span>'s quantity is too low!",
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

        // Sort
        $sortOption = $request->input('sort');

        if ($sortOption === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sortOption === 'category_asc') {
            $query->orderBy('category', 'asc');
        } elseif ($sortOption === 'quantity_asc') {
            $query->orderBy('quantity', 'asc');
        } elseif ($sortOption === 'capital_asc') {
            $query->orderBy('capital', 'asc');
        } elseif ($sortOption === 'unit_price_asc') {
            $query->orderBy('unit_price', 'asc');
        }

        $suppliers = Supplier::all();
        $products = $query->paginate(5);
        $searchQuery = $request->input('search');

        return view('navbar.product', [
            'username' => $nm,
            'lowQuantityNotifications' => $lowQuantityNotifications,
            'salesForecastNotifications' => $salesForecastNotifications,
            'searchQuery' => $searchQuery,
            'products' => $products,
            'bestSellerNotifications' => $bestSellerNotifications,
            'bestSeller' => $bestSeller, // Pass the best seller information to the view
            'suppliers' => $suppliers,
        ]);
    }


    public function productStore(Request $request)
    {
        // Your validation logic here
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required|unique:products,name,NULL,id',
            'description' => 'required',
            'category' => 'required',
            'quantity' => 'required|numeric|min:1',
            'capital' => 'required|numeric|min:1',
            'unit_price' => 'required|numeric|min:1',
        ], [
            'name.unique' => 'You already have :input in your table.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.product')->withErrors($validator)->withInput();
        }

        $products = new Product;
        $products->code = $request->input('code');
        $products->name = $request->input('name');
        $products->description = $request->input('description');
        $products->category = $request->input('category');
        $products->quantity = $request->input('quantity');
        $products->capital = $request->input('capital');
        $products->unit_price = $request->input('unit_price');

        if ($request->hasFile('photo')) {
            $fileName = time() . $request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('images', $fileName, 'public');
            $products->photo = '/storage/' . $path;
        }

        $products->save();
        return redirect()->route('admin.product')->with('success', 'Product created successfully.');
    }

    public function productUpdate(Request $request, string $id)
    {
        $updateValidator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required|unique:products,name,' . $id . ',id',
            'description' => 'required',
            'category' => 'required',
            'quantity' => 'required|numeric|min:0',
            'capital' => 'required|numeric|min:1',
            'unit_price' => 'required|numeric|min:1',
        ], [
            'name.unique' => 'You already have :input in your table.',
        ]);

        if ($updateValidator->fails()) {
            return redirect()->route('admin.product')->withErrors($updateValidator)->withInput();
        }

        $product = Product::find($id);

        if (!$product) {
            return redirect()->back()->with('error', 'Product not found');
        }

        $product->code = $request->code;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->quantity = $request->quantity;
        $product->capital = $request->capital;
        $product->unit_price = $request->unit_price;

        if ($request->hasFile('photo')) {
            $fileName = time() . $request->file('photo')->getClientOriginalName();
            $path = $request->file('photo')->storeAs('images', $fileName, 'public');
            $product->photo = '/storage/' . $path;
        }

        $product->save();
        return redirect()->route('admin.product');
    }

    public function productDestroy(string $id)
    {
        $products = Product::findOrFail($id);
        $products->delete();
        return redirect()->route('admin.product');
        // return back()->withSuccess('Account deleted successfully!');
    }

    public function searchProduct(Request $request)
    {
        $searchQuery = $request->input('search');

        $products = Product::where('code', 'LIKE', '%' . $searchQuery . '%')
            ->orWhere('name', 'LIKE', '%' . $searchQuery . '%')
            ->orWhere('description', 'LIKE', '%' . $searchQuery . '%')
            ->orWhere('category', 'LIKE', '%' . $searchQuery . '%')
            ->orWhere('quantity', 'LIKE', '%' . $searchQuery . '%')
            ->orWhere('capital', 'LIKE', '%' . $searchQuery . '%')
            ->orWhere('unit_price', 'LIKE', '%' . $searchQuery . '%')
            ->paginate(6);

        return redirect()->route('admin.product')->with('products', $products);


        // return redirect()->back()->with('products', $products);
        // return view('product', ['products' => $products])->with('username', $nm)->with('lowQuantityNotifications', $lowQuantityNotifications)->with('searchQuery', $searchQuery);
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
        } elseif ($sortOption === 'unit_price_asc') {
            $query->orderBy('unit_price', 'asc');
        } elseif ($sortOption === 'total_price_asc') {
            $query->orderBy('total_price', 'asc');
        } elseif ($sortOption === 'total_earned_asc') {
            $query->orderByDesc('total_earned');
        }

        // Notification arrays
        $lowQuantityNotifications = [];
        $bestSellerNotifications = [];
        $salesForecastNotifications = [];

        // Get forecasts outside the loop to avoid duplication
        $forecasts = $this->forecastSalesForAllCustomers();

        // Find the best-selling product
        $bestSeller = Product::select('products.id', 'products.name')
            ->join('transactions', 'products.name', '=', 'transactions.product_name')
            ->selectRaw('SUM(transactions.qty) as total_qty')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->first();

        $productsss = Product::all();

        foreach ($productsss as $product) {
            // Check if there are forecasts for the customer
            $customerId = $product->customer_name; // Assuming customer_name is the customer identifier

            // Create a single message by joining the forecasts array elements
            $forecastMessage = implode('<br>', $forecasts);

            // If there are forecasts, add them to low quantity notifications
            if (!empty($forecastMessage) && $product->quantity <= 20) {
                $notification = [
                    'message' => '<span class="bold-text">' . $product->name . "</span>'s quantity is too low!",
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

        $transactions = $query->paginate(5);
        $products = Product::all();
        $customers = Customer::all();

        $searchQuery = $request->input('search');

        return view('navbar.transaction', [
            'bestSellerNotifications' => $bestSellerNotifications,
            'transactions' => $transactions, 'username' => $nm, 'products' => $products,
            'salesForecastNotifications' => $salesForecastNotifications,

        ])->with('lowQuantityNotifications', $lowQuantityNotifications)->with('searchQuery', $searchQuery)->with('customers', $customers);
    }


    public function transactionStore(Request $request)
    {
        // Retrieve data from the request
        $productName = $request->input('product_name');
        $unitPrice = $request->input('unit_price');
        $qty = $request->input('qty');
        $amountTendered = $request->input('amount_tendered');
        $customerName = $request->input('customer_name');
        $customerPhone = $request->input('contact_num');

        // Retrieve the product's capital from the Products table (assuming you have a 'Product' model)
        $product = Product::where('name', $productName)->where('unit_price', $unitPrice)->first();

        if ($product) {
            // Check if there's enough quantity to subtract
            if ($product->quantity >= $qty) {
                // Calculate total price
                $totalPrice = $unitPrice * $qty;

                // Calculate change due
                $changeDue = $amountTendered - $totalPrice;

                // Check if change_due is negative
                if ($changeDue < 0) {
                    // Calculate the required amount to cover total_price
                    $requiredAmount = $totalPrice - $amountTendered;

                    // Return an error message with the required amount
                    return redirect()->back()
                        ->withInput()
                        // ->withErrors(['error_change' => 'Insufficient amount tendered. Please add at least ₱' . $requiredAmount . ' to cover the total price.']);
                        ->withErrors(['error_change' => 'Insufficient amount tendered. Your total price is: ' . $totalPrice . '.']);
                }

                // Calculate total earned
                $capital = $product->capital;
                $totalEarned = ($unitPrice - $capital) * $qty;

                // Create a new Transactions record and save it to the database
                $transaction = new Transaction;
                $transaction->product_name = $productName;
                $transaction->unit_price = $unitPrice;
                $transaction->qty = $qty;
                $transaction->amount_tendered = $amountTendered;
                $transaction->customer_name = $customerName;
                $transaction->contact_num = $customerPhone;
                $transaction->change_due = $changeDue;
                $transaction->total_price = $totalPrice;
                $transaction->total_earned = $totalEarned;
                $transaction->save();

                // Update the product quantity by subtracting the sold quantity
                $product->quantity -= $qty;
                $product->save();

                return back();
                // return redirect()->route('admin.transaction');
            } else {
                // Handle the case where the quantity is insufficient
                return redirect()->back()
                    ->withInput()
                    ->withErrors(['error_stock' => 'Insufficient quantity in stock. Remaining quantity: ' . $product->quantity]);
            }
        } else {
            // Keep the form data and repopulate the fields
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Selected product and unit price did not match.']);
        }
    }

    public function transactionUpdate(Request $request, string $id)
    {
        // Aayusin pa ito

        // Retrieve the existing transaction record by its ID
        $transaction = Transaction::find($id);

        if (!$transaction) {
            return redirect()->route('admin.transaction')->with("error", "Transaction not found!");
        }

        // Retrieve data from the request
        $productName = $request->input('product_name');
        $unitPrice = $request->input('unit_price');
        $qty = $request->input('qty');
        $amountTendered = $request->input('amount_tendered');
        $customerName = $request->input('customer_name');
        $customerPhone = $request->input('contact_num');

        // Retrieve the old 'qty' for the transaction
        $oldQty = $transaction->qty;

        // Retrieve the product's capital from the Products table (assuming you have a 'Product' model)
        $product = Product::where('name', $productName)->where('unit_price', $unitPrice)->first();

        if ($product) {
            // Check if there's enough quantity to subtract
            if ($product->quantity + $oldQty >= $qty) {
                // Calculate total price
                $totalPrice = $unitPrice * $qty;

                // Calculate change due
                $changeDue = $amountTendered - $totalPrice;

                // Calculate total earned
                $capital = $product->capital;
                $totalEarned = ($unitPrice - $capital) * $qty;

                // Update the existing transaction record with the new data
                $transaction->product_name = $productName;
                $transaction->unit_price = $unitPrice;
                $transaction->qty = $qty;
                $transaction->amount_tendered = $amountTendered;
                $transaction->customer_name = $customerName;
                $transaction->contact_num = $customerPhone;
                $transaction->change_due = $changeDue;
                $transaction->total_price = $totalPrice;
                $transaction->total_earned = $totalEarned;
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
            // Keep the form data and repopulate the fields
            // return redirect()->back()
            //     ->withInput()
            //     ->withErrors(['error' => 'Selected product and unit price did not match.']);
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

        // Increment the toDate by one day to include records on the toDate itself
        $toDate = date('Y-m-d', strtotime($toDate . ' +1 day'));

        $transactions = Transaction::whereBetween('created_at', [$fromDate, $toDate])->get();
        return view('navbar.report', [
            'transactions' => $transactions,
            // 'fromDate' => $fromDate, // Pass both from and to dates to the view
            // 'toDate' => $toDate,
            'fromDate' => Carbon::parse($fromDate),
            'toDate' => Carbon::parse($toDate),

        ]);
    }


    // Customer Controllers
    public function customer()
    {
        $nm = Session::get('name');
        $acc = Session::get('acc');

        // Notification arrays
        $lowQuantityNotifications = [];
        $bestSellerNotifications = [];
        $salesForecastNotifications = [];

        // Get forecasts outside the loop to avoid duplication
        $forecasts = $this->forecastSalesForAllCustomers();

        // Find the best-selling product
        $bestSeller = Product::select('products.id', 'products.name')
            ->join('transactions', 'products.name', '=', 'transactions.product_name')
            ->selectRaw('SUM(transactions.qty) as total_qty')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->first();

        $productsss = Product::all();

        foreach ($productsss as $product) {
            // Check if there are forecasts for the customer
            $customerId = $product->customer_name; // Assuming customer_name is the customer identifier

            // Create a single message by joining the forecasts array elements
            $forecastMessage = implode('<br>', $forecasts);

            // If there are forecasts, add them to low quantity notifications
            if (!empty($forecastMessage) && $product->quantity <= 20) {
                $notification = [
                    'message' => '<span class="bold-text">' . $product->name . "</span>'s quantity is too low!",
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

        $customers = Customer::paginate(6);
        return view('navbar.customer', [
            'customers' => $customers, 'bestSellerNotifications' => $bestSellerNotifications, 'salesForecastNotifications' => $salesForecastNotifications,
            'username' => $nm,
            'lowQuantityNotifications' => $lowQuantityNotifications,
        ]);
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
        $customers->contact_person = $request->input('contact_person');
        $customers->address = $request->input('address');
        $customers->contact_num = $request->input('contact_num');
        $customers->item_sold = $request->input('item_sold');
        $customers->save();
        return redirect()->route('admin.customer')->with("message", "Customer added successfully!");
    }

    public function customerUpdate(Request $request, string $id)
    {
        $customers = Customer::find($id);
        $customers->name = $request->name;
        $customers->contact_person = $request->contact_person;
        $customers->address = $request->address;
        $customers->contact_num = $request->contact_num;
        $customers->item_sold = $request->item_sold;
        $customers->save();
        return redirect()->route('admin.customer')->with("message", "Customer updated successfully!");
    }

    public function customerDestroy(string $id)
    {
        $customers = Customer::findOrFail($id);
        $customers->delete();
        return redirect()->route('admin.customer')->withSuccess('Account deleted successfully!');
    }


    // Supplier Controllers
    public function supplier()
    {
        $nm = Session::get('name');

        // Notification arrays
        $lowQuantityNotifications = [];
        $bestSellerNotifications = [];
        $salesForecastNotifications = [];

        // Get forecasts outside the loop to avoid duplication
        $forecasts = $this->forecastSalesForAllCustomers();

        // Find the best-selling product
        $bestSeller = Product::select('products.id', 'products.name')
            ->join('transactions', 'products.name', '=', 'transactions.product_name')
            ->selectRaw('SUM(transactions.qty) as total_qty')
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_qty')
            ->first();

        $productsss = Product::all();

        foreach ($productsss as $product) {
            // Check if there are forecasts for the customer
            $customerId = $product->customer_name; // Assuming customer_name is the customer identifier

            // Create a single message by joining the forecasts array elements
            $forecastMessage = implode('<br>', $forecasts);

            // If there are forecasts, add them to low quantity notifications
            if (!empty($forecastMessage) && $product->quantity <= 20) {
                $notification = [
                    'message' => '<span class="bold-text">' . $product->name . "</span>'s quantity is too low!",
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

        $suppliers = Supplier::paginate(6);
        // 'bestSellerNotifications' => $bestSellerNotifications,
        return view('navbar.supplier', ['suppliers' => $suppliers, 'salesForecastNotifications' => $salesForecastNotifications, 'bestSellerNotifications' => $bestSellerNotifications,])->with('username', $nm)->with('lowQuantityNotifications', $lowQuantityNotifications);
    }


    public function supplierStore(Request $request)
    {

        $request->validate([
            "supplier" => "required",
            "contact_person" => "required|min:1|max:20",
            "address" => "required",
            "contact_num" => "required|numeric|digits_between:5,11",
        ]);

        $suppliers = new Supplier;
        $suppliers->supplier = $request->input('supplier');
        $suppliers->contact_person = $request->input('contact_person');
        $suppliers->address = $request->input('address');
        $suppliers->product_name = $request->input('product_name');
        $suppliers->contact_num = $request->input('contact_num');
        $suppliers->save();
        // return redirect()->route('admin.supplier');
        return back();
    }

    public function supplierUpdate(Request $request, string $id)
    {
        $suppliers = Supplier::find($id);
        $suppliers->supplier = $request->supplier;
        $suppliers->contact_person = $request->contact_person;
        $suppliers->address = $request->address;
        $suppliers->product_name = $request->product_name;
        $suppliers->contact_num = $request->contact_num;
        $suppliers->save();
        // return redirect()->route('admin.supplier')->with("message", "Supplier updated successfully!");
        return back();
    }

    public function supplierDestroy(string $id)
    {
        $suppliers = Supplier::findOrFail($id);
        $suppliers->delete();
        return back();
    }
}
