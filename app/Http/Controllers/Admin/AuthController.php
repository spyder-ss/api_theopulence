<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    function login(Request $request)
    {
        $ADMIN_ROUTE_NAME = getAdminRouteName();

        if (auth()->check()) {
            return Redirect(url('admin/dashboard'));
        }

        if ($request->method() == 'POST' || $request->method() == 'post') {
            request()->validate([
                'email' => 'required',
                'password' => 'required',
            ]);

            $email = isset($request->email) ? $request->email : '';
            $password = isset($request->password) ? $request->password : '';

            $UserTableData = User::where('email', $email)->first();
            if (!empty($UserTableData)) {
                $credentials = $request->only('email', 'password');
                if (Auth::attempt(['email' => strtolower($email), 'password' => $password], $request->has('remember'))) {
                    if (Auth::user()->status == 0) {
                        Auth::logout();
                        Session::flush();
                        return redirect()->back()->withErrors(['email' => ['Your account Is Deactivate yet !']]);
                    }
                    return redirect(url($ADMIN_ROUTE_NAME . '/dashboard'));
                }
                return Redirect(route('login'))->withErrors(['email' => ['You have entered an Invalid Email or Username and Password!']]);
            } else {
                return Redirect(route('login'))->withErrors(['email' => ['Invalid Email Address..!!']]);
            }
        }

        return view('admin.auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
