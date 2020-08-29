@extends('master.master')

@section('title')
    <title>Trending - {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="container py-3">
        @if ($videos->count() > 0)
            <div class="channels" id="channels">
                <h2 class="fw">Trending Videos</h2>
                <hr>
                <div>
                    @foreach ($videos as $video)
                        @if ($video->user->channel()->first())
                        <div class="row mb-3 align-items-center" style="min-height: 125px;">
                            <div class="col-12 col-sm-3">
                                <a href="{{ route('video.show', $video->slug) }}"><img src="{{ asset('uploads/thumbnails/' . $video->thumbnail) }}" width="100%" alt="Thumbnail not found">
                            </div>
                            <div class="col-12 col-sm">
                                <a href="{{ route('video.show', $video->slug) }}" class="text-dark"><h2 class="fw">{{ $video->title }}</h2></a>
                                <h5 class="mb-3">{{ $video->manyUsers()->count() ?? 0 }} Views | {{ $video->created_at->diffForHumans() }}</h5>
                                <a href="{{ route('channel.show', base64_encode(($video->user->channel->id * 1234554321) / 67890)) }}" class="text-dark">
                                    <div>
                                        <div class="float-left mr-2" style="width: 40px;height: 40px;overflow: hidden;border-radius: 100%;"><img src="{{ asset('images/avatars/' . $video->user->channel->avatar) }}" width="100%" alt="Avatar not found"></div>
                                        <div class="float-left"><h5 style="padding-top: 10px;">{{ $video->user->channel->name }}</h5></div>
                                        <br class="clear">
                                    </div>
                                </a>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

    </div>
@endsection
