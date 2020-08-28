<?php

namespace App\Http\Controllers;

use App\Category;
use App\User;
use App\Video;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VideoController extends Controller
{
    public function __construct() {
        $this->middleware('auth', ['except' => 'show']);
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
        $categories = Category::all();
        return view('video.create', ['categories' => $categories]);
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
            'uploaded_video'                =>      'required',
            'uploaded_thumbnail'            =>      'required',
            'title'                         =>      'required|min:5',
            'category'                      =>      'required',
            'visibility'                    =>      'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->with(['video' => $request->get('uploaded_video'), 'thumbnail' => $request->get('uploaded_thumbnail')])->withInput();
        } else {
            $created = Auth::user()->videos()->create([
                'video'                     =>      $request->get('uploaded_video'),
                'thumbnail'                 =>      $request->get('uploaded_thumbnail'),
                'title'                     =>      $request->get('title'),
                'slug'                      =>      str_slug($request->get('title')),
                'description'               =>      $request->get('description'),
                'category_id'               =>      $request->get('category'),
                'status'                    =>      $request->get('visibility')
            ]);
            if ($created) {
                return redirect()->route('channel.show', base64_encode((auth()->user()->channel->id * 1234554321) / 67890))->with('success', 'Video uploaded successfully');
            } else {
                return back()->with(['error' => 'An error occured while uploading video', 'video' => $request->get('uploaded_video'), 'thumbnail' => $request->get('uploaded_thumbnail')])->withInput();
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function show(Video $video)
    {
        if (!empty($video)) {
            if (Auth::check()) {
                Auth::user()->manyVideos()->attach($video);
            } else {
                $user = User::where('email', 'guest@mytube.com')->first();
                $user->manyVideos()->attach($video);
            }
            $comments = $video->comments()->latest()->get();
            $videos = Video::where('status', 'public')->inRandomOrder()->limit(9)->get();
            return view('video.show', ['video' => $video, 'videos' => $videos, 'comments' => $comments]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function edit(Video $video)
    {
        $categories = Category::all();
        return view('video.edit', ['video' => $video, 'categories' => $categories]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        $rules = [
            'uploaded_video'                =>      'required',
            'uploaded_thumbnail'            =>      'required',
            'title'                         =>      'required|min:5',
            'category'                      =>      'required',
            'visibility'                    =>      'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->with('thumbnail', $request->get('uploaded_thumbnail'));
        } else {
            if ($video->thumbnail != $request->get('uploaded_thumbnail')) {
                unlink(public_path('uploads/thumbnails/' . $video->thumbnail));
            }

            $updated = $video->update([
                'video'                     =>      $request->get('uploaded_video'),
                'thumbnail'                 =>      $request->get('uploaded_thumbnail'),
                'title'                     =>      $request->get('title'),
                'slug'                      =>      str_slug($request->get('title')),
                'description'               =>      $request->get('description'),
                'category_id'               =>      $request->get('category'),
                'status'                    =>      $request->get('visibility')
            ]);

            if ($updated) {
                return redirect()->route('channel.show', base64_encode((auth()->user()->channel->id * 1234554321) / 67890))->with('success', 'Video uploaded successfully');
            } else {
                return back()->with(['error' => 'An error occured while uploading video', 'thumbnail' => $request->get('uploaded_thumbnail')]);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Video  $video
     * @return \Illuminate\Http\Response
     */
    public function destroy(Video $video)
    {
        if (file_exists(public_path('uploads/thumbnails/' . $video->thumbnail)) && file_exists(public_path('uploads/videos/' . $video->video))) {
            unlink(public_path('uploads/thumbnails/' . $video->thumbnail));
            unlink(public_path('uploads/videos/' . $video->video));
        }

        $deleted = $video->delete();
        if ($deleted) {
            return back()->with('success', 'Video deleted successfully');
        } else {
            return back()->with('error', 'An error occured while deleting video.');
        }
    }

    // Upload Video
    public function uploadVideo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'video'                         =>      'required|mimes:mp4,mkv,ogg,mov|max:1073741824',
            'thumbnail'                     =>      'required|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        } else {
            $video = $request->file('video');
            $thumbnail = $request->file('thumbnail');
            $video_name = 'MyTube_video_' . date('Y-m-d_H:i:s') . '.' . $video->getClientOriginalExtension();
            $thumbnail_name = 'MyTube_thumbnail_' . date('Y-m-d_H:i:s') . '.' . $thumbnail->getClientOriginalExtension();
            $uploaded = $video->move(public_path('uploads/videos'), $video_name);
            $thumbnail_uploaded = $thumbnail->move(public_path('uploads/thumbnails'), $thumbnail_name);
            if ($uploaded && $thumbnail_uploaded) {
                return back()->with(['video' => $video_name, 'thumbnail' => $thumbnail_name, 'success' => 'Video & thumbnail uploaded Successfully.']);
            } else {
                return back()->with('error', 'An error occued while uploading video & thumbnail.');
            }
        }
    }

    public function updateVideo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'thumbnail'                     =>      'required|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        } else {
            $thumbnail = $request->file('thumbnail');
            $thumbnail_name = 'MyTube_thumbnail_' . date('Y-m-d_H:i:s') . '.' . $thumbnail->getClientOriginalExtension();
            $thumbnail_uploaded = $thumbnail->move(public_path('uploads/thumbnails'), $thumbnail_name);
            if ($thumbnail_uploaded) {
                return back()->with(['thumbnail' => $thumbnail_name, 'success' => 'Video & thumbnail uploaded Successfully.']);
            } else {
                return back()->with('error', 'An error occued while uploading video & thumbnail.');
            }
        }
    }

    public static function getVideoDuration($video_path)
    {
        $ffprobe = FFProbe::create();
        $duration = $ffprobe->format($video_path)->get('duration');

    }
}
