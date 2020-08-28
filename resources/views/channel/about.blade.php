@extends('master.master')

@section('title')
    <title>{{ $channel->name ?? 'My Channel' }} - {{ config('app.name') }}</title>
@endsection

@section('content')
    <div>
        @include('components.channel-header')

        <div class="container-fluid py-3">
            <div class="container">

                @include('components.success')

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
                        <p class="mb-2">{{ $views ?? 0 }} Views</p>
                        <p class="mb-2">{{ $channel->manyUsers()->count() ?? 0 }} Subscribers</p>
                    </aside>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.subscribe').click(function() {
                var channel_id = $(this).data('id');

                if ($(this).hasClass('subscribed')) {
                    if (confirm('Are you sure you want to unsubscribe?')) {
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
                    } else {
                        return false;
                    }
                } else {
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
                }
            });
        });
    </script>
@endsection
