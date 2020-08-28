<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Reply;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
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
        if ($request->ajax()) {
            $comment_id = $request->get('comment_id');
            $reply = $request->get('reply');

            $created = Auth::user()->replies()->create([
                'body'                  =>      $reply,
                'comment_id'            =>      $comment_id,
            ]);

            if ($created) {
                $comment = Comment::find($request->get('comment_id'));
                $replies = Reply::where('comment_id', $request->get('comment_id'))->latest()->get();

                $video = $comment->video()->first();
                $comments = Comment::where('video_id', $video->id)->latest()->get();

                $data = '';
                foreach ($comments as $comment) {
                    $replies = '';
                    $comment_delete_btn = '';

                    if ($comment->replies()->count() > 0) {
                        $replies_obj = $comment->replies()->latest()->get();
                        foreach ($replies_obj as $reply) {
                            $reply_delete_btn = '';
                            if (Auth::user()->id == $reply->user_id) {
                                $reply_delete_btn .= '<span class="delete delete-reply text-uppercase fw" data-id="' . $reply->id . '"><i class="fas fa-trash-alt"></i> Delete</span>';
                            }

                            $replies .= '<div class="row mb-3">
                            <div class="offset-1 col-1">
                                <div style="width: 50px;height: 50px;overflow: hidden;border-radius: 100%;"><img src="' . asset('images/avatars/' . $reply->user->channel->avatar) . '" width="100%" alt="Avatar not found"></div>
                            </div>
                            <div class="col-10">
                                <h5 class="fw mb-1">' . $reply->user->channel->name . ' ' . (($reply->user_id == $video->user_id) ? '<small>(Owner)</small>' : '') . '</h5>
                                <p class="mb-1">' . $reply->body . '</p>
                                <p class="mb-0 d-inline">
                                    <span class="mr-3">' . $reply->created_at->diffForHumans() . '</span>
                                    <span class="like mr-3" id="like_' . $reply->id . '"><i class="fas fa-thumbs-up"></i> 0</span>
                                    <span class="dislike mr-3" id="dislike_' . $reply->id . '"><i class="fas fa-thumbs-down"></i> 0</span>
                                    <span class="reply mr-3 fw text-uppercase" data-id="' . $comment->id . '"><i class="fas fa-reply"></i> Reply</span>
                                    ' . $reply_delete_btn . '
                                </p>
                            </div>
                        </div>';
                        }
                    } else {
                        $replies .= '';
                    }

                    if (Auth::user()->id == $comment->user_id) {
                        $comment_delete_btn .= '<span class="delete delete-comment text-uppercase fw" data-id="' . $comment->id . '"><i class="fas fa-trash-alt"></i> Delete</span>';
                    }

                    $data .= '<div class="row mb-3">
                    <div class="col-1">
                        <div style="width: 60px;height: 60px;overflow: hidden;border-radius: 100%;"><img src="' . asset('images/avatars/' . $comment->user->channel->avatar) . '" width="100%" alt="Avatar not found"></div>
                    </div>
                    <div class="col-11">
                        <h5 class="fw mb-1">' . $comment->user->channel->name . ' ' . (($comment->user_id == $video->user_id) ? '<small>(Owner)</small>' : '') . '</h5>
                        <p class="mb-1">' . $comment->body . '</p>
                        <p class="mb-0 d-inline">
                            <span class="mr-3">' . $comment->created_at->diffForHumans() . '</span>
                            <span class="like mr-3" id="like_' . $comment->id . '"><i class="fas fa-thumbs-up"></i> 0</span>
                            <span class="dislike mr-3" id="dislike_' . $comment->id . '"><i class="fas fa-thumbs-down"></i> 0</span>
                            <span class="reply mr-3 fw text-uppercase" data-id="' . $comment->id . '"><i class="fas fa-reply"></i> Reply</span>
                            ' . $comment_delete_btn . '
                        </p>
                    </div>
                </div>
                <div class="replies-section" id="replies-section">
                    <form action="" id="reply-form-' . $comment->id . '" data-id="' . $comment->id . '" class="mb-3 reply-form d-none" method="post">
                        ' . csrf_field() . '
                        <div class="row align-items-center" style="min-height: 50px;">
                            <div class="offset-1 col-1">
                                <div style="width: 50px;height: 50px;overflow: hidden;border-radius: 100%">
                                    <img src="' . asset('images/avatars/' . auth()->user()->channel->avatar) . '" width="100%" alt="Avatar not found">
                                </div>
                            </div>
                            <div class="col-9">
                                <textarea name="reply" id="reply-' . $comment->id . '" rows="2" class="form-control" placeholder="Add Reply here..."></textarea>
                                <p class="mb-0 invalid-feedback" id="reply-error-' . $comment->id . '"></p>
                            </div>
                            <div class="col-1">
                                <button type="submit" class="btn btn-block btn-lg btn-primary" id="add-reply"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </form>
                    ' . $replies . '
                </div>';
                }
                return response()->json(['output' => $data, 'comments' => ($video->comments()->count() . ' Comments')]);
            } else {
                return response()->json(['output' => 'Something went wrong.', 'comments' => ($video->comments()->count() . ' Comments')]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function show(Reply $reply)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reply $reply)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reply  $reply
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $reply_id = $request->get('reply_id');

            $deleted = Reply::destroy($reply_id);

            if ($deleted) {
                $video = Video::find($request->get('video_id'));
                $comments = Comment::where('video_id', $request->get('video_id'))->latest()->get();
                $data = '';
                foreach ($comments as $comment) {
                    $replies = ''; $comment_delete_btn = '';

                    if ($comment->replies()->count() > 0) {
                        $replies_obj = $comment->replies()->latest()->get();
                        foreach ($replies_obj as $reply) {
                            $reply_delete_btn = '';
                            if (Auth::user()->id == $reply->user_id) {
                                $reply_delete_btn .= '<span class="delete delete-reply text-uppercase fw" data-id="'. $reply->id .'"><i class="fas fa-trash-alt"></i> Delete</span>';
                            }

                            $replies .= '<div class="row mb-3">
                            <div class="offset-1 col-1">
                                <div style="width: 50px;height: 50px;overflow: hidden;border-radius: 100%;"><img src="'. asset('images/avatars/' . $reply->user->channel->avatar) .'" width="100%" alt="Avatar not found"></div>
                            </div>
                            <div class="col-10">
                                <h5 class="fw mb-1">'. $reply->user->channel->name .' '. (($reply->user_id == $video->user_id) ? '<small>(Owner)</small>' : '') .'</h5>
                                <p class="mb-1">'. $reply->body .'</p>
                                <p class="mb-0 d-inline">
                                    <span class="like mr-3" id="like_'. $comment->id .'"><i class="fas fa-thumbs-up"></i> 0</span>
                                    <span class="dislike mr-3" id="dislike_'. $comment->id .'"><i class="fas fa-thumbs-down"></i> 0</span>
                                    <span class="reply mr-3 fw text-uppercase" data-id="'. $comment->id .'"><i class="fas fa-reply"></i> Reply</span>
                                    ' . $reply_delete_btn .'
                                </p>
                            </div>
                        </div>';
                        }
                    } else {
                        $replies .= '';
                    }

                    if (Auth::user()->id == $comment->user_id) {
                        $comment_delete_btn .= '<span class="delete delete-comment text-uppercase fw" data-id="'. $comment->id .'"><i class="fas fa-trash-alt"></i> Delete</span>';
                    }

                    $data .= '<div class="row mb-3">
                    <div class="col-1">
                        <div style="width: 60px;height: 60px;overflow: hidden;border-radius: 100%;"><img src="'. asset('images/avatars/' . $comment->user->channel->avatar) .'" width="100%" alt="Avatar not found"></div>
                    </div>
                    <div class="col-11">
                        <h5 class="fw mb-1">'. $comment->user->channel->name .' '. (($comment->user_id == $video->user_id) ? '<small>(Owner)</small>' : '') .'</h5>
                        <p class="mb-1">'. $comment->body .'</p>
                        <p class="mb-0 d-inline">
                            <span class="like mr-3" id="like_'. $comment->id .'"><i class="fas fa-thumbs-up"></i> 0</span>
                            <span class="dislike mr-3" id="dislike_'. $comment->id .'"><i class="fas fa-thumbs-down"></i> 0</span>
                            <span class="reply mr-3 fw text-uppercase" data-id="'. $comment->id .'"><i class="fas fa-reply"></i> Reply</span>
                            ' . $comment_delete_btn . '
                        </p>
                    </div>
                </div>
                <div class="replies-section" id="replies-section">
                    <form action="" id="reply-form-'. $comment->id .'" data-id="'. $comment->id .'" class="mb-3 reply-form d-none" method="post">
                        '. csrf_field() .'
                        <div class="row align-items-center" style="min-height: 50px;">
                            <div class="offset-1 col-1">
                                <div style="width: 50px;height: 50px;overflow: hidden;border-radius: 100%">
                                    <img src="'. asset('images/avatars/' . auth()->user()->channel->avatar) .'" width="100%" alt="Avatar not found">
                                </div>
                            </div>
                            <div class="col-9">
                                <textarea name="reply" id="reply-'. $comment->id .'" rows="2" class="form-control" placeholder="Add Reply here..."></textarea>
                                <p class="mb-0 invalid-feedback" id="reply-error-'. $comment->id .'"></p>
                            </div>
                            <div class="col-1">
                                <button type="submit" class="btn btn-block btn-lg btn-primary" id="add-reply"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </form>
                    ' . $replies . '
                </div>';
                }

                $toast = '<div class="toast fade bg-success" id="comment-toast">
                    <div class="toast-body text-light">Comment deleted successfully.</div>
                </div>';

                return response()->json(['output' => $data, 'comments' => ($video->comments()->count() . ' Comments'), 'toast' => $toast]);
            } else {
                return response()->json(['output' => 'Something went wrong.', 'comments' => ($video->comments()->count() . ' Comments')]);
            }
        }
    }
}
