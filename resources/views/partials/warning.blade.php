@if(session('info'))
    <div class="alert alert-danger" role="alert">
        {{ session('info') }}
    </div>
@endif