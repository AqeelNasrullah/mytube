<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Dislike;
use App\Reply;
use App\User;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DislikeController extends Controller
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
        if ($dislike = $video->dislikes()->where('user_id', $user_id)->first()) {
            $destroyed = Dislike::destroy($dislike->id);
            if ($destroyed) {
                $toast = '<div class="toast bg-success" id="dislike-toast">
                    <div class="toast-body text-light">You removed dislike from this video.</div>
                </div>';
                $count = $video->dislikes()->count();
                return response()->json(['status' => 'undisliked', 'toast' => $toast, 'count' => $count]);
            } else {
                $toast = '<div class="toast bg-danger" id="dislike-toast">
                    <div class="toast-body text-light">Something went wrong</div>
                </div>';
                $count = $video->dislikes()->count();
                return response()->json(['status' => 'undisliked', 'toast' => $toast, 'count' => $count]);
            }
        } else {
            $created = $video->dislikes()->create([
                'user_id'                   =>      $user_id
            ]);
            if ($created) {
                $toast = '<div class="toast bg-success" id="dislike-toast">
                    <div class="toast-body text-light">You disliked this video.</div>
                </div>';
                $count = $video->dislikes()->count();
                return response()->json(['status' => 'disliked', 'toast' => $toast, 'count' => $count]);
            } else {
                $toast = '<div class="toast bg-danger" id="dislike-toast">
                    <div class="toast-body text-light">Something went wrong</div>
                </div>';
                $count = $video->dislikes()->count();
                return response()->json(['status' => 'disliked', 'toast' => $toast, 'count' => $count]);
            }
        }
    }

    // Dislike Comments
    public function dislikeComment(Request $request)
    {
        $comment_id = $request->get('comment_id');
        $user_id = 0;
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = User::where('email', 'guest@mytube.com')->first()->id;
        }
        $comment = Comment::find($comment_id);
        if ($dislike = $comment->dislikes()->where('user_id', $user_id)->first()) {
            $destroyed = Dislike::destroy($dislike->id);
            if ($destroyed) {
                $toast = '<div class="toast bg-success" id="comment-dislike-toast">
                    <div class="toast-body text-light">You removed dislike from comment.</div>
                </div>';
                $count = $comment->dislikes()->count();
                return response()->json(['status' => 'undisliked', 'toast' => $toast, 'count' => $count]);
            } else {
                $toast = '<div class="toast bg-danger" id="comment-dislike-toast">
                    <div class="toast-body text-light">Something went wrong</div>
                </div>';
                $count = $comment->dislikes()->count();
                return response()->json(['status' => 'undisliked', 'toast' => $toast, 'count' => $count]);
            }
        } else {
            $created = $comment->dislikes()->create([
                'user_id'                   =>      $user_id
            ]);
            if ($created) {
                $toast = '<div class="toast bg-success" id="comment-dislike-toast">
                    <div class="toast-body text-light">You disliked comment.</div>
                </div>';
                $count = $comment->dislikes()->count();
                return response()->json(['status' => 'disliked', 'toast' => $toast, 'count' => $count]);
            } else {
                $toast = '<div class="toast bg-danger" id="comment-dislike-toast">
                    <div class="toast-body text-light">Something went wrong</div>
                </div>';
                $count = $comment->dislikes()->count();
                return response()->json(['status' => 'disliked', 'toast' => $toast, 'count' => $count]);
            }
        }
    }

    // Dislike Reply
    public function dislikeReply(Request $request)
    {
        $reply_id = $request->get('reply_id');
        $user_id = 0;
        if (Auth::check()) {
            $user_id = Auth::user()->id;
        } else {
            $user_id = User::where('email', 'guest@mytube.com')->first()->id;
        }
        $reply = Reply::find($reply_id);
        if ($dislike = $reply->dislikes()->where('user_id', $user_id)->first()) {
            $destroyed = Dislike::destroy($dislike->id);
            if ($destroyed) {
                $toast = '<div class="toast bg-success" id="reply-dislike-toast">
                    <div class="toast-body text-light">You removed dislike from reply.</div>
                </div>';
                $count = $reply->dislikes()->count();
                return response()->json(['status' => 'undisliked', 'toast' => $toast, 'count' => $count]);
            } else {
                $toast = '<div class="toast bg-danger" id="reply-dislike-toast">
                    <div class="toast-body text-light">Something went wrong</div>
                </div>';
                $count = $reply->dislikes()->count();
                return response()->json(['status' => 'undisliked', 'toast' => $toast, 'count' => $count]);
            }
        } else {
            $created = $reply->dislikes()->create([
                'user_id'                   =>      $user_id
            ]);
            if ($created) {
                $toast = '<div class="toast bg-success" id="reply-dislike-toast">
                    <div class="toast-body text-light">You disliked reply.</div>
                </div>';
                $count = $reply->dislikes()->count();
                return response()->json(['status' => 'disliked', 'toast' => $toast, 'count' => $count]);
            } else {
                $toast = '<div class="toast bg-danger" id="reply-dislike-toast">
                    <div class="toast-body text-light">Something went wrong</div>
                </div>';
                $count = $reply->dislikes()->count();
                return response()->json(['status' => 'disliked', 'toast' => $toast, 'count' => $count]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Dislike  $dislike
     * @return \Illuminate\Http\Response
     */
    public function show(Dislike $dislike)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Dislike  $dislike
     * @return \Illuminate\Http\Response
     */
    public function edit(Dislike $dislike)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Dislike  $dislike
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dislike $dislike)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Dislike  $dislike
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dislike $dislike)
    {
        //
    }
}
