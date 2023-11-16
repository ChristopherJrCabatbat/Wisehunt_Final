<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
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
    // Dashboard Controller
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


        $productCount = Product::count();
        $transactionCount = Transaction::count();
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
            'username' => $nm,
            'productCount' => $productCount,
            'transactionCount' => $transactionCount,
            'totalSalesQty' => $totalSalesQty,
            'totalEarnings' => $totalEarnings,
            'lowQuantityNotifications' => $lowQuantityNotifications,
            'datasets' => $datasets, // Adding the datasets for the bar chart
            'labels' => $labels, // Adding the labels for the bar chart
            'productLabels' => $productLabels,
            'productDatasets' => $productDatasets,
        ]);
    }

    // Product Controller
    public function product(Request $request)
    {
        $nm = Session::get('name');

        $sortOption = $request->input('sort');
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
            'searchQuery' => $searchQuery,
            'products' => $products,
            'suppliers' => $suppliers,
        ]);
    }

    public function productStore(Request $request)
    {

        // Your validation logic here
        $validatedData = $request->validate([
            'code' => 'required',
            'name' => 'required|unique:products,name,NULL,id',
            'description' => 'required',
            'category' => 'required',
            'quantity' => 'required|numeric|min:1', // Quantity should be numeric and greater than or equal to 1
            'capital' => 'required|numeric|min:1', // Capital should be numeric and greater than or equal to 1
            'unit_price' => 'required|numeric|min:1', // Unit Price should be numeric and greater than or equal to 1
        ], [
            'name.unique' => 'You already have :input in your table.',
        ]);

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
        $validatedData = $request->validate([
            'code' => 'required',
            'name' => 'required|unique:products,name,' . $id . ',id',
            'description' => 'required',
            'category' => 'required',
            'quantity' => 'required|numeric|min:1',
            'capital' => 'required|numeric|min:1',
            'unit_price' => 'required|numeric|min:1',
        ], [
            'name.unique' => 'You already have :input in your table.',
        ]);

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
        return back()->withSuccess('Account deleted successfully!');
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

        $transactions = $query->paginate(1);
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

        $customers = Customer::paginate(6);
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

        $suppliers = Supplier::paginate(6);
        return view('navbar.supplier', ['suppliers' => $suppliers])->with('username', $nm)->with('lowQuantityNotifications', $lowQuantityNotifications);
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
