@extends('master.master')

@section('title')
    <title>Login - {{ config('app.name') }}</title>
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
            <h3 class="mb-3 fw"><i class="fab fa-youtube text-danger"></i> Login</h3>

            @include('components.error')

            <form action="{{ route('login.attemptLogin') }}" method="post">
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
                <div class="mb-3">
                    <label for="remember" class="float-left"><input type="checkbox" name="remember" id="remember"> Remember me</label>
                    <button type="submit" class="btn btn-danger float-right">Login</button>
                    <br class="clear">
                </div>
            </form>

            <h6 class="text-center"><a href="{{ route('register.index') }}">Not a memeber? Register Here</a></h6>
        </div>
    </div>
@endsection
