@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">{{ __('admin.settings') }}</h1>
</div>
@if(session('updated'))
    <div class="alert alert-success" role="alert">{{ session('updated') }}</div>
@endif
<div class="card">
    <h5 class="card-header h6">{{ __('admin.free apply times') }}</h5>
    <div class="card-body">
        <form method="POST" action="/admin/settings">
        @csrf
            <div class="input-group w-25">
                <input type="text" class="form-control" name="free_apply_times" value="{{ config('app.free_apply') }}">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="submit">{{ __('admin.update') }}</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
