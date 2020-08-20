@extends('master.master')

@section('title')
    <title>Email Verification - {{ config('app.name') }}</title>
@endsection

@section('style')
    <style>
        .search-box {
            display: none;
        }

        .inner {
            max-width: 500px;
        }

        .content {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

    </style>
@endsection

@section('content')
    <div class="container-fluid py-3">
        <div class="inner mx-auto">
            @if (!$expired)
            <div>
                <h1 class="text-center text-success" style="font-size: 75px;"><i class="fas fa-check-circle"></i></h1>
                <h3 class="text-center mb-5">Email Verified Successfully</h3>
                @if (auth()->user()->channel()->latest()->first())
                <p class="text-center"><a href="{{ route('home.index') }}" class="btn btn-success btn-lg">Go to Home Page</a></p>
                @else
                <p class="text-center"><a href="{{ route('channel.create') }}" class="btn btn-success btn-lg">Complete Registration Process</a></p>
                @endif
            </div>
            @else
            <div>
                <h1 class="text-center text-danger" style="font-size: 75px;"><i class="fas fa-times-circle"></i></h1>
                <h3 class="text-center mb-5">Verification Link Expired</h3>
                @if (!auth()->user()->status == 'unverified')
                <p class="text-center"><a href="{{ route('verifyEmail.resendEmail', base64_encode((auth()->user()->id * 1234554321) / 67890)) }}" class="btn btn-danger btn-lg">Resend Verification Link</a></p>
                @endif
            </div>
            @endif
        </div>
    </div>
@endsection
