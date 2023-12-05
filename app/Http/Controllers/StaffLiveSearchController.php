<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;

class StaffLiveSearchController extends Controller
{
    public function productSearch(Request $request)
    {
        $rowNumber = 1;
        $output = "";
    
        $products = Product::where('code', 'Like', '%' . $request->search . '%')
            ->orWhere('name', 'LIKE', '%' . $request->search . '%')
            ->orWhere('description', 'LIKE', '%' . $request->search . '%')
            ->orWhere('category', 'LIKE', '%' . $request->search . '%')
            ->get();
    
        foreach ($products as $product) {
            $output .=
                '<tr>
                    <td>' . $rowNumber++ . '</td>
                    <td> ' . $product->code . ' </td>
                    <td> ' . $product->name . ' </td>
                    <td> ' . $product->name . ' </td>
                    <td> ' . $product->description . ' </td>
                    <td> ' . $product->category . ' </td>
                    <td>
                        <img src="' . asset($product->photo) . '" alt="' . $product->name . '" width="auto" height="50px" style="background-color: transparent">
                    </td>
                    <td> ' . $product->quantity . ' </td>
                    <td class="nowrap"> ₱ ' . number_format($product->capital) . ' </td>
                    <td class="nowrap"> ₱ ' . number_format($product->unit_price) . ' </td>
                    <td class="actions">
                        <div class="actions-container">
                            <form action="' . route('staff.productEdit', $product->id) . '" method="POST">
                                ' . csrf_field() . '
                                ' . method_field('GET') . '
                                <button type="submit" class="edit editButton" id="edit">
                                <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                            </button>
                            </form>
                            <form action="' . route('staff.productDestroy', $product->id) . '" method="POST">
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

    public function transactionSearch(Request $request)
    {
        $rowNumber = 1;
        $output="";

        $transactions = Transaction::where('customer_name', 'Like', '%' . $request->search . '%')
        ->orWhere('product_name', 'LIKE', '%' . $request->search . '%')
        ->get();

        foreach ($transactions as $transaction) 
        {
            $output.=

            '<tr>

            <td>' . $rowNumber++ . '</td>
            <td> '.$transaction->customer_name.' </td>
            <td> '.$transaction->product_name.' </td>
            <td> '.$transaction->qty.' </td>
            <td class="nowrap"> ₱ '.number_format($transaction->unit_price).' </td>
            <td class="nowrap"> ₱ '.number_format($transaction->total_price).' </td>
            <td class="nowrap"> ₱ '.number_format($transaction->total_earned).' </td>
            <td> '.$transaction->created_at->format('M. d, Y').' </td>

            <td class="actions">
                <div class="actions-container">
                        <form action="'. route('staff.transactionEdit', $transaction->id) .'" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('GET') . '
                        <button type="submit" class="edit editButton" id="edit" data-id="'.$transaction->id.'">
                            <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                        </button>
                    </form>
                    <form action="'. route('staff.productDestroy', $transaction->id) .'" method="POST">
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
    
    public function customerSearch(Request $request)
    {
        $rowNumber = 1;
        $output="";

        $transactions = Transaction::where('customer_name', 'Like', '%' . $request->search . '%')
        ->orWhere('product_name', 'LIKE', '%' . $request->search . '%')
        ->get();

        foreach ($transactions as $transaction) 
        {
            $output.=

            '<tr>

            <td>' . $rowNumber++ . '</td>
            <td> '.$transaction->customer_name.' </td>
            <td> '.$transaction->product_name.' </td>
            <td> '.$transaction->qty.' </td>
            <td> ₱ '.$transaction->unit_price.' </td>
            <td> ₱ '.number_format($transaction->total_price).' </td>
            <td> ₱ '.number_format($transaction->total_earned).' </td>
            <td> '.$transaction->created_at->format('M. d, Y').' </td>

            <td class="actions">
                <div class="actions-container">
                        <form action="'. route('staff.transactionEdit', $transaction->id) .'" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('GET') . '
                        <button type="submit" class="edit editButton" id="edit" data-id="'.$transaction->id.'">
                            <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                        </button>
                    </form>
                    <form action="'. route('staff.productDestroy', $transaction->id) .'" method="POST">
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

