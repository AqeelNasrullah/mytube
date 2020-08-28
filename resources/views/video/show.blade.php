@extends('master.master')

@section('title')
    <title>{{ $video->title ?? 'Video Title' }} - {{ config('app.name') }}</title>
@endsection

@section('style')
    <style>
        .reply, .delete {cursor: pointer;}
    </style>
@endsection

@section('content')
    <div class="container-fluid py-3">
        <div class="row">
            <main class="col-12 col-md-8">
                <section class="video-section">
                    <div class="mb-3">
                        <video controls muted autoplay width="100%">
                            <source src="{{ asset('uploads/videos/' . $video->video) }}">
                        </video>
                    </div>
                    <h2 class="fw mb-2">{{ $video->title ?? 'This is video title' }}</h2>
                    <div class="row">
                        <div class="col-12 col-sm-5">
                            <h5>{{ $video->manyUsers()->count() ?? 0 }} Views | {{ date('F d, Y', strtotime($video->created_at)) }}</h5>
                        </div>
                        <div class="col-12 col-sm">
                            <h5 class="text-right"><a href="" class="text-dark mr-3"><i class="fas fa-thumbs-up"></i> {{ $likes ?? 0 }}</a><a href="" class="text-dark"><i class="fas fa-thumbs-down"></i> {{ $dislikes ?? 0 }}</a></h5>
                        </div>
                    </div>
                    <hr>
                    <div class="video-desc">
                        <div class="row mb-3 align-items-center" style="min-height: 50px;">
                            <div class="col-9">
                                <a href="{{ route('channel.show', base64_encode(($video->user->channel->id * 1234554321) / 67890)) }}">
                                    <div>
                                        <div class="float-left mr-2" style="width: 45px;height: 45px;overflow: hidden;border-radius: 100%">
                                            <img src="{{ asset('images/avatars/' . $video->user->channel->avatar) }}" width="100%" alt="Avatar not found">
                                        </div>
                                        <div class="float-left" style="padding-top: 3px;">
                                            <h4 class="fw text-dark">{{ $video->user->channel->name }}</h4>
                                            <h6 class="text-dark">{{ $subscribers ?? 0 }} Subscribers</h6>
                                        </div>
                                        <br class="clear">
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 text-right">
                                <a href="" class="btn btn-danger text-uppercase">Subscribe</a>
                            </div>
                        </div>
                        <div>
                            {!! $video->description ?? '' !!}
                        </div>
                    </div>
                    <hr>
                </section>

                <section class="comments-section" id="comments-section">
                    @if (auth()->check())
                    <form action="" method="post" class="mb-3" id="comment-form">
                        @csrf
                        <div class="row align-items-center" style="min-height: 50px;">
                            <div class="col-1">
                                <div style="width: 60px;height: 60px;overflow: hidden;border-radius: 100%">
                                    <img src="{{ asset('images/avatars/' . auth()->user()->channel->avatar) }}" width="100%" alt="Avatar not found">
                                </div>
                            </div>
                            <div class="col-10">
                                <textarea name="comment" id="comment" rows="2" class="form-control" placeholder="Add comment here..."></textarea>
                                <p class="mb-0 invalid-feedback" id="comment-error"></p>
                            </div>
                            <div class="col-1">
                                <button type="submit" class="btn btn-block btn-lg btn-primary" id="add-comment"><i class="fas fa-paper-plane"></i></button>
                            </div>
                        </div>
                    </form>
                    @else
                    <h3 class="text-center font-italic"><a href="{{ route('login.index') }}">Sign in</a> to add comment.</h3>
                    @endif

                    <div class="comments-inner-section" id="comments-inner-section">
                        <h3 class="fw mb-3" id="comments-count">{{ $video->comments()->count() ?? 0 }} Comments</h3>

                        <div class="comments" id="comments">
                            @forelse ($comments as $comment)
                            <div class="row mb-3">
                                <div class="col-1">
                                    <div style="width: 60px;height: 60px;overflow: hidden;border-radius: 100%;"><img src="{{ asset('images/avatars/' . $comment->user->channel->avatar) }}" width="100%" alt="Avatar not found"></div>
                                </div>
                                <div class="col-11">
                                    <h5 class="fw mb-1">{{ $comment->user->channel->name }} {!! $comment->user_id == $video->user_id ? '<small>(Owner)</small>' : '' !!}</h5>
                                    <p class="mb-1">{{ $comment->body }}</p>
                                    <p class="mb-0 d-inline">
                                        <span class="mr-3">{{ $comment->created_at->diffForHumans() }}</span>
                                        <span class="like mr-3" id="like_{{ $comment->id }}"><i class="fas fa-thumbs-up"></i> 0</span>
                                        <span class="dislike mr-3" id="dislike_{{ $comment->id }}"><i class="fas fa-thumbs-down"></i> 0</span>
                                        @if (auth()->check())
                                        <span class="reply mr-3 fw text-uppercase" data-id="{{ $comment->id }}"><i class="fas fa-reply"></i> Reply</span>
                                        @if (auth()->user()->id == $comment->user_id)
                                        <span class="delete delete-comment text-uppercase fw" data-id="{{ $comment->id }}"><i class="fas fa-trash-alt"></i> Delete</span>
                                        @endif
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="replies-section" id="replies-section">
                                @if (auth()->check())
                                <form action="" id="reply-form-{{ $comment->id }}" data-id="{{ $comment->id }}" class="mb-3 reply-form d-none" method="post">
                                    @csrf
                                    <div class="row align-items-center" style="min-height: 50px;">
                                        <div class="offset-1 col-1">
                                            <div style="width: 50px;height: 50px;overflow: hidden;border-radius: 100%">
                                                <img src="{{ asset('images/avatars/' . auth()->user()->channel->avatar) }}" width="100%" alt="Avatar not found">
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <textarea name="reply" id="reply-{{ $comment->id }}" rows="2" class="form-control" placeholder="Add Reply here..."></textarea>
                                            <p class="mb-0 invalid-feedback" id="reply-error-{{ $comment->id }}"></p>
                                        </div>
                                        <div class="col-1">
                                            <button type="submit" class="btn btn-block btn-lg btn-primary" id="add-reply"><i class="fas fa-paper-plane"></i></button>
                                        </div>
                                    </div>
                                </form>
                                @endif
                                @if ($comment->replies()->count() > 0)
                                    @foreach ($comment->replies()->latest()->get() as $reply)
                                    <div class="row mb-3">
                                        <div class="offset-1 col-1">
                                            <div style="width: 50px;height: 50px;overflow: hidden;border-radius: 100%;"><img src="{{ asset('images/avatars/' . $reply->user->channel->avatar) }}" width="100%" alt="Avatar not found"></div>
                                        </div>
                                        <div class="col-10">
                                            <h5 class="fw mb-1">{{ $reply->user->channel->name }} {!! $reply->user_id == $video->user_id ? '<small>(Owner)</small>' : '' !!}</h5>
                                            <p class="mb-1">{{ $reply->body }}</p>
                                            <p class="mb-0 d-inline">
                                                <span class="mr-3">{{ $reply->created_at->diffForHumans() }}</span>
                                                <span class="like mr-3" id="like_{{ $comment->id }}"><i class="fas fa-thumbs-up"></i> 0</span>
                                                <span class="dislike mr-3" id="dislike_{{ $comment->id }}"><i class="fas fa-thumbs-down"></i> 0</span>
                                                @if (auth()->check())
                                                <span class="reply mr-3 fw text-uppercase" data-id="{{ $comment->id }}"><i class="fas fa-reply"></i> Reply</span>
                                                @if (auth()->user()->id == $reply->user_id)
                                                <span class="delete delete-reply text-uppercase fw" data-id="{{ $reply->id }}"><i class="fas fa-trash-alt"></i> Delete</span>
                                                @endif
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                            @empty
                            <h3 class="text-center font-italic">Be first to comment.</h3>
                            @endforelse
                        </div>
                    </div>

                </section>
            </main>
            <aside class="col-12 col-md">
                @forelse ($videos as $vid)
                    <div class="row mb-3 align-items-center" style="min-height: 75px;">
                        <div class="col-5"><a href="{{ route('video.show', $vid->slug) }}"><img src="{{ asset('uploads/thumbnails/' . $vid->thumbnail) }}" width="100%" alt="Thumbnail not found"></a></div>
                        <div class="col-7">
                            <h5 class="fw"><a class="text-dark" href="{{ route('video.show', $vid->slug) }}">{{ Str::length($vid->title) > 50 ? Str::substr($vid->title, 0, 49) . '...' : $vid->title }}</a></h5>
                            <h6 class="fw"><a href="{{ route('channel.show', base64_encode(($vid->user->channel->id * 1234554321) / 67890)) }}" class="text-secondary text-uppercase">{{ $vid->user->channel->name }}</a></h6>
                            <p class="mb-0">{{ $vid->manyUsers()->count() ?? 0 }} Views | {{ $vid->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <h4 class="text-center font-italic">No video to show.</h4>
                @endforelse
            </aside>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            $('#comments-section').on('submit', '#comment-form', function(e) {
                e.preventDefault();

                var comment = $('#comment').val();

                if (comment == "") {
                    $('#comment').addClass('is-invalid');
                    $('#comment-error').html('Comment field is required.');
                    return false;
                } else {
                    $('#comment').removeClass('is-invalid');
                    $('#comment-error').html('');
                    $.ajax({
                        url : "{{ route('comment.store') }}",
                        method : 'post',
                        data : {
                            "_token" : "{{ csrf_token() }}",
                            comment : comment,
                            video_id : {{ $video->id }}
                        },
                        dataType : 'json',
                        success : function(response) {
                            $('#comment').val('');
                            $('#comments-count').html(response.comments);
                            $('#comments').html(response.output);
                        }
                    });
                }
            });

            $('#comments').on('click', '.reply', function(e) {
                e.preventDefault();
                var data_id = $(this).data('id');
                $('#reply-form-' + data_id).removeClass('d-none');
            });

            $('#comments').on('submit', '.reply-form', function(e) {
                e.preventDefault();
                var comment_id = $(this).data('id');
                var reply = $('#reply-' + comment_id).val();

                if (reply == "") {
                    $('#reply-' + comment_id).addClass('is-invalid');
                    $('#reply-error-' + comment_id).html('Reply field is required.');
                } else {
                    $('#reply-' + comment_id).removeClass('is-invalid');
                    $('#reply-error-' + comment_id).html('');
                    $.ajax({
                        url : "{{ route('reply.store') }}",
                        method : 'post',
                        data : {
                            "_token" : "{{ csrf_token() }}",
                            comment_id : comment_id,
                            reply : reply
                        },
                        dataType : 'json',
                        success: function(response) {
                            $('#reply-' + comment_id).val('');
                            $('#comments-count').html(response.comments);
                            $('#comments').html(response.output);
                        }
                    });
                }
            });

            $('#comments').on('click', '.delete-comment', function(e) {
                e.preventDefault();
                if (confirm('Are you sure to delete this comment?')) {
                    var comment_id = $(this).data('id');
                    $.ajax({
                        url : "{{ route('comment.destroy') }}",
                        method : 'post',
                        data : {
                            "_token" : "{{ csrf_token() }}",
                            comment_id : comment_id,
                            video_id : {{ $video->id }}
                        },
                        dataType : 'json',
                        success : function(response) {
                            $('#comments-count').html(response.comments);
                            $('#comments').html(response.output);
                            $('#toast').html(response.toast);
                            $('#comment-toast').toast({ delay : 5000 });
                            $('#comment-toast').toast('show');
                        }
                    });
                } else {
                    return false;
                }
            });

            $('#comments').on('click', '.delete-reply', function(e) {
                e.preventDefault();
                if (confirm('Are you sure to delete the comment?')) {
                    var reply_id = $(this).data('id');
                    $.ajax({
                        url : "{{ route('reply.destroy') }}",
                        method : 'post',
                        data : {
                            "_token" : "{{ csrf_token() }}",
                            video_id : {{ $video->id }},
                            reply_id : reply_id
                        },
                        dataType : 'json',
                        success : function(response) {
                            $('#comments-count').html(response.comments);
                            $('#comments').html(response.output);
                            $('#toast').html(response.toast);
                            $('#comment-toast').toast({ delay : 5000 });
                            $('#comment-toast').toast('show');
                        }
                    });
                } else {
                    return false;
                }
            });
        });
    </script>
@endsection
