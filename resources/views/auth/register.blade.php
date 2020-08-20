@extends('master.master')

@section('title')
    <title>Register - {{ config('app.name') }}</title>
@endsection

@section('style')
    <style>
        .content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .search-box {
            display: none;
        }

    </style>
@endsection

@section('content')
    <div class="container-fluid py-3">
        <div class="mx-auto bg-white p-3" style="border-radius: 5px;max-width: 375px;">
            <h3 class="mb-3 fw"><i class="fab fa-youtube text-danger"></i> Register</h3>

            @include('components.error')

            <form action="{{ route('register.store') }}" method="post" class="{{ auth()->check() ? 'd-none' : '' }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email <span class="required">*</span>:</label>
                    <input type="text" name="email" id="email" placeholder="Email" class="form-control"
                        value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label for="password">Password <span class="required">*</span>:</label>
                    <input type="password" name="password" id="password" placeholder="Password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="retype-password">Retype Password <span class="required">*</span>:</label>
                    <input type="password" name="retype_password" id="retype-password" placeholder="Retype Password"
                        class="form-control">
                </div>
                <div>
                    <a href="{{ route('login.index') }}" class="float-left">Already have account. Login?</a>
                    <button type="submit" class="btn btn-danger float-right">Register</button>
                    <br class="clear">
                </div>
            </form>

            @auth
                @if (auth()->user()->status == 'unverified')
                    <div class="alert alert-success text-center">{!! session()->get('success') ?? 'An email sent to <strong>' .
                            auth()->user()->email . '</strong>. Click on link to verify your email.<br><strong>Note:</strong>
                        Link is only valid for one hour.' !!}</div>
                @else

                    @if (auth()
                ->user()
                ->channel()
                ->latest()
                ->first())
                        <div class="alert alert-success text-center"><a href="{{ route('home.index') }}"
                                class="alert-link">Click here</a> to go to home page or Sign out to register new email.</div>
                    @else
                        <div class="alert alert-success text-center"><a href="{{ route('channel.create') }}" class="alert-link">Click here</a> to create new
                            Channel.</div>
                    @endif
                @endif
            @endauth
        </div>
    </div>
@endsection
