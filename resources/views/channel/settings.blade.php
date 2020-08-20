@extends('master.master')

@section('title')
    <title>{{ $channel->name ?? 'My Channel' }} - {{ config('app.name') }}</title>
@endsection

@section('content')
    <div>
        @include('components.channel-header')

        <div class="container-fluid py-3">
            <div class="container">
                <h4 class="mb-2">Choose how you appear and what you see on {{ config('app.name') }}</h4>
                <p>Signed in as <strong>{{ auth()->user()->email }}</strong></p>
                <hr>
                <h1 class="mb-3">Your {{ config('app.name') }} Channel</h1>
                <p>This is your public presence on {{ config('app.name') }}. You need a channel to upload your own videos, like or dislike videos or comment on videos.</p>
                <div class="mb-3">
                    <div class="float-left mr-5" style="width: 80px;height: 80px;border-radius: 100%;overflow: hidden;">
                        <img src="{{ asset('images/avatars/' . auth()->user()->channel->avatar) }}" width="100%" alt="Avatar not found">
                    </div>
                    <div class="float-left">
                        <h2 class="fw">{{ auth()->user()->channel->name }}</h2>
                        <h4 class="fw">{{ auth()->user()->email }}</h4>
                        <a href="">Edit Profile</a>
                    </div>
                    <br class="clear">
                </div>
                <h5 class="fw"><a href="">Manage or Customize Channel</a></h5>
                <hr>
                <h1 class="mb-3">Your Account</h1>
                <h4 class="fw mb-3"><span class="mr-5">Membership</span> No membership | <a href="">Get {{ config('app.name') }} Premium</a></h4>
                <form action="{{ route('channel.destroy', base64_encode(($channel->id * 1234554321) / 67890)) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="delete-channel btn btn-lg btn-danger text-uppercase">Delete Channel</button>
                </form>
                <p>Deleting channel will not close your account.</p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.delete-channel').click(function() {
                if (confirm('Are you sure you want to delete channel?')) {
                    return true;
                } else {
                    return false;
                }
            });
        });
    </script>
@endsection
