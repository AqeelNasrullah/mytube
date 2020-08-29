<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- Site Title --}}
    @yield('title')

    {{-- Site Icons --}}
    <link rel="shortcut icon" href="{{ asset('images/mytube.webp') }}" type="image/x-icon">

    {{-- Style Sheets --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}"> {{-- Common Style File
    --}}
    <link rel="stylesheet" href="{{ asset('css/master.css') }}"> {{-- Master Style File
    --}}

    @yield('style')

</head>

<body>
    <main>
        <section id="toast" style="position: absolute;z-index: 10000;top: 10px;right: 10px;">

        </section>

        <header class="header p-2 d-flex"
            style="justify-content: space-between;position: sticky;top:0px;background-color: white;z-index: 100;">
            <div>
                <h4 class="float-left mb-0 mr-3" id="navicon" style="margin-top: 9px; cursor: pointer;"><i
                        class="fas fa-bars"></i></h4>
                <a href="{{ url('') }}">
                    <h1 class="mb-0 float-right fw text-dark"><i class="fab fa-youtube text-danger"></i> {{ config('app.name') }}</h1>
                </a>
                <br class="clear">
            </div>

            <div class="search-box">
                <form action="{{ route('home.search') }}" method="get">
                    @csrf
                    <div class="input-group">
                        <input type="text" name="search" id="search" placeholder="Search" class="form-control"
                            style="width: 375px;height: 42px;font-size: medium;border-radius: 2px;" value="{{ $query ?? '' }}">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-block btn-secondary"
                                style="width: 75px;border-radius: 2px;border-top-left-radius: 0px;border-bottom-left-radius: 0px; background: rgba(245,245,245,1.00) !important;color: gray;border: 1px solid lightgray;"><i
                                    class="fas fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>

            <div>
                <div class="float-left">
                    <h5 class="search-icon float-left mr-3 d-none" style="margin-top: 12px;"><i
                            class="fas fa-search"></i></h5>
                </div>
                @auth
                    <div class="float-left">
                        <h5 class="mb-0 float-left mr-3" style="margin-top: 11.5px;"><a href="{{ route('video.create') }}" class="text-dark"><i
                                    class="fas fa-upload"></i> <span class="upload-txt">Upload</span></a></h5>
                        <div class="dropdown float-left">
                            <div class="avatar" style="cursor: pointer;" data-toggle="dropdown"><img
                                    src="{{ asset('images/avatars/' . (auth()->user()->channel->avatar ?? 'user.webp')) }}"
                                    width="100%" alt="Avatar not found"></div>

                                <ul class="dropdown-menu dropdown-menu-right mt-2 pro-dropdown" style="border-top: 3px solid red;width: 275px;">
                                    @if (auth()->user()->channel()->first())
                                    <div class="p-2">
                                        <div class="img" style="width: 45px;height: 45px;border-radius: 100%;overflow: hidden;float: left;margin-right: 10px;"><img src="{{ asset('images/avatars/' . auth()->user()->channel->avatar) }}" width="100%" alt="Avatar not found"></div>
                                        <div class="float-left" style="width: calc(100% - 55px);">
                                            <h4 class="fw mb-0 pb-0" style="text-overflow: ellipsis !important;">{{ auth()->user()->channel->name }}</h4>
                                            <p class="mb-0">{{ auth()->user()->email }}</p>
                                        </div>
                                        <br class="clear">
                                    </div>
                                    <div class="dropdown-divider"></div>
                                    <a href="{{ route('channel.show', base64_encode((auth()->user()->channel->id * 1234554321) / 67890)) }}" class="dropdown-item"><i class="fas fa-user mr-2"></i> My Channel</a>
                                    @else
                                    @if (auth()->user()->status == 'unverified')
                                    <a href="" class="dropdown-item disabled"><i class="fas fa-user mr-2"></i> Verify email first.</a>
                                    @else
                                    <a href="{{ route('channel.create') }}" class="dropdown-item"><i class="fas fa-user mr-2"></i> Create Channel</a>
                                    @endif
                                    @endif
                                    <a href="{{ route('login.logout') }}" class="dropdown-item"><i class="fas fa-sign-out-alt mr-2"></i> Sign out</a>

                                </ul>

                        </div>
                        <br class="clear">
                    </div>
                @else
                    <a href="{{ route('login.index') }}" class="btn btn-danger float-left" style="font-size: larger;"><i
                            class="fas fa-user"></i> Sign
                        in</a>
                @endauth
                <br class="clear">
            </div>
        </header>

        <main>
            <aside class="side-menu float-left" style="position: sticky;top: 60px;left: 0px;z-index:90;">
                <ul style="border-bottom: 1px solid lightgray;">
                    <li class="p-2"><a href="{{ route('home.index') }}" class="text-dark d-block"><span><i class="fas fa-home"></i></span>
                            Home</a></li>
                    <li class="p-2"><a href="{{ route('home.trending') }}" class="text-dark d-block"><span><i class="fas fa-fire"></i></span>
                            Trending</a></li>
                    <li class="p-2"><a href="{{ route('home.subscriptions') }}" class="text-dark d-block"><span><i class="fas fa-align-left"></i></span>
                            Subscriptions</a></li>
                </ul>
                <ul style="border-bottom: 1px solid lightgray;">
                    <li class="p-2"><a href="{{ route('home.history') }}" class="text-dark d-block"><span><i class="fas fa-history"></i></span>
                            History</a></li>
                    <li class="p-2"><a href="{{ route('channel.show', base64_encode((auth()->user()->channel->id * 1234554321) / 67890)) }}" class="text-dark d-block"><span><i class="fas fa-play"></i></span> Your
                            Videos</a></li>
                </ul>
                @auth
                <ul style="border-bottom: 1px solid lightgray;">
                    <h6 class="p-2 mb-0 text-uppercase">Subscriptions</h6>

                    @forelse (auth()->user()->manyChannels()->get() as $channel)
                    <li class="p-2"><a href="{{ route('channel.show', base64_encode(($channel->id * 1234554321) / 67890)) }}" class="text-dark d-block" style="font-size: large;"><span
                        style="margin-right: 3px;"><img src="{{ asset('images/avatars/' . $channel->avatar) }}"
                            width="30px" height="30px" style="border-radius: 100%;overflow: hidden;"
                            alt=""></span> {{ $channel->name }}</a></li>
                    @empty
                        <li class="dropdown-item text-center">No Subscribed Channel Yet.</li>
                    @endforelse
                </ul>
                @endauth
                <div class="p-2">
                    <p><a class="text-secondary" href="">About</a> - <a class="text-secondary" href="">Terms</a> - <a
                            class="text-secondary" href="">Privacy</a> - <a class="text-secondary" href="">Policy &amp;
                            Safety</a></p>
                    <p class="mb-0">Copyrights &copy; {{ date('Y') }} - All Rights Reserved by 2017-CS-439</p>
                </div>
            </aside>
            <main class="content float-right">
                @yield('content')
            </main>
            {{-- <br class="clear"> --}}
        </main>
    </main>


    {{-- Script Files --}}
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/fontawesome.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.toast').toast({ delay : 5000 });
            $('.toast').toast('show');
            $('#navicon').click(function() {
                if ($('.side-menu').outerWidth() == 0) {
                    $('.side-menu').css({
                        'width': '275px',
                        'display': 'block'
                    });
                    $('.content').css('width', 'calc(100% - 275px)');
                } else {
                    $('.side-menu').css({
                        'display': 'none',
                        'width': '0px'
                    });
                    $('.content').css('width', '100%');
                }
            });
            $('.side-menu').css('height', 'calc(100vh - ' + $('.header').outerHeight() + 'px)');
            $('.content').css('height', 'calc(100vh - ' + $('.header').outerHeight() + 'px)');
        });

    </script>
    @yield('script')
</body>

</html>
