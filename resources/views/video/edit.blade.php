@extends('master.master')

@section('title')
    <title>{{ $video->title ?? 'Video Title' }} - {{ config('app.name') }}</title>
@endsection

@section('style')
    <style>
        .search-box {
            display: none;
        }

        .uploadedVideo,
        .uploadedThumbnail {
            width: 100%;
            background-color: gray;
            min-height: 150px;
            margin-bottom: 10px;
        }

    </style>
@endsection

@section('content')
    <div class="container py-3">

        @include('components.error')

        <div class="row">
            <main class="col-md-8">
                <h1 class="fw mb-3">Detail</h1>
                <form action="{{ route('video.update', $video->slug) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="uploaded-video">Uploaded Video:</label>
                                <input type="text" name="uploaded_video" id="uploaded-video" placeholder="Uplaoded Video" value="{{ $video->video }}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="uploaded-thumbnail">Uploaded Thumbnail:</label>
                                <input type="text" name="uploaded_thumbnail" id="uploaded-tumbnail" placeholder="Uplaoded Thumbnail" value="{{ session()->get('thumbnail') ?? $video->thumbnail }}" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="title">Video Title <span class="required">*</span>:</label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="Video Title" value="{{ $video->title }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="description">Video Description:</label>
                                <textarea name="description" id="description" rows="5" class="form-control" placeholder="Video Description">{{ $video->description }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category">Category <span class="required">*</span>:</label>
                                <select name="category" id="category" class="custom-select">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $video->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="visibility">Visibility <span class="required">*</span>:</label>
                                <select name="visibility" id="visibility" class="custom-select">
                                    <option value="public" {{ ($video->status == 'public') ? 'selected' : '' }}>Public</option>
                                    <option value="private" {{ ($video->status == 'private') ? 'selected' : '' }}>Private</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-primary float-right">Update</button>
                        <a href="{{ route('channel.show', base64_encode(($video->user->channel->id * 1234554321) / 67890)) }}" class="btn btn-outline-danger float-right mr-3">Close</a>
                    </div>
                </form>
            </main>
            <aside class="col-md-4">

                @include('components.success')

                <form action="{{ route('video.updateVideo') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <h3 class="mb-3 fw">Video</h3>
                    <div class="uploadedVideo">
                        {{-- @if ($video = session()->get('video')) --}}
                            <video width="100%" height="auto" controls>
                                <source src="{{ asset('uploads/videos/' . $video->video) }}">
                            </video>
                        {{-- @else
                            <h4 class="text-center align-middle pt-5">Video Preview</h4>
                        @endif --}}
                    </div>

                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" name="video" class="custom-file-input" id="video" disabled>
                            <label class="custom-file-label" for="video">Choose Video</label>
                        </div>
                    </div>

                    <hr>
                    <h3 class="mb-3 fw">Thumbnail</h3>
                    <div class="uploadedThumbnail">
                        @if ($thumbnail = session()->get('thumbnail'))
                        <img src="{{ asset('uploads/thumbnails/' . $thumbnail) }}" width="100%" alt="Thumbnail not found">
                        @else
                        <img src="{{ asset('uploads/thumbnails/' . $video->thumbnail) }}" width="100%" alt="Thumbnail not found">
                        @endif
                    </div>

                    <div class="form-group">
                        <div class="custom-file">
                            <input type="file" name="thumbnail" class="custom-file-input" id="thumbnail">
                            <label class="custom-file-label" for="video">Choose Thumbnail</label>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-block btn-primary">Upload</button>
                    </div>
                </form>


            </aside>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace('description');
    </script>
@endsection
