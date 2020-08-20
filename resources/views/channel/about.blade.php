@extends('master.master')

@section('title')
    <title>{{ $channel->name ?? 'My Channel' }} - {{ config('app.name') }}</title>
@endsection

@section('content')
    <div>
        @include('components.channel-header')

        <div class="container-fluid py-3">
            <div class="container">
                <div class="row">
                    <main class="col-md-9">
                        <div class="details">
                            <h3 class="mb-3">Details</h3>
                            <p class="mb-2"><strong>For business inquiries email at:</strong> {{ $channel->user->email }}</p>
                            <p class="mb-2"><strong>For business inquiries call at:</strong> {{ $channel->phone_number }}</p>
                            <p class="mb-2"><strong>Country:</strong> {{ $channel->country->name ?? 'Country' }}</p>
                        </div>
                    </main>
                    <aside class="col-md-3">
                        <h3 class="mb-3">Stats</h3>
                        <p class="mb-2"><strong>Joined:</strong> {{ date('F d, Y', strtotime($channel->created_at)) }}</p>
                        <p class="mb-2">{{ 1234567890 }} Views</p>
                    </aside>
                </div>
            </div>
        </div>
    </div>
@endsection
