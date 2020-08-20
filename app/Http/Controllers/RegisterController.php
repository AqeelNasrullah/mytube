<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmailMail;
use App\User;
use App\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $rules = [
            'email'                 =>      'required|email',
            'password'              =>      'required|min:6|max:16|required_with:retype_password|same:retype_password',
            'retype_password'       =>      'required|min:6|max:16'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $user = User::where('email', $request->get('email'))->first();
            if ($user) {
                return back()->with('error', 'Email already registered.');
            } else {
                $data = [
                    'email'                         =>      $request->get('email'),
                    'password'                      =>      Hash::make($request->get('password')),
                    'role_id'                       =>      3,
                    'status'                        =>      'unverified'
                ];
                $created = User::create($data);
                if ($created) {
                    Auth::loginUsingId($created->id);
                    $verify = Auth::user()->verifyEmails()->create([
                        'email'                     =>      $created->email,
                        'key'                       =>      Str::random(50),
                        'expire_at'                 =>      date('Y-m-d H:i:s', strtotime('+1 hour'))
                    ]);

                    if ($verify) {
                        Mail::to($created->email)->send(new VerifyEmailMail([
                            'email'                         =>      $created->email,
                            'user'                          =>      $created->id,
                            'key'                           =>      $verify->key
                        ]));
                        return back()->with('success', 'An email sent to <strong>' . $created->email . '</strong>. Click on link to verify your email.<br><strong>Note:</strong> Link is only valid for one hour.');
                    } else {
                        return back()->with('error', 'An error occured while sending mail.');
                    }

                } else {
                    return back()->with('error', 'An error occured while registering user.')->withInput();
                }
            }
        }
    }
}
