@extends('master.master')

@section('title')
    <title>{{ $channel->name ?? 'My Channel' }} - {{ config('app.name') }}</title>
@endsection

@section('content')
    <div>
        @include('components.channel-header')
    </div>
@endsection
