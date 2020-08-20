<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Country;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChannelController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
        $this->middleware('verify.email');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::where('email', auth()->user()->email)->first();
        $countries = Country::all();
        return view('channel.create', ['user' => $user, 'countries' => $countries]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'avatar'                    =>      'nullable|max:2048|mimes:png,jpeg,jpg,gif,webp',
            'name'                      =>      'required|min:3',
            'phone_number'              =>      'required|min:6',
            'address'                   =>      'required|min:6',
            'city'                      =>      'required|min:2',
            'state'                     =>      'required|min:2',
            'postal_code'               =>      'required|numeric',
            'country'                   =>      'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        } else {
            $channel = Auth::user()->channel()->first();
            if ($channel) {
                return back()->with('error', 'You are allowed to create only one channel for <b>' . $channel->user->email . '</b>.');
            } else {
                $filename = 'user.webp';
                if ($request->hasFile('avatar')) {
                    $avatar = $request->file('avatar');
                    $filename = 'MyTube_' . date('Y-m-d_H_i_s') . '.' . $avatar->getClientOriginalExtension();
                    $avatar->move(public_path('images/avatars'), $filename);
                }
                $created = Auth::user()->channel()->create([
                    'avatar'                        =>      $filename,
                    'name'                          =>      $request->get('name'),
                    'phone_number'                  =>      $request->get('phone_number'),
                    'address'                       =>      $request->get('address'),
                    'city'                          =>      $request->get('city'),
                    'state'                         =>      $request->get('state'),
                    'postal_code'                   =>      $request->get('postal_code'),
                    'country_id'                    =>      $request->get('country')
                ]);
                if ($created) {
                    return redirect()->route('home.index')->with('success', 'Channel created successfully.');
                } else {
                    return back()->with('error', 'An error occured while creating channel.');
                }
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($id) {
            $d_id = (base64_decode($id) * 67890) / 1234554321;
            $channel = Channel::find($d_id);
            return view('channel.show', ['channel' => $channel]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function edit(Channel $channel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Channel $channel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id) {
            $d_id = (base64_decode($id) * 67890) / 1234554321;
            $channel = Channel::find($d_id);
            if ($channel) {
                if (file_exists(public_path('images/avatars/' . $channel->avatar))) {
                    unlink(public_path('/images/avatars/' . $channel->avatar));
                }
                $channel->delete();
                return redirect()->route('home.index')->with('success', 'Channel deleted successfully.');
            } else {
                return redirect()->route('home.index')->with('error', 'An error occured while deleting channel.');
            }
        }
    }

    // About page
    public function about($id)
    {
        if ($id) {
            $d_id = (base64_decode($id) * 67890) / 1234554321;
            $channel = Channel::find($d_id);
            return view('channel.about', ['channel' => $channel]);
        }
    }

    // Settings page
    public function settings($id)
    {
        if ($id) {
            $d_id = (base64_decode($id) * 67890) / 1234554321;
            $channel = Channel::find($d_id);
            if ($channel->user_id == Auth::user()->id) {
                return view('channel.settings', ['channel' => $channel]);
            } else {
                return redirect()->route('home.index');
            }
        }
    }
}
