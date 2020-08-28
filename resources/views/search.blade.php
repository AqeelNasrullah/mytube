@extends('master.master')

@section('title')
    <title>{{ $query ?? 'Search Results' }} - {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="container py-3">
        @if ($channels->count() > 0 or $videos->count() > 0)
            @if ($channels->count() > 0)
                <div class="channels" id="channels">
                    <h2 class="fw">Channels</h2>
                    <hr>
                    <div>
                        @foreach ($channels as $channel)
                            <div class="row align-items-center mb-3" style="min-height: 50px;">
                                <div class="col-2"><a
                                        href="{{ route('channel.show', base64_encode(($channel->id * 1234554321) / 67890)) }}"><div style="width: 125px;height: 125px;overflow: hidden;border-radius: 100%;"><img
                                            src="{{ asset('images/avatars/' . $channel->avatar) }}" width="100%"
                                            alt="Avatar not found"></div></a></div>
                                <div class="col">
                                    <h1 class="fw"><a class="text-dark"
                                            href="{{ route('channel.show', base64_encode(($channel->id * 1234554321) / 67890)) }}">{{ $channel->name }}</a>
                                    </h1>
                                    <h5>{{ $channel->manyUsers()->count() ?? 0 }} Subscribers |
                                        {{ $channel->user->videos()->count() ?? 0 }} Videos</h5>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($videos->count() > 0)
            <div class="channels" id="channels">
                <h2 class="fw">Videos</h2>
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
        @else
            <h1>No search result.</h1>
        @endif
    </div>
@endsection
