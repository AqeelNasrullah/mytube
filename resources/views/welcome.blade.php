@extends('master.master')

@section('title')
    <title>{{ config('app.name') }} - Home for videos that matter</title>
@endsection

@section('content')
    <div class="container-fluid py-3">
        @include('components.error')
        @include('components.success')

        <div class="row">
            @forelse ($videos as $video)
                @if ($video->user->channel()->first())
                <div class="col-12 col-sm-6 col-lg-3 mb-4">
                    <div class="mb-3">
                        <a href="{{ route('video.show', $video->slug) }}"><div class="thumb" style="height: 200px;overflow: hidden;"><img src="{{ asset('uploads/thumbnails/' . $video->thumbnail) }}" width="100%" alt="Thumbnail not found"></div></a>
                    </div>
                    <div>
                        <div class="float-left" style="width: 50px;height: 50px;overflow: hidden;border-radius: 100%; margin-right: 10px;">
                            <a href="{{ route('channel.show', base64_encode(($video->user->channel->id * 1234554321) / 67890)) }}" class="text-secondary"><img src="{{ asset('images/avatars/' . $video->user->channel->avatar) }}" width="100%" alt="Avatar not found"></a>
                        </div>
                        <div class="float-left" style="width: calc(100% - 60px);" >
                            <h4><a href="{{ route('video.show', $video->slug) }}" class="text-dark">{{ Str::length($video->title) > 50 ? Str::substr($video->title, 0, 49) . '...' : $video->title }}</a></h4>
                            <h6><a href="{{ route('channel.show', base64_encode(($video->user->channel->id * 1234554321) / 67890)) }}" class="text-secondary">{{ $video->user->channel->name }}</a></h6>
                            <h6 class="text-dark">{{ $video->manyUsers()->count() ?? 0 }} Views | {{ $video->created_at->diffForHumans() }}</h6>
                        </div>
                        <br class="clear">
                    </div>
                </div>
                @endif
            @empty
                <h2 class="col-12 text-center font-italic">No video to show.</h2>
            @endforelse
        </div>
    </div>
@endsection
