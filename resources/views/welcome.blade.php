@extends('master.master')

@section('title')
    <title>{{ config('app.name') }} - Home for videos that matter</title>
@endsection

@section('content')
    <div class="container-fluid py-3">
        @include('components.success')
    </div>
@endsection
