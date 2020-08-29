<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
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
            $comment = $request->get('comment');
            $video_id = $request->get('video_id');

            $created = Auth::user()->comments()->create([
                'body'                  =>      $comment,
                'video_id'              =>      $video_id
            ]);

            if ($created) {
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
                                <span class="mr-3">' . $reply->created_at->diffForHumans() . '</span>
                                    <span class="like-reply-' . $reply->id . ' ' . ($reply->likes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-success' : 'text-dark') . ' like-reply mr-3" data-id="'. $reply->id .'"><i class="fas fa-thumbs-up"></i> <span class="reply-likes-count-' . $reply->id . '">' . ($reply->likes()->count() ?? 0) . '</span></span>
                                    <span class="dislike-reply-' . $reply->id . ' ' . ($reply->dislikes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-danger' : 'text-dark') . ' dislike-reply mr-3" data-id="'. $reply->id .'"><i class="fas fa-thumbs-down"></i>  <span class="reply-dislikes-count-' . $reply->id . '">' . ($reply->dislikes()->count() ?? 0) . '</span></span>
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
                        <span class="mr-3">' . $comment->created_at->diffForHumans() . '</span>
                        <span class="like-comment-' . $comment->id . ' ' . ($comment->likes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-success' : 'text-dark') . ' like-comment mr-3" data-id="'. $comment->id .'"><i class="fas fa-thumbs-up"></i> <span class="comment-likes-count-' . $comment->id . '">' . ($comment->likes()->count() ?? 0) . '</span></span>
                        <span class="dislike-comment-' . $comment->id . ' ' . ($comment->dislikes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-danger' : 'text-dark') . ' dislike-comment mr-3" data-id="'. $comment->id .'"><i class="fas fa-thumbs-down"></i>  <span class="comment-dislikes-count-' . $comment->id . '">' . ($comment->dislikes()->count() ?? 0) . '</span></span>
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
                return response()->json(['output' => $data, 'comments' => ($video->comments()->count() . ' Comments')]);
            } else {
                return response()->json(['output' => 'Something went wrong.', 'comments' => ($video->comments()->count() . ' Comments')]);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if ($request->ajax()) {
            $comment_id = $request->get('comment_id');

            $deleted = Comment::destroy($comment_id);

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
                                <span class="mr-3">' . $reply->created_at->diffForHumans() . '</span>
                                <span class="like-reply-' . $reply->id . ' ' . ($reply->likes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-success' : 'text-dark') . ' like-reply mr-3" data-id="'. $reply->id .'"><i class="fas fa-thumbs-up"></i> <span class="reply-likes-count-' . $reply->id . '">' . ($reply->likes()->count() ?? 0) . '</span></span>
                                <span class="dislike-reply-' . $reply->id . ' ' . ($reply->dislikes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-danger' : 'text-dark') . ' dislike-reply mr-3" data-id="'. $reply->id .'"><i class="fas fa-thumbs-down"></i>  <span class="reply-dislikes-count-' . $reply->id . '">' . ($reply->dislikes()->count() ?? 0) . '</span></span>
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
                        <span class="mr-3">' . $comment->created_at->diffForHumans() . '</span>
                        <span class="like-comment-' . $comment->id . ' ' . ($comment->likes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-success' : 'text-dark') . ' like-comment mr-3" data-id="'. $comment->id .'"><i class="fas fa-thumbs-up"></i> <span class="comment-likes-count-' . $comment->id . '">' . ($comment->likes()->count() ?? 0) . '</span></span>
                        <span class="dislike-comment-' . $comment->id . ' ' . ($comment->dislikes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-danger' : 'text-dark') . ' dislike-comment mr-3" data-id="'. $comment->id .'"><i class="fas fa-thumbs-down"></i>  <span class="comment-dislikes-count-' . $comment->id . '">' . ($comment->dislikes()->count() ?? 0) . '</span></span>
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

                return response()->json(['output' => $data, 'comments' => ($video->comments()->count() . ' Comments')]);
            } else {
                return response()->json(['output' => 'Something went wrong.', 'comments' => ($video->comments()->count() . ' Comments')]);
            }
        }
    }
}
