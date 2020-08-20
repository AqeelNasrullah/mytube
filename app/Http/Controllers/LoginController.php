<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function attemptLogin(Request $request)
    {
        $rules = [
            'email'                     =>      'required|email',
            'password'                  =>      'required|min:6|max:16'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        } else {
            $credentials = [
                'email'             =>      $request->get('email'),
                'password'          =>      $request->get('password')
            ];
            if (Auth::attempt($credentials, $request->get('remember'))) {
                return redirect()->route('home.index');
            } else {
                return back()->with('error', 'Email or Password is incorrect');
            }
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home.index');
    }
}
