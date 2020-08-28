<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Country;
use App\User;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ChannelController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except' => ['show', 'about']]);
        $this->middleware('verify.email', ['except' => ['show', 'about']]);
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
            $videos = $channel->user->videos()->latest()->paginate(10);
            return view('channel.show', ['channel' => $channel, 'videos' => $videos]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($id) {
            $d_id = (base64_decode($id) * 67890) / 1234554321;
            $channel = Channel::find($d_id);
            $countries = Country::all();
            return view('channel.edit', ['channel' => $channel, 'countries' => $countries]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Channel  $channel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($id) {
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
                return back()->withErrors($validator);
            } else {
                $d_id = (base64_decode($id) * 67890) / 1234554321;
                $channel = Channel::find($d_id);
                $filename = $channel->avatar;
                if ($request->hasFile('avatar')) {
                    if ($channel->avatar !== 'user.webp') {
                        unlink(public_path('images/avatars/' . $channel->avatar));
                    }
                    $avatar = $request->file('avatar');
                    $filename = 'MyTube_' . date('Y-m-d_H_i_s') . '.' . $avatar->getClientOriginalExtension();
                    $avatar->move(public_path('images/avatars'), $filename);
                }
                $updated = $channel->update([
                    'avatar'                =>      $filename,
                    'name'                  =>      $request->get('name'),
                    'phone_number'          =>      $request->get('phone_number'),
                    'address'               =>      $request->get('address'),
                    'city'                  =>      $request->get('city'),
                    'state'                 =>      $request->get('state'),
                    'postal_code'           =>      $request->get('postal_code'),
                    'country'               =>      $request->get('country')
                ]);
                if ($updated) {
                    return redirect()->route('channel.about', base64_encode(($channel->id * 1234554321) / 67890))->with('success', 'Channel info updated successfully.');
                } else {
                    return back()->with('error', 'An error occured while updating channel info.');
                }
            }
        }
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

            $videos = $channel->user->videos()->get();
            $views = 0;

            foreach ($videos as $video) {
                $vid_views = $video->manyUsers()->count();
                $views += $vid_views;
            }

            return view('channel.about', ['channel' => $channel, 'views' => $views]);
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

    // Channel Subscribe
    public function subscribe(Request $request)
    {
        if ($request->ajax()) {
            $channel_id = $request->get('channel_id');
            $channel = Channel::find($channel_id);
            if (Auth::user()->manyChannels()->where('channel_id', $channel_id)->first()) {
                Auth::user()->manyChannels()->detach($channel_id);
                $toast = '<div class="toast bg-success" id="comment-toast">
                    <div class="toast-body text-light">Channel unsubscribed successfully.</div>
                </div>';
                $subscribers = $channel->manyUsers()->count();
                return response()->json(['toast' => $toast, 'status' => 'unsubscribed', 'count' => $subscribers]);
            } else {
                Auth::user()->manyChannels()->attach($channel_id);
                $toast = '<div class="toast bg-success" id="comment-toast">
                    <div class="toast-body text-light">Channel subscribed successfully.</div>
                </div>';
                $subscribers = $channel->manyUsers()->count();
                return response()->json(['toast' => $toast, 'status' => 'subscribed', 'count' => $subscribers]);
            }
        }
    }
}
