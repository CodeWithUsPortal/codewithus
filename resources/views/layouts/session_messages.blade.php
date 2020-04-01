@if (session('success'))
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    </div>
</div>
@endif

@if (session('danger'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-danger" role="alert">
                {{ session('danger') }}
            </div>
        </div>
    </div>
@endif

@if (session('warning'))
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-warning" role="alert">
                {{ session('warning') }}
            </div>
        </div>
    </div>
@endif
