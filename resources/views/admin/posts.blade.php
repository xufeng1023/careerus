@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">{{ __('admin.job list') }}</h1>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @if(request('id'))
            <li class="nav-item">
                <a class="nav-link active" href="/admin/jobs">
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
                <a class="nav-link" data-toggle="pill" href="#job-add" role="tab" aria-controls="job-add" aria-selected="false">
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
                        <th>标题</th>
                        <th>管理员</th>
                        <th>添加日期</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td><a href="?id={{ $post->id }}">{{ $post->title }}</a></td>
                            <td>{{ $post->creator->name }}</td>
                            <td>{{ $post->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade {{ request('id')? 'show active' : '' }}" id="job-add" role="tabpanel" aria-labelledby="job-add-tab">
        @if(session('updated'))
            <div class="alert alert-success" role="alert">{{ session('updated') }}</div>
        @endif
        <form method="POST" action="/admin/post/{{ request('id')? 'update/'.request('id') : 'add' }}">
            @csrf
            <div class="form-group">
                <label class="col-form-label">{{ __('admin.job title') }}</label>

                <input type="text" class="form-control" name="title" value="{{ request('id')? $posts[0]->title: '' }}">
            </div>

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.job desc') }}</label>

                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.2/trix.css">
                <script src="https://cdnjs.cloudflare.com/ajax/libs/trix/0.11.2/trix.js"></script>
                <input id="description" type="hidden" name="description" value="{{ request('id')? $posts[0]->description: '' }}">
                <trix-editor input="description"></trix-editor>
            </div>

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.company') }}</label>

                <select class="form-control" name="company_id">
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('id') && ($posts[0]->company_id == $company->id)? 'selected' : '' }}>{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>{{ __('admin.catagory') }}</label>
                <select class="form-control" name="catagory_id">
                    @foreach($catagories as $catagory)
                        <option value="{{ $catagory->id }}" {{ request('id') && ($posts[0]->catagory_id == $catagory->id)? 'selected' : '' }}>{{ $catagory->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.job location') }}</label>

                <input type="text" class="form-control{{ $errors->has('location') ? ' is-invalid' : '' }}" name="location" value="{{ old('location') ?: 'New York, NY' }}">
            </div>

            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="is_fulltime" id="radio-fulltime" value="1" {{ request('id') && ($posts[0]->is_fulltime == 1)? 'checked' : '' }} required>
                    <label class="form-check-label" for="radio-fulltime">Full Time</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="is_fulltime" id="radio-parttime" value="0" {{ request('id') && ($posts[0]->is_fulltime == 0)? 'checked' : '' }} required>
                    <label class="form-check-label" for="radio-parttime">Part Time</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">{{ request('id')? __('admin.update') : __('admin.save') }}</button>
        </form>
    </div>
</div>
@endsection
