<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function dashboard()
    {
        if(Auth::id()) 
        {
            $role = Auth()->user()->role;
        }

        if($role == 'Staff')
        {
            return redirect()->route('staff.dashboard');
        }
        else if($role == 'Admin')
        {
            // return view('admin.admin-home');
            return redirect()->route('admin.dashboard');
        }
        else
        {
            return redirect()->back();
        }
    }
}
