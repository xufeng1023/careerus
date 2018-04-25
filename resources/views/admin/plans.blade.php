@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">{{ __('admin.plans') }}</h1>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @if(request('id'))
            <li class="nav-item">
                <a class="nav-link active" href="/admin/plan">
                    {{ __('admin.return') }}
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link active" data-toggle="pill" href="#job-list" role="tab" aria-controls="job-list" aria-selected="true">
                    {{ __('admin.list') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="pill" href="#plan-add" role="tab" aria-controls="plan-add" aria-selected="false">
                    {{ __('admin.job add') }}
                </a>
            </li>
        @endif
    </ul>
</div>

<div class="tab-content" id="pills-tabContent">
    <div class="tab-pane fade {{ !request('id')? 'show active' : '' }}" id="job-list" role="tabpanel" aria-labelledby="job-list-tab">
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>{{ __('admin.plan name') }}</th>
                        <th>{{ __('admin.plan price') }}</th>
                        <th>{{ __('admin.plan points') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($plans as $plan)
                        <tr>
                            <td><a href="?id={{ $plan->id }}">{{ $plan->name }}</a></td>
                            <td>{{ $plan->price }}</td>
                            <td>{{ $plan->points }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade {{ request('id')? 'show active' : '' }}" id="plan-add" role="tabpanel" aria-labelledby="plan-add-tab">
        @if(session('updated'))
            <div class="alert alert-success" role="alert">{{ session('updated') }}</div>
        @endif
        <form method="POST" action="/admin/plan/{{ request('id')? 'update/'.request('id') : 'add' }}">
            @csrf
            <div class="form-group">
                <label class="col-form-label">{{ __('admin.plan name') }}</label>

                <input type="text" class="form-control" name="name" value="{{ request('id')? $plans[0]->name: '' }}">
            </div>

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.plan price') }}</label>

                <input type="number" class="form-control" name="price" value="{{ request('id')? $plans[0]->price: '' }}">
            </div>

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.plan points') }}</label>

                <input type="number" class="form-control" name="points" value="{{ request('id')? $plans[0]->points: '' }}">
            </div>
            
            <button type="submit" class="btn btn-primary">{{ request('id')? __('admin.update') : __('admin.save') }}</button>
        </form>
    </div>
</div>
@endsection
