<?php

namespace App\Http\Controllers;

use App\Mail\VerifyEmailMail;
use App\User;
use App\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerifyEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, $key)
    {
        if ($id && $key) {
            Auth::loginUsingId($id);
            $verify = VerifyEmail::where('user_id', $id)->where('key', $key)->first();
            $expired = false;
            if (strtotime($verify->expire_at) < time()) {
                $expired = true;
            } else {
                $user = User::where('email', $verify->email)->update([
                    'email_verified_at'                     =>      date('Y-m-d H:i:s'),
                    'status'                                =>      'verified'
                ]);
                if (!$user) {
                    return redirect()->route('home.index')->with('error', 'An error occured while verifying email.');
                }
            }
            return view('auth.verify', ['expired' => $expired]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VerifyEmail  $verifyEmail
     * @return \Illuminate\Http\Response
     */
    public function show(VerifyEmail $verifyEmail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VerifyEmail  $verifyEmail
     * @return \Illuminate\Http\Response
     */
    public function edit(VerifyEmail $verifyEmail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VerifyEmail  $verifyEmail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VerifyEmail $verifyEmail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VerifyEmail  $verifyEmail
     * @return \Illuminate\Http\Response
     */
    public function destroy(VerifyEmail $verifyEmail)
    {
        //
    }

    // Resend email
    public function resendEmail($id)
    {
        if ($id) {
            $d_id = (base64_decode($id) * 67890) / 1234554321;
            $user = User::where('id', $d_id)->first();
            $verify = VerifyEmail::where('user_id', $d_id)->where('email', $user->email)->first();
            if ($verify) {
                $key = Str::random(50);
                $updated = $verify->update([
                    'key'                   =>      $key,
                    'expire_at'             =>      date('Y-m-d H:i:s', strtotime('+1 hour'))
                ]);
                if ($updated) {
                    Mail::to($user->email)->send(new VerifyEmailMail([
                        'email'                 =>      $user->email,
                        'user'                  =>      $d_id,
                        'key'                   =>      $key
                    ]));
                    return redirect()->route('register.index')->with('success', 'An email sent to <strong>' . $user->email . '</strong>. Click on link to verify your email.<br><strong>Note:</strong> Link is only valid for one hour.');
                } else {
                    return redirect()->route('home.index')->with('error', 'An error occured while sending email.');
                }
            } else {
                return redirect()->route('home.index')->with('error', 'An error occured while sending email.');
            }
        }
    }
}
