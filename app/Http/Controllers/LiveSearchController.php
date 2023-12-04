<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;

class LiveSearchController extends Controller
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
                    <td> ' . $product->capital . ' </td>
                    <td> ' . $product->unit_price . ' </td>
                    <td class="actions">
                        <div class="actions-container">
                            <form action="' . route('admin.productEdit', $product->id) . '" method="POST">
                                ' . csrf_field() . '
                                ' . method_field('GET') . '
                                <button type="submit" class="edit editButton" id="edit">
                                <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                            </button>
                            </form>
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
            <td> '.$transaction->unit_price.' </td>
            <td> '.$transaction->total_price.' </td>
            <td> '.$transaction->total_earned.' </td>
            <td> '.$transaction->created_at->format('M. d, Y').' </td>

            <td class="actions">
                <div class="actions-container">
                        <form action="'. route('admin.transactionEdit', $transaction->id) .'" method="POST">
                        ' . csrf_field() . '
                        ' . method_field('GET') . '
                        <button type="submit" class="edit editButton" id="edit" data-id="'.$transaction->id.'">
                            <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                        </button>
                    </form>
                    <form action="'. route('admin.productDestroy', $transaction->id) .'" method="POST">
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

    
    public function categorySearch(Request $request)
    {
        $output = "";

        $categories = Product::where('categ_name', 'LIKE', '%' . $request->search . '%')->get();

        foreach ($categories as $category) {
            $output .= 
                '<tr>
                    <td class="id">' . $category->id . '</td>
                    <td>' . $category->categ_name . '</td>
                    <td>Active</td>
                    <td class="actions">
                        <div class="actions-container">
                            <form action="">
                                <button type="button" class="edit editButton" data-id="' . $category->id . '">
                                    <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                                </button>
                            </form>
                            <form action="' . route('categoryDestroy', $category->id) . '" method="POST">
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

        return response()->json([
            'output' => $output,
        ]);
    }

    public function brandSearch(Request $request) 
    // public function search(Request $request) 
    {
        $output="";

        $brand=Transaction::where('category', 'Like', '%' . $request->search . '%')
        ->orWhere('brand_name', 'LIKE', '%' . $request->search . '%')
        ->get();

        foreach ($brand as $brand) 
        {
            $output.=

            '<tr>

            <td class="id"> '.$brand->id.' </td>
            <td> '.$brand->category.' </td>
            <td> '.$brand->brand_name.' </td>
            <td>Active</td>

            <td class="actions">
                <div class="actions-container">
                    <form action="">
                        <button type="button" class="edit editButton" id="edit" data-id="'.$brand->id.'">
                            <i class="fa-solid fa-pen-to-square" style="color: #ffffff;"></i>
                        </button>
                    </form>
                    <form action="'. route('brandDestroy', $brand->id) .'" method="POST">
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
