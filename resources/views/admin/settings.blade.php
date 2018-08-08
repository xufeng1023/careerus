@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">{{ __('admin.settings') }}</h1>
</div>
@if(session('updated'))
    <div class="alert alert-success" role="alert">{{ session('updated') }}</div>
@endif
<div>
    <form method="POST" action="/admin/settings" autocomplete="off">
        @csrf
        <div class="form-group">
            <label>{{ __('admin.apply times per day') }}</label>
            <input type="text" class="form-control w-25" name="apply_times_a_day" value="{{ cache('apply_times_a_day') }}">
        </div>

        <div class="form-group">
            <label>{{ __('admin.job applies per day') }}</label>
            <input type="text" class="form-control w-25" name="job_applies_a_day" value="{{ cache('job_applies_a_day') }}">
        </div>

        <div class="form-group">
            <label>每个工作最大申请次数</label>
            <input type="text" class="form-control w-25" name="job_applies_limit" value="{{ cache('job_applies_limit') }}">
        </div>

        <button class="btn btn-primary" type="submit">{{ __('admin.update') }}</button>
    </form>
</div>
@endsection
