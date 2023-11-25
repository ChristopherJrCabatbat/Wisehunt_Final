<?php

namespace App\Http\Controllers;

use App\Models\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{

    public function login()
    {
        Session::forget('user');
        return view('login');
    }

    // public function loginStore(Request $request)
    // {
    //     // Session::forget('user');

    //     $username = $request->input('username');
    //     $password = $request->input('password');
    //     $user = Login::where("username", $username)->first();

    //     if ($user) {
    //         $db_password = $user->password;
    //         // $decrypted = Crypt::decryptString($db_password);

    //         // if ($password === $decrypted) {
    //         if ($password === $db_password) {
    //             Session::put('user', $user);

    //             // Set a session variable to indicate a successful login
    //             Session::put('login_success', true);
    //             return redirect()->route('admin.dashboard');
    //             // return redirect("/Client")->with("message", "Login successful!");
    //         } else {
    //             // return view('login');
    //             return redirect()->route('login');
    //             // return redirect('LoginForm')->with("message", "Wrong password! Please try again.");
    //         }
    //     } else {
    //         // return view('login');
    //         return redirect()->route('login');
    //         // return redirect('LoginForm')->with("message", " Account not found! Please try again.");  
    //     }
    // }

    public function loginStore(Request $request)
    {
        $credentials = $request->only('username', 'password');
    
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            // Authentication passed
            return redirect()->route('admin.dashboard');
        }
    
        // Authentication failed
        return redirect()->route('login');
    }
    

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
        // return redirect('/LoginForm');
    }

    // public function loginStore(Request $request)
    // {
    //     $username = $request->input('username');
    //     $password = $request->input('password');

    //     return redirect()->route('admin.dashboard'); // or any other redirect or response
    // }

    // public function index(Request $request)
    // {
    //     $fullName = $request->input('fullName');
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

    // public function loginForm()
    // {
    //     Session::forget('user');
    //     return view('login');
    // }

}
