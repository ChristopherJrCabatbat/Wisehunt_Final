<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginStore(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        return redirect()->route('admin.dashboard'); // or any other redirect or response
    }

    // public function index(Request $request)
    // {
    //       $fullName = $request->input('fullName');
    //     if (isset($fullName)) {

    //         $fullName = $request->input('fullName');

    //         $passwordinput = $request->input('password');

    //         $fulnam = Login::where("username", $fullName)->value("username");

    //         $exists = Login::where('username', $fullName)->exists();

    //         if ($exists) {
    //             $password = Login::where("username", $fulnam)->value("password");
    //             $decrypt = decrypt($password);

    //             if ($passwordinput == $decrypt) {
    //                 $fullN = Login::where("username", $fullName)->value("username");
    //                 $account = Login::where("username", $fullN)->value("account");

    //                 Session::put('name', $fullN);
    //                 Session::put('acc', $account);
    //                 //user level :  admin lang


    //                 if ($account == 'admin') {
    //                     Session::put('account', $account);

    //                     return redirect()->route('admin.dashboard');
    //                 } elseif ($account == 'faculty') {
    //                     Session::put('account', $account);
    //                     return redirect('/faculty')->with('alert', "Welcome  $fullN!")->with('lrt', 1);
    //                 } elseif ($account == 'student') {
    //                     Session::put('account', $account);
    //                     return redirect('/student')->with('alert', "Welcome  $fullN!")->with('lrt', 1);
    //                 } else {
    //                     return "gagi aliwa HAHAHAHAHAHA";
    //                 }
    //             } else {

    //                 return back()->with('messagepass', 'Wrong Password')->withInput();



    //             }
    //         } else {


    //             return back()->with('messageid', 'Id does not exist')->withInput();

    //         }
    //     } else {

    //         return view('loginNew')->with('alert', 'Id does not exist');
    //     }
    // }
}
