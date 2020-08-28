@extends('master.master')

@section('title')
    <title>{{ $channel->name ?? 'My Channel' }} - {{ config('app.name') }}</title>
@endsection

@section('content')
    <div>
        @include('components.channel-header')

        <div class="container py-3">
            @include('components.success')

            @forelse ($videos as $video)
            <div class="row mb-3 align-items-center" style="min-height: 150px;">
                <div class="col-12 col-sm-3">
                    <a href="{{ route('video.show', $video->slug) }}"><img src="{{ asset('uploads/thumbnails/' . $video->thumbnail) }}" width="100%" alt="Thumbnail not found"></a>
                </div>
                <div class="col-12 col-sm col-md-7">
                    <a href="{{ route('video.show', $video->slug) }}"><h2 class="fw text-dark mb-2">{{ $video->title }}</h2></a>
                    <h5 class="mb-0 text-dark">{{ $video->manyUsers()->count() ?? 0 }} Views | {{ $video->created_at->diffForHumans() }} {!! (auth()->check() ? auth()->user()->id : 3) == $channel->user_id ? '| <i class="fas fa-eye"></i> ' . (($video->status) == 'public' ? 'Public' : 'Private') : '' !!}</h5>
                </div>
                <div class="col-12 col-sm-12 col-md-2 text-center">
                    @if ((auth()->check() ? auth()->user()->id : 3) == $channel->user_id)
                    <a href="{{ route('video.edit', $video->slug) }}" class="d-inline text-success text-large mr-3" title="Edit"><i class="fas fa-edit"></i></a>
                    <form action="{{ route('video.destroy', $video->slug) }}" method="post" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link p-0 m-0 delete-video text-large text-danger" title="Delete"><i class="fas fa-trash"></i></button>
                    </form>
                    @endif
                </div>
                </div>
            @empty
            <div>
                <h1 class="text-center">No video uploaded yet.</h1>
                @auth
                    @if (auth()->user()->id == $channel->user_id)
                        <p class="text-center"><a href="{{ route('video.create') }}" class="btn btn-danger">Upload Video</a></p>
                    @endif
                @endauth
            </div>
            @endforelse

            <div class="d-flex" style="justify-content: space-between;">
                <div>{{ $videos->links() }}</div>
                <p>Showing {{ $videos->firstItem() ?? 0 }} - {{ $videos->lastItem() ?? 0 }} of {{ $videos->total() ?? 0 }} videos</p>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.delete-video').click(function() {
                if (confirm('Are you sure to delete the video?')) {
                    return true;
                } else {
                    return false;
                }
            });

            $('.subscribe').click(function() {
                var channel_id = $(this).data('id');

                if ($(this).hasClass('subscribed')) {
                    if (confirm('Are you sure you want to unsubscribe?')) {
                        continue;
                    } else {
                        return false;
                    }
                }

                $.ajax({
                    url : "{{ route('channel.subscribe') }}",
                    method : 'post',
                    data : {
                        "_token" : "{{ csrf_token() }}",
                        channel_id : channel_id
                    },
                    dataType : 'json',
                    success: function(response) {
                        if (response.status == 'subscribed') {
                            $('.subscribe').removeClass('btn-danger');
                            $('.subscribe').addClass('btn-secondary');
                            $('.subscribe').addClass('subscribed');
                            $('.subscribe').html('Subscribed');
                        } else {
                            $('.subscribe').removeClass('btn-secondary');
                            $('.subscribe').addClass('btn-danger');
                            $('.subscribe').removeClass('subscribed');
                            $('.subscribe').html('Subscribe');
                        }
                    }
                });
            });
        });
    </script>
@endsection
