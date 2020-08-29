@extends('master.master')

@section('title')
    <title>{{ $video->title ?? 'Video Title' }} - {{ config('app.name') }}</title>
@endsection

@section('style')
    <style>
        .reply, .delete, .like-comment, .dislike-comment, .like-reply, .dislike-reply {cursor: pointer;}
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
                            <h5 class="text-right">
                                <a href="" class="{{ $video->likes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-success' : 'text-dark' }} like-video mr-3" data-id="{{ $video->id }}"><i class="fas fa-thumbs-up"></i> <span class="likes-count">{{ $video->likes()->count() ?? 0 }}</span></a>
                                <a href="" class="{{ $video->dislikes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-danger' : 'text-dark' }} dislike-video" data-id="{{ $video->id }}"><i class="fas fa-thumbs-down"></i> <span class="dislikes-count">{{ $video->dislikes()->count() ?? 0 }}</span></a></h5>
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
                                            <h6 class="text-dark subscribers-count">{{ $video->user->channel->manyUsers()->count() ?? 0 }} Subscribers</h6>
                                        </div>
                                        <br class="clear">
                                    </div>
                                </a>
                            </div>
                            <div class="col-3 text-right">
                                @auth
                                    <button class="btn subscribe text-uppercase {{ auth()->user()->manyChannels()->where('channel_id', $video->user->channel->id)->first() ? 'btn-secondary subscribed' : 'btn-danger' }}" data-id="{{ $video->user->channel->id }}">{{ auth()->user()->manyChannels()->where('channel_id', $video->user->channel->id)->first() ? 'Subscribed' : 'Subscribe' }}</button>
                                @endauth
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
                                        <span class="like_comment_{{ $comment->id }} {{ $comment->likes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-success' : 'text-dark' }} like-comment mr-3" data-id="{{ $comment->id }}"><i class="fas fa-thumbs-up"></i> <span class="comment-likes-count-{{ $comment->id }}">{{ $comment->likes()->count() ?? 0 }}</span></span>
                                        <span class="dislike_comment_{{ $comment->id }} {{ $comment->dislikes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-danger' : 'text-dark' }} dislike-comment mr-3" data-id="{{ $comment->id }}"><i class="fas fa-thumbs-down"></i> <span class="comment-dislikes-count-{{ $comment->id }}">{{ $comment->dislikes()->count() ?? 0 }}</span></span>
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
                                                <span class="like-reply-{{ $reply->id }} {{ $reply->likes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-success' : 'text-dark' }} like-reply mr-3" data-id="{{ $reply->id }}"><i class="fas fa-thumbs-up"></i> <span class="reply-likes-count-{{ $reply->id }}">{{ $reply->likes()->count() ?? 0 }}</span></span>
                                                <span class="dislike-reply-{{ $reply->id }} {{ $reply->dislikes()->where('user_id', (auth()->user()->id ?? App\User::where('email', 'guest@mytube.com')->first()->id))->first() ? 'text-danger' : 'text-dark' }} dislike-reply mr-3" data-id="{{ $reply->id }}"><i class="fas fa-thumbs-down"></i> <span class="reply-dislikes-count-{{ $reply->id }}">{{ $reply->dislikes()->count() ?? 0 }}</span></span>
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


            $('.subscribe').click(function() {
                var channel_id = $(this).data('id');

                if ($(this).hasClass('subscribed')) {
                    if (confirm('Are you sure you want to unsubscribe?')) {
                        $.ajax({
                            url : "{{ route('channel.subscribe') }}",
                            method : 'post',
                            data : {
                                "_token" : "{{ csrf_token() }}",
                                channel_id : channel_id
                            },
                            dataType : 'json',
                            success: function(response) {
                                if (response.status == 'subscribed') {
                                    $('.subscribe').removeClass('btn-danger');
                                    $('.subscribe').addClass('btn-secondary');
                                    $('.subscribe').addClass('subscribed');
                                    $('.subscribe').html('Subscribed');
                                    $('.subscribers-count').html(response.count + ' Subscribers');
                                } else {
                                    $('.subscribe').removeClass('btn-secondary');
                                    $('.subscribe').addClass('btn-danger');
                                    $('.subscribe').removeClass('subscribed');
                                    $('.subscribe').html('Subscribe');
                                    $('.subscribers-count').html(response.count + ' Subscribers');
                                }
                            }
                        });
                    } else {
                        return false;
                    }
                } else {
                    $.ajax({
                        url : "{{ route('channel.subscribe') }}",
                        method : 'post',
                        data : {
                            "_token" : "{{ csrf_token() }}",
                            channel_id : channel_id
                        },
                        dataType : 'json',
                        success: function(response) {
                            if (response.status == 'subscribed') {
                                $('.subscribe').removeClass('btn-danger');
                                $('.subscribe').addClass('btn-secondary');
                                $('.subscribe').addClass('subscribed');
                                $('.subscribe').html('Subscribed');
                                $('.subscribers-count').html(response.count + ' Subscribers');
                            } else {
                                $('.subscribe').removeClass('btn-secondary');
                                $('.subscribe').addClass('btn-danger');
                                $('.subscribe').removeClass('subscribed');
                                $('.subscribe').html('Subscribe');
                                $('.subscribers-count').html(response.count + ' Subscribers');
                            }
                        }
                    });
                }
            });

            $('.like-video').click(function(e) {
                e.preventDefault();
                var video_id = $(this).data('id');

                $.ajax({
                    url : "{{ route('like.store') }}",
                    method : 'post',
                    data : {
                        "_token" : "{{ csrf_token() }}",
                        video_id : video_id
                    },
                    dataType : 'json',
                    success : function(response) {
                        if (response.status == 'unliked') {
                            $('.like-video').removeClass('text-success');
                            $('.like-video').addClass('text-dark');
                            $('.likes-count').html(response.count);
                            $('#toast').html(response.toast);
                            $('#like-toast').toast({delay : 5000});
                            $('#like-toast').toast('show');
                        } else {
                            $('.like-video').removeClass('text-dark');
                            $('.like-video').addClass('text-success');
                            $('.likes-count').html(response.count);
                            $('#toast').html(response.toast);
                            $('#like-toast').toast({delay : 5000});
                            $('#like-toast').toast('show');
                        }
                    }
                });
            });

            $('.dislike-video').click(function(e) {
                e.preventDefault();
                var video_id = $(this).data('id');

                $.ajax({
                    url : "{{ route('dislike.store') }}",
                    method : 'post',
                    data : {
                        "_token" : "{{ csrf_token() }}",
                        video_id : video_id
                    },
                    dataType : 'json',
                    success : function(response) {
                        if (response.status == 'undisliked') {
                            $('.dislike-video').removeClass('text-danger');
                            $('.dislike-video').addClass('text-dark');
                            $('.dislikes-count').html(response.count);
                            $('#toast').html(response.toast);
                            $('#dislike-toast').toast({delay : 5000});
                            $('#dislike-toast').toast('show');
                        } else {
                            $('.dislike-video').removeClass('text-dark');
                            $('.dislike-video').addClass('text-danger');
                            $('.dislikes-count').html(response.count);
                            $('#toast').html(response.toast);
                            $('#dislike-toast').toast({delay : 5000});
                            $('#dislike-toast').toast('show');
                        }
                    }
                });
            });

            $('#comments').on('click', '.like-comment', function() {
                var comment_id = $(this).data('id');
                $.ajax({
                    url : "{{ route('like.likeComment') }}",
                    method : 'post',
                    data : {
                        "_token" : "{{ csrf_token() }}",
                        comment_id : comment_id
                    },
                    dataType : 'json',
                    success : function(response) {
                        if (response.status == 'unliked') {
                            $('.like-comment-' + comment_id).removeClass('text-success');
                            $('.like-comment-' + comment_id).addClass('text-dark');
                            $('.comment-likes-count-' + comment_id).html(response.count);
                            $('#toast').html(response.toast);
                            $('#comment-like-toast').toast({delay : 5000});
                            $('#comment-like-toast').toast('show');
                        } else {
                            $('.like-comment-' + comment_id).removeClass('text-dark');
                            $('.like-comment-' + comment_id).addClass('text-success');
                            $('.comment-likes-count-' + comment_id).html(response.count);
                            $('#toast').html(response.toast);
                            $('#comment-like-toast').toast({delay : 5000});
                            $('#comment-like-toast').toast('show');
                        }
                    }
                });
            });

            $('#comments').on('click', '.dislike-comment', function() {
                var comment_id = $(this).data('id');
                $.ajax({
                    url : "{{ route('dislike.dislikeComment') }}",
                    method : 'post',
                    data : {
                        "_token" : "{{ csrf_token() }}",
                        comment_id : comment_id
                    },
                    dataType : 'json',
                    success : function(response) {
                        if (response.status == 'undisliked') {
                            $('.dislike-comment-' + comment_id).removeClass('text-danger');
                            $('.dislike-comment-' + comment_id).addClass('text-dark');
                            $('.comment-dislikes-count-' + comment_id).html(response.count);
                            $('#toast').html(response.toast);
                            $('#comment-dislike-toast').toast({delay : 5000});
                            $('#comment-dislike-toast').toast('show');
                        } else {
                            $('.dislike-comment-' + comment_id).removeClass('text-dark');
                            $('.dislike-comment-' + comment_id).addClass('text-danger');
                            $('.comment-dislikes-count-' + comment_id).html(response.count);
                            $('#toast').html(response.toast);
                            $('#comment-dislike-toast').toast({delay : 5000});
                            $('#comment-dislike-toast').toast('show');
                        }
                    }
                });
            });

            $('#comments').on('click', '.like-reply', function() {
                var reply_id = $(this).data('id');
                $.ajax({
                    url : "{{ route('like.likeReply') }}",
                    method : 'post',
                    data : {
                        "_token" : "{{ csrf_token() }}",
                        reply_id : reply_id
                    },
                    dataType : 'json',
                    success : function(response) {
                        if (response.status == 'unliked') {
                            $('.like-reply-' + reply_id).removeClass('text-success');
                            $('.like-reply-' + reply_id).addClass('text-dark');
                            $('.reply-likes-count-' + reply_id).html(response.count);
                            $('#toast').html(response.toast);
                            $('#reply-like-toast').toast({delay : 5000});
                            $('#reply-like-toast').toast('show');
                        } else {
                            $('.like-reply-' + reply_id).removeClass('text-dark');
                            $('.like-reply-' + reply_id).addClass('text-success');
                            $('.reply-likes-count-' + reply_id).html(response.count);
                            $('#toast').html(response.toast);
                            $('#reply-like-toast').toast({delay : 5000});
                            $('#reply-like-toast').toast('show');
                        }
                    }
                });
            });

            $('#comments').on('click', '.dislike-reply', function() {
                var reply_id = $(this).data('id');
                $.ajax({
                    url : "{{ route('dislike.dislikeReply') }}",
                    method : 'post',
                    data : {
                        "_token" : "{{ csrf_token() }}",
                        reply_id : reply_id
                    },
                    dataType : 'json',
                    success : function(response) {
                        if (response.status == 'undisliked') {
                            $('.dislike-reply-' + reply_id).removeClass('text-danger');
                            $('.dislike-reply-' + reply_id).addClass('text-dark');
                            $('.reply-dislikes-count-' + reply_id).html(response.count);
                            $('#toast').html(response.toast);
                            $('#reply-dislike-toast').toast({delay : 5000});
                            $('#reply-dislike-toast').toast('show');
                        } else {
                            $('.dislike-reply-' + reply_id).removeClass('text-dark');
                            $('.dislike-reply-' + reply_id).addClass('text-danger');
                            $('.reply-dislikes-count-' + reply_id).html(response.count);
                            $('#toast').html(response.toast);
                            $('#reply-dislike-toast').toast({delay : 5000});
                            $('#reply-dislike-toast').toast('show');
                        }
                    }
                });
            });
        });
    </script>
@endsection
