<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
    // Dashboard Controller
    public function dashboard()
    {
        // Log::info('Session Data in Product Controller', ['session' => Session::all()]);
        // dd('Dashboard method', Session::all());

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
                'label' => 'Monthly earnings (' . $currentYear . ')',
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
        
        // Log::info('Session Data in Product Controller', ['session' => Session::all()]);
        // // Check if the user is authenticated
        // if (!Session::has('user')) {
        //     // dd('After middleware check, before redirect', Session::all());

        //     return redirect()->route('login');
        // }
        
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

    // public function productStore(Request $request)
    // {

    //     // Your validation logic here
    //     // $validatedData = $request->validate([
    //     $request->validate([
    //         'code' => 'required',
    //         'name' => 'required|unique:products,name,NULL,id',
    //         'description' => 'required',
    //         'category' => 'required',
    //         'quantity' => 'required|numeric|min:1', // Quantity should be numeric and greater than or equal to 1
    //         'capital' => 'required|numeric|min:1', // Capital should be numeric and greater than or equal to 1
    //         'unit_price' => 'required|numeric|min:1', // Unit Price should be numeric and greater than or equal to 1
    //     ], [
    //         'name.unique' => 'You already have :input in your table.',
    //     ]);

    //     $products = new Product;
    //     $products->code = $request->input('code');
    //     $products->name = $request->input('name');
    //     $products->description = $request->input('description');
    //     $products->category = $request->input('category');
    //     $products->quantity = $request->input('quantity');
    //     $products->capital = $request->input('capital');
    //     $products->unit_price = $request->input('unit_price');

    //     if ($request->hasFile('photo')) {
    //         $fileName = time() . $request->file('photo')->getClientOriginalName();
    //         $path = $request->file('photo')->storeAs('images', $fileName, 'public');
    //         $products->photo = '/storage/' . $path;
    //     }

    //     $products->save();
    //     return redirect()->route('admin.product')->with('success', 'Product created successfully.');
    // }

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
            'quantity' => 'required|numeric|min:1',
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

        $transactions = $query->paginate(5);
        $products = Product::all();
        $customers = Customer::all();

        $searchQuery = $request->input('search');

        return view('navbar.transaction', [
            'transactions' => $transactions, 'username' => $nm, 'products' => $products
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
                        ->withErrors(['error_change' => 'Insufficient amount tendered. Please add at least ₱' . $requiredAmount . ' to cover the total price.']);
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
