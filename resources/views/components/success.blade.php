@if (session()->has('success'))
    <div class="alert alert-success">
        <button class="close" data-dismiss="alert">&times;</button>
        <p class="mb-0"><i class="fas fa-check"></i> {!! session()->get('success') !!}</p>
    </div>
@endif
