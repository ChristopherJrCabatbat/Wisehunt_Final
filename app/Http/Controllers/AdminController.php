<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Session;
use App\Models\Customer;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\UserAccount;
use App\Notifications\SMSNotification;

class AdminController extends Controller
{
    public function dashboard()
    {
        $nm = Session::get('name');
        $acc = Session::get('acc');

        // Notification
        $productsss = Product::all();
        $lowQuantityNotifications = [];

        foreach ($productsss as $product) {
            if ($product->quantity <= 20) {
                $notification = [
                    'message' => $product->name . "'s quantity is too low!",
                    'productId' => $product->id, // Assuming 'id' is the product's unique identifier
                ];
                $lowQuantityNotifications[] = $notification;
            }
        }

        $productCount = Product::count();
        $transactionCount = Transaction::count();

        $transactions_qty = DB::table('transactions')
            ->select('product_name', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty') // Order by the sum of qty in descending order
            ->limit(5)
            ->get();


        $transactions_qty_low = DB::table('transactions')
            ->select('product_name', DB::raw('SUM(qty) as total_qty'))
            ->groupBy('product_name')
            ->orderBy('total_qty') // Order by the sum of qty in ascending order
            ->limit(5)
            ->get();

        $transactions_total_price = DB::table('transactions')
            ->select('product_name', DB::raw('SUM(total_earned) as total_earning'))
            ->groupBy('product_name')
            ->orderByDesc('total_earning') // Order by the sum of total_price in descending order
            ->limit(5)
            ->get();

        $transactions_total_price_low = DB::table('transactions')
            ->select('product_name', DB::raw('SUM(total_earned) as total_earning'))
            ->groupBy('product_name')
            ->orderBy('total_earning')
            ->limit(5)
            ->get();

        $totalSalesQty = Transaction::sum('qty');
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
                'label' => 'Earnings by month (' . $currentYear . ')',
                // 'label' => '₱ ',
                'data' => $data,
                // 'backgroundColor' => 'black',
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


        // $transactions = Transactions::all();       
        return view('navbar.dashboard', [
            'productCount' => $productCount,
            'transactionCount' => $transactionCount,
            'username' => $nm,
            'transactions_qty' => $transactions_qty,
            'transactions_qty_low' => $transactions_qty_low,
            'transactions_total_price' => $transactions_total_price,
            'transactions_total_price_low' => $transactions_total_price_low,
            'totalSalesQty' => $totalSalesQty,
            'totalEarnings' => $totalEarnings,
            'lowQuantityNotifications' => $lowQuantityNotifications,
            'datasets' => $datasets, // Adding the datasets for the bar chart
            'labels' => $labels, // Adding the labels for the bar chart
            'productLabels' => $productLabels,
            'productDatasets' => $productDatasets,
        ]);
    }

    public function product(Request $request)
    {
        // dd($request->input('sort'));
        $nm = Session::get('name');
        $sortOption = $request->input('sort');
        // dd($sortOption);

        $query = Product::query();

        // Notification
        $productsss = Product::all();
        $lowQuantityNotifications = [];

        foreach ($productsss as $product) {
            if ($product->quantity <= 20) {

                // $user = UserAccount::first();
                // $user->notify(new SMSNotification);

                $notification = [
                    'message' => $product->name . "'s quantity is too low!",
                    'productId' => $product->id, // Assuming 'id' is the product's unique identifier
                ];
                $lowQuantityNotifications[] = $notification;
            }
        }

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

        // $products = $query->get();
        $products = $query->paginate(5);

        $searchQuery = $request->input('search');

        return view('navbar.product', [
            'username' => $nm,
            'lowQuantityNotifications' => $lowQuantityNotifications,
            'searchQuery' => $searchQuery,
            'products' => $products,    
        ]);
    }

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

        // Notification
        $productsss = Product::all();
        $lowQuantityNotifications = [];

        foreach ($productsss as $product) {
            if ($product->quantity <= 20) {
                $notification = [
                    'message' => $product->name . "'s quantity is too low!",
                    'productId' => $product->id, // Assuming 'id' is the product's unique identifier
                ];
                $lowQuantityNotifications[] = $notification;
            }
        }

        // $productsss = Products::all();
        // $lowQuantityNotifications = [];

        // foreach ($productsss as $product) {
        //     if ($product->quantity <= 20) {
        //         $notification = [
        //             'message' => $product->name . "'s quantity is too low!",
        //             'productId' => $product->id,
        //         ];
        //         $lowQuantityNotifications[] = $notification;

        //         // Send SMS notification
        //         sendSMSNotification($user->phone_number, $product->name); // Adjust the user and product data as needed
        //     }
        // }

        $transactions = $query->paginate(6);
        $products = Product::all();

        $searchQuery = $request->input('search');

        return view('navbar.transaction', [
            'transactions' => $transactions, 'username' => $nm, 'products' => $products
        ])->with('lowQuantityNotifications', $lowQuantityNotifications)->with('searchQuery', $searchQuery);
    }

    public function customer()
    {
        $nm = Session::get('name');
        $acc = Session::get('acc');

        // Notification
        $productsss = Product::all();
        $lowQuantityNotifications = [];

        foreach ($productsss as $product) {
            if ($product->quantity <= 20) {
                $notification = [
                    'message' => $product->name . "'s quantity is too low!",
                    'productId' => $product->id, // Assuming 'id' is the product's unique identifier
                ];
                $lowQuantityNotifications[] = $notification;
            }
        }

        $customers = Customer::paginate(8);
        return view('navbar.customer', ['customers' => $customers])->with('username', $nm)->with('lowQuantityNotifications', $lowQuantityNotifications);
    }

    public function supplier()
    {
        $nm = Session::get('name');

        // Notification
        $productsss = Product::all();
        $lowQuantityNotifications = [];

        foreach ($productsss as $product) {
            if ($product->quantity <= 20) {
                $notification = [
                    'message' => $product->name . "'s quantity is too low!",
                    'productId' => $product->id, // Assuming 'id' is the product's unique identifier
                ];
                $lowQuantityNotifications[] = $notification;
            }
        }

        $suppliers = Supplier::paginate(8);
        return view('navbar.supplier', ['suppliers' => $suppliers])->with('username', $nm)->with('lowQuantityNotifications', $lowQuantityNotifications);
    }

}
