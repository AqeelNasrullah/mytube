@extends('master.master')

@section('title')
    <title>History - {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="container py-3">
        <h3 class="fw">Watch History</h3>
        <hr>
        @forelse ($videos as $video)
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
        @empty
        <div class="col-12 text-center">No watch history available.</div>
        @endforelse

        <div class="d-flex" style="justify-content: space-between;">
            <div class="paginate">
                {{ $videos->links() }}
            </div>
            <p>Showing {{ $videos->firstItem() ?? 0 }} - {{ $videos->lastItem() ?? 0 }} of {{ $videos->total() ?? 0 }} results</p>
        </div>
    </div>
@endsection
