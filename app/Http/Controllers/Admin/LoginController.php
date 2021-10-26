<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;

use App\Models\Admin;

class LoginController extends Controller
{
    //

    public function getLogin()
    {
        #Got me to view and view login page
        return view('admin.auth.login');

    }


    public function login(LoginRequest $request)
    {
        $remember_me = $request->has('remember_me') ? true : false;
        // dd(auth()->guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")]));

        if (Auth::guard('admin')->attempt(['email' => $request->input("email"), 'password' => $request->input("password")], $remember_me)) {
           // notify()->success('تم الدخول بنجاح  ');
            return redirect()->route('admin.dashboard');
        }else{
       // notify()->error('خطا في البيانات  برجاء المجاولة مجدا ');
        return redirect()->back()->with(['error' => 'هناك خطا بالبيانات']);}
    }
}
