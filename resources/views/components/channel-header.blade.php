<style>
    .channel-nav {background-color: rgba(200,200,200,1.00);}
    .channel-nav ul li {list-style-type: none; text-transform: uppercase;float: left;}
    .channel-nav ul li:hover {background-color: rgba(225,225,225,1.00);}
    .channel-nav ul li a {color: black;display: block;padding: 10px 20px;}
    @media screen and (max-width: 769px) {
        .main-channel-header div {width: 100px; display: block !important; justify-content: center !important;}
        .channel-nav ul li a {width: 100%;}
    }
</style>

<header class="container-fluid py-3" style="background-color: rgba(225,225,225,1.00);">
    <div class="container main-channel-header d-flex" style="justify-content: space-between;">
        <div>
            <div class="header-avatar float-left mr-3" style="width: 75px;height: 75px;border-radius: 100%;overflow: hidden;"><img src="{{ asset('images/avatars/' . $channel->avatar) }}" width="100%" alt="Avatar not found"></div>
            <div class="float-left" style="margin-top: 16px;"><h1 class="fw">{{ $channel->name }}</h1></div>
        </div>
        <div>
            @auth
                @if ($channel->user_id == auth()->user()->id)
                <a href="{{ route('channel.edit', base64_encode(($channel->id * 1234554321) / 67890)) }}" class="btn btn-lg btn-primary text-uppercase" style="margin-top: 14px;">Customize Channel</a>
                @else
                <button class="btn subscribe btn-lg text-uppercase {{ auth()->user()->manyChannels()->where('channel_id', $channel->id)->first() ? 'btn-secondary subscribed' : 'btn-danger' }}" data-id="{{ $channel->id }}" style="margin-top: 14px;">{{ auth()->user()->manyChannels()->where('channel_id', $channel->id)->first() ? 'Subscribed' : 'Subscribe' }}</button>
                @endif
            @endauth
        </div>
    </div>
</header>
<nav class="channel-nav">
    <div class="container">
        <ul class="p-0 m-0">
            <li><a href="{{ route('channel.show', base64_encode(($channel->id * 1234554321) / 67890)) }}">Videos</a></li>
            @if ($channel->user_id == (auth()->check() ? auth()->user()->id : 3))
            @endif
            <li><a href="{{ route('channel.about', base64_encode(($channel->id * 1234554321) / 67890)) }}">About</a></li>
            @if ($channel->user_id == (auth()->check() ? auth()->user()->id : 3))
            <li><a href="{{ route('channel.settings', base64_encode(($channel->id * 1234554321) / 67890)) }}">Settings</a></li>
            @endif
        </ul>
        <br class="clear">
    </div>
</nav>
