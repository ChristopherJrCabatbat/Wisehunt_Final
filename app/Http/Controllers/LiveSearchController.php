<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Supplier;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LiveSearchController extends Controller
{
    public function productSearch(Request $request)
    {
        $rowNumber = 1;
        $output = "";

        $products = Product::where('code', 'Like', '%' . $request->search . '%')
            ->orWhere('product_name_id', 'LIKE', '%' . $request->search . '%')
            ->orWhere('description', 'LIKE', '%' . $request->search . '%')
            ->orWhere('category', 'LIKE', '%' . $request->search . '%')
            ->get();

        foreach ($products as $product) {
            $output .=
                '<tr>
                    <td>' . $rowNumber++ . '</td>
                    <td> ' . $product->code . ' </td>
                    <td> ' . $product->product_name_id . ' </td>
                    <td> ' . $product->brand_name . ' </td>
                    <td> ' . $product->description . ' </td>
                    <td> ' . $product->unit . ' </td>
                    <td> ' . $product->category . ' </td>
                    <td>
                        <img src="' . asset($product->photo) . '" alt="' . $product->product_name_id . '" width="auto" height="50px" style="background-color: transparent">
                    </td>
                    <td> ' . $product->quantity . ' </td>
                    <td class="nowrap"> ₱ ' . number_format($product->purchase_price) . ' </td>
                    <td class="nowrap"> ₱ ' . number_format($product->selling_price) . ' </td>
                    <td class="actions">
                        <div class="actions-container">
                            
                            <form action="' . route('admin.productDestroy', $product->id) . '" method="POST">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button onclick="return confirm(\'Are you sure you want to delete this?\')" type="submit" class="delete" id="delete">
                                    <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>';
        }

        return response($output ?: '');
    }

    // public function transactionSearch(Request $request)
    // {
    //     $rowNumber = 1;
    //     $output = "";

    //     // $searchTerm = $request->search;
    //     // $formattedDate = date('Y-m-d', strtotime($searchTerm));

    //     // $transactions = Transaction::where('customer_name', 'like', '%' . $searchTerm . '%')
    //     //     ->orWhere('product_name', 'like', '%' . $searchTerm . '%')
    //     //     ->orWhere(function ($query) use ($formattedDate) {
    //     //         $query->whereDate('created_at', $formattedDate);
    //     //     })
    //     //     ->get();

    //     $searchTerm = $request->search;
    //     $formattedDate = date('Y-m-d', strtotime($searchTerm));

    //     $transactions = Transaction::where('customer_name', 'like', '%' . $searchTerm . '%')
    //         ->orWhere('product_name', 'like', '%' . $searchTerm . '%')
    //         ->orWhereDate('created_at', $formattedDate)
    //         // ->orWhere(function ($query) use ($formattedDate) {
    //         //     // Check for a partial date match using LIKE
    //         //     $query->where(DB::raw('DATE(created_at)'), 'LIKE', '%' . $formattedDate . '%');
    //         // })
    //         ->get();

    //     foreach ($transactions as $transaction) {
    //         $output .= '<tr>
    //             <td class="transcact-td">' . $rowNumber++ . '</td>
    //             <td class="transcact-td"> ' . $transaction->customer_name . ' </td>
    //             <td class="transcact-td"> ' . $transaction->product_name . ' </td>
    //             <td class="transcact-td"> ' . $transaction->qty . ' </td>
    //             <td class="nowrap transcact-td"> ₱ ' . number_format($transaction->selling_price) . ' </td>
    //             <td class="nowrap transcact-td"> ₱ ' . number_format($transaction->total_price) . ' </td>
    //             <td class="nowrap transcact-td"> ₱ ' . number_format($transaction->profit) . ' </td>
    //             <td> ' . $transaction->created_at->format('M. d, Y') . ' </td>
    //             </tr>';
    //     }

    //     return response($output ?: '');
    // }

    public function transactionSearch(Request $request)
    {
        $rowNumber = 1;
        $output = "";

        $searchTerm = $request->search;

        // Log the search term
        Log::info('Search Term: ' . $searchTerm);

        $transactions = Transaction::where('customer_name', 'like', '%' . $searchTerm . '%')
            ->orWhere('product_name', 'like', '%' . $searchTerm . '%')
            ->orWhere(function ($query) use ($searchTerm) {
                // Check if the search term is a valid date
                $formattedDate = date('Y-m-d', strtotime($searchTerm));
                if ($formattedDate && strtotime($formattedDate) === strtotime($searchTerm)) {
                    // Use whereDate for an exact date match
                    $query->orWhereDate('created_at', $formattedDate);
                } else {
                    // Use like for partial date match on both formats
                    $query->orWhereRaw("DATE_FORMAT(created_at, '%b. %d, %Y') LIKE ?", ['%' . $searchTerm . '%'])
                        ->orWhere('created_at', 'like', '%' . $formattedDate . '%');
                }
            })
            ->get();

        // Log the number of transactions retrieved
        Log::info('Number of Transactions: ' . $transactions->count());

        foreach ($transactions as $transaction) {
            $output .= '<tr>
            <td class="transcact-td">' . $rowNumber++ . '</td>
            <td class="transcact-td"> ' . $transaction->customer_name . ' </td>
            <td class="transcact-td"> ' . $transaction->product_name . ' </td>
            <td class="transcact-td"> ' . $transaction->qty . ' </td>
            <td class="nowrap transcact-td"> ₱ ' . number_format($transaction->selling_price) . ' </td>
            <td class="nowrap transcact-td"> ₱ ' . number_format($transaction->total_price) . ' </td>
            <td class="nowrap transcact-td"> ₱ ' . number_format($transaction->profit) . ' </td>
            <td> ' . $transaction->created_at->format('M. d, Y') . ' </td>
            </tr>';
        }

        return response($output ?: '');
    }


    // public function transactionSearch(Request $request)
    // {
    //     $rowNumber = 1;
    //     $output = "";

    //     $searchTerm = $request->search;

    //     // Log the search term
    //     Log::info('Search Term: ' . $searchTerm);

    //     $transactions = Transaction::where('customer_name', 'like', '%' . $searchTerm . '%')
    //         ->orWhere('product_name', 'like', '%' . $searchTerm . '%')
    //         ->orWhere(function ($query) use ($searchTerm) {
    //             // Check if the search term is a valid date
    //             $formattedDate = date('Y-m-d', strtotime($searchTerm));
    //             if ($formattedDate && strtotime($formattedDate) === strtotime($searchTerm)) {
    //                 // Use whereDate for an exact date match
    //                 $query->orWhereDate('created_at', $formattedDate);
    //             } else {
    //                 // Use like for partial date match
    //                 $query->orWhereRaw("DATE_FORMAT(created_at, '%b. %d, %Y') LIKE ?", ['%' . $searchTerm . '%']);
    //             }
    //         })
    //         ->get();

    //     // Log the number of transactions retrieved
    //     Log::info('Number of Transactions: ' . $transactions->count());

    //     foreach ($transactions as $transaction) {
    //         $output .= '<tr>
    //         <td class="transcact-td">' . $rowNumber++ . '</td>
    //         <td class="transcact-td"> ' . $transaction->customer_name . ' </td>
    //         <td class="transcact-td"> ' . $transaction->product_name . ' </td>
    //         <td class="transcact-td"> ' . $transaction->qty . ' </td>
    //         <td class="nowrap transcact-td"> ₱ ' . number_format($transaction->selling_price) . ' </td>
    //         <td class="nowrap transcact-td"> ₱ ' . number_format($transaction->total_price) . ' </td>
    //         <td class="nowrap transcact-td"> ₱ ' . number_format($transaction->profit) . ' </td>
    //         <td> ' . $transaction->created_at->format('M. d, Y') . ' </td>
    //         </tr>';
    //     }

    //     return response($output ?: '');
    // }






    public function supplierSearch(Request $request)
    {
        $rowNumber = 1;
        $output = "";

        $suppliers = Supplier::where('company_name', 'Like', '%' . $request->search . '%')
            ->orWhere('contact_name', 'LIKE', '%' . $request->search . '%')
            ->orWhere('product_name_id', 'LIKE', '%' . $request->search . '%')
            ->get();

        foreach ($suppliers as $supplier) {
            $output .=

                '<tr>

            <td>' . $rowNumber++ . '</td>
            <td> ' . $supplier->company_name . ' </td>
            <td> ' . $supplier->contact_name . ' </td>
            <td> ' . $supplier->contact_num . ' </td>
            <td> ' . $supplier->address . ' </td>
            <td> ' . implode(', ', json_decode($supplier->product_name_id, true)) . 
            ' <button type="button" onclick="showProducts(\''. addslashes($supplier->company_name) .'\', \''. htmlspecialchars($supplier->product_name_id, ENT_QUOTES) .'\')">View All</button>' . ' </td>
            <td class="actions">
                <div class="actions-container">
                        <form action="' . route('admin.supplierEdit', $supplier->id) . '" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('GET') . '
                        <button type="submit" class="edit editButton" id="edit" data-id="' . $supplier->id . '">
                            <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                        </button>
                    </form>
                    <form action="' . route('admin.supplierDestroy', $supplier->id) . '" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button onclick="return confirm(\'Are you sure you want to delete this?\')" type="submit" class="delete" id="delete">
                            <i class="fa-solid fa-trash" style="color: #ffffff;"></i>
                        </button>
                    </form>
                </div>
            </td>

            <tr>';
        }

        return response($output ?: '');
    }
}
