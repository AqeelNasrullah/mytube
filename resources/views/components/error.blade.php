@if ($errors->any())
    <ul class="alert alert-danger" style="list-style: none;">
        <button class="close" data-dismiss="alert">&times;</button>
        @foreach ($errors->all() as $error)
            <li><i class="fas fa-times"></i> {{ $error }}</li>
        @endforeach
    </ul>
@elseif (session()->has('error'))
    <div class="alert alert-danger">
        <button class="close" data-dismiss="alert">&times;</button>
        <p class="mb-0"><i class="fas fa-times"></i> {!! session()->get('error') !!}</p>
    </div>
@endif
