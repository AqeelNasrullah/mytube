<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Like;
use App\Reply;
use App\User;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
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
        $video_id = $request->get('video_id');
        $user_id = 0;
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = User::where('email', 'guest@mytube.com')->first()->id;
        }
        $video = Video::find($video_id);
        if ($like = $video->likes()->where('user_id', $user_id)->first()) {
            $destroyed = Like::destroy($like->id);
            if ($destroyed) {
                $toast = '<div class="toast bg-success" id="like-toast">
                    <div class="toast-body text-light">You unliked this video.</div>
                </div>';
                $count = $video->likes()->count();
                return response()->json(['status' => 'unliked', 'toast' => $toast, 'count' => $count]);
            } else {
                $toast = '<div class="toast bg-danger" id="like-toast">
                    <div class="toast-body text-light">Something went wrong</div>
                </div>';
                $count = $video->likes()->count();
                return response()->json(['status' => 'unliked', 'toast' => $toast, 'count' => $count]);
            }
        } else {
            $created = $video->likes()->create([
                'user_id'                   =>      $user_id
            ]);
            if ($created) {
                $toast = '<div class="toast bg-success" id="like-toast">
                    <div class="toast-body text-light">You liked this video.</div>
                </div>';
                $count = $video->likes()->count();
                return response()->json(['status' => 'liked', 'toast' => $toast, 'count' => $count]);
            } else {
                $toast = '<div class="toast bg-danger" id="like-toast">
                    <div class="toast-body text-light">Something went wrong</div>
                </div>';
                $count = $video->likes()->count();
                return response()->json(['status' => 'liked', 'toast' => $toast, 'count' => $count]);
            }
        }

    }

    // Like COmments
    public function likeComment(Request $request)
    {
        $comment_id = $request->get('comment_id');
        $user_id = 0;
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = User::where('email', 'guest@mytube.com')->first()->id;
        }
        $comment = Comment::find($comment_id);
        if ($like = $comment->likes()->where('user_id', $user_id)->first()) {
            $destroyed = Like::destroy($like->id);
            if ($destroyed) {
                $toast = '<div class="toast bg-success" id="comment-like-toast">
                    <div class="toast-body text-light">You unliked this comment.</div>
                </div>';
                $count = $comment->likes()->count();
                return response()->json(['status' => 'unliked', 'toast' => $toast, 'count' => $count]);
            } else {
                $toast = '<div class="toast bg-danger" id="comment-like-toast">
                    <div class="toast-body text-light">Something went wrong</div>
                </div>';
                $count = $comment->likes()->count();
                return response()->json(['status' => 'unliked', 'toast' => $toast, 'count' => $count]);
            }
        } else {
            $created = $comment->likes()->create([
                'user_id'                   =>      $user_id
            ]);
            if ($created) {
                $toast = '<div class="toast bg-success" id="comment-like-toast">
                    <div class="toast-body text-light">You liked this comment.</div>
                </div>';
                $count = $comment->likes()->count();
                return response()->json(['status' => 'liked', 'toast' => $toast, 'count' => $count]);
            } else {
                $toast = '<div class="toast bg-danger" id="comment-like-toast">
                    <div class="toast-body text-light">Something went wrong</div>
                </div>';
                $count = $comment->likes()->count();
                return response()->json(['status' => 'liked', 'toast' => $toast, 'count' => $count]);
            }
        }

    }

    // Like Reply
    public function likeReply(Request $request)
    {
        $reply_id = $request->get('reply_id');
        $user_id = 0;
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = User::where('email', 'guest@mytube.com')->first()->id;
        }
        $reply = Reply::find($reply_id);
        if ($like = $reply->likes()->where('user_id', $user_id)->first()) {
            $destroyed = Like::destroy($like->id);
            if ($destroyed) {
                $toast = '<div class="toast bg-success" id="reply-like-toast">
                    <div class="toast-body text-light">You unliked reply.</div>
                </div>';
                $count = $reply->likes()->count();
                return response()->json(['status' => 'unliked', 'toast' => $toast, 'count' => $count]);
            } else {
                $toast = '<div class="toast bg-danger" id="reply-like-toast">
                    <div class="toast-body text-light">Something went wrong</div>
                </div>';
                $count = $reply->likes()->count();
                return response()->json(['status' => 'unliked', 'toast' => $toast, 'count' => $count]);
            }
        } else {
            $created = $reply->likes()->create([
                'user_id'                   =>      $user_id
            ]);
            if ($created) {
                $toast = '<div class="toast bg-success" id="reply-like-toast">
                    <div class="toast-body text-light">You liked reply.</div>
                </div>';
                $count = $reply->likes()->count();
                return response()->json(['status' => 'liked', 'toast' => $toast, 'count' => $count]);
            } else {
                $toast = '<div class="toast bg-danger" id="reply-like-toast">
                    <div class="toast-body text-light">Something went wrong</div>
                </div>';
                $count = $reply->likes()->count();
                return response()->json(['status' => 'liked', 'toast' => $toast, 'count' => $count]);
            }
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function show(Like $like)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function edit(Like $like)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Like $like)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Like  $like
     * @return \Illuminate\Http\Response
     */
    public function destroy(Like $like)
    {
        //
    }
}
