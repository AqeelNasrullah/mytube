@extends('master.master')

@section('title')
    <title>{{ $channel->name ?? 'Channel Name' }} - {{ config('app.name') }}</title>
@endsection

@section('style')
    <style>
        .content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding-top: 113px;
        }

        .search-box {
            display: none;
        }

        .avatar-cover {
            width: 100px;
            height: 100px;
            overflow: hidden;
        }

        .alert-danger {
            margin-top:50px;
        }

    </style>
@endsection

@section('content')
    <div class="container-fluid py-3">
        <div class="mx-auto bg-white p-3" style="border-radius: 5px;max-width: 475px;">
            <h3 class="mb-3 fw"><i class="fab fa-youtube text-danger"></i> Channel Detail</h3>

            @include('components.error')
            @include('components.success')

            <form action="{{ route('channel.update', base64_encode(($channel->id * 1234554321) / 67890)) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-2 avatar-cover mx-auto">
                    <img src="{{ asset('images/avatars/' . $channel->avatar) }}" id="avatar-img" width="100%" alt="Image not found">
                </div>
                <input type="file" name="avatar" id="avatar" class="d-none">
                <button id="upload-btn" class="btn btn-danger mb-3 w-50" style="position: relative;left: 25%;"><i
                        class="fas fa-upload"></i> Upload Picture</button>
                <div class="form-group">
                    <label for="name">Name <span class="required">*</span>:</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Name"
                        value="{{ $channel->name }}">
                </div>
                <div class="form-group">
                    <label for="name">Email <span class="required">*</span>:</label>
                    <input type="text" name="email" id="email" class="form-control" placeholder="Email" readonly
                        value="{{ $channel->user->email }}">
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number <span class="required">*</span>:</label>
                    <input type="text" name="phone_number" id="phone" class="form-control" placeholder="Phone Number without dashes e.g +92XXXXXXXXXX"
                        value="{{ $channel->phone_number }}">
                </div>
                <div class="form-group">
                    <label for="address">Address <span class="required">*</span>:</label>
                    <input type="text" name="address" id="address" class="form-control" placeholder="Address"
                        value="{{ $channel->address }}">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="city">City <span class="required">*</span>:</label>
                            <input type="text" name="city" id="city" class="form-control" placeholder="City"
                                value="{{ $channel->city }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="state">State <span class="required">*</span>:</label>
                            <input type="text" name="state" id="state" class="form-control" placeholder="State" value="{{ $channel->state }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="postalcode">Postal Code <span class="required">*</span>:</label>
                            <input type="text" name="postal_code" id="postalcode" class="form-control"
                                placeholder="Postal Code" value="{{ $channel->postal_code }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country">Country <span class="required">*</span>:</label>
                            <select name="country" id="country" class="custom-select">
                                @foreach ($countries as $country)
                                    <option value="{{ $country->id }}" {{ $country->id == $channel->country_id ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div>
                    <button type="submit" class="btn btn-danger float-right">Update Channel</button>
                    <br class="clear">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#upload-btn').click(function(e) {
                $('#avatar').click();
                e.preventDefault();
            });

            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#avatar-img').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                }
            }

            $("#avatar").change(function() {
                readURL(this);
            });
        });

    </script>
@endsection
