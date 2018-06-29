@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">{{ __('admin.users') }}</h1>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @if(request('id'))
            <li class="nav-item">
                <a class="nav-link active" href="/admin/user">
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
                <a class="nav-link" data-toggle="pill" href="#user-add" role="tab" aria-controls="user-add" aria-selected="false">
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
                        <th>{{ __('admin.user name') }}</th>
                        <th>{{ __('admin.email') }}</th>
                        <th>{{ __('admin.phone') }}</th>
                        <th>{{ __('admin.role') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="{{ $user->suspended? 'table-danger' : '' }} toggle-on-hover">
                            <td>
                                <div><a href="?id={{ $user->id }}">{{ $user->name }}</a></div>
                                <ul class="list-inline m-0 px-0 invisible">
                                    @if($user->hasValidResume())
                                        <li class="list-inline-item">
                                            <a href="/dashboard/resume/download?r={{ $user->resume }}" class="text-muted">{{ __('front.resume download') }}</a>
                                        </li>
                                    @endif
                                    <li class="list-inline-item">
                                        <form action="/admin/user/suspend/{{ $user->id }}" onsubmit="suspend(event)">
                                            <button type="submit" class="text-muted p-0 btn btn-link btn-sm to-toggle-text">{{ $user->suspended? '取消冻结' : '冻结' }}</button>
                                        </form>
                                    </li>
                                </ul>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->role }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade {{ request('id')? 'show active' : '' }}" id="user-add" role="tabpanel" aria-labelledby="user-add-tab">
        @if(session('updated'))
            <div class="alert alert-success" role="alert">{{ session('updated') }}</div>
        @endif
        <form method="POST" action="/admin/user/{{ request('id')? 'update/'.request('id') : 'add' }}">
            @csrf
            <input type="text" class="z-9999">
            <input type="password" class="z-9999">
            <div class="form-group">
                <label class="col-form-label">{{ __('admin.user name') }}</label>

                <input type="text" class="form-control" name="name" value="{{ request('id')? $users[0]->name: '' }}">
            </div>

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.email') }}</label>

                <input type="email" class="form-control" name="email" value="{{ request('id')? $users[0]->email: '' }}">
            </div>
            @unless(request('id'))
            <div class="form-group">
                <label class="col-form-label">{{ __('admin.password') }}</label>

                <input type="password" class="form-control" name="password">
            </div>
            @endunless

            @if(auth()->user()->isMaster())
                <div class="form-group">
                    <label class="col-form-label">{{ __('admin.role') }}</label>

                    <select class="form-control{{ $errors->has('role') ? ' is-invalid' : '' }}" name="role">
                        <option value="student" {{ request('id') && ($users[0]->role == 'student')? 'selected' : '' }}>{{ __('admin.student') }}</option>
                        <option value="admin" {{ request('id') && ($users[0]->role == 'admin')? 'selected' : '' }}>{{ __('admin.admin') }}</option>
                    </select>
                </div>
            @endif

            @if(request('id') && $users[0]->resume)
                <div class="form-group">
                    <a href="/dashboard/resume/download?r={{ $users[0]->resume }}">
                        {{ __('front.resume download') }}
                    </a>
                </div>
            @endif

            <button type="submit" class="btn btn-primary">{{ request('id')? __('admin.update') : __('admin.save') }}</button>
        </form>
    </div>
</div>
@endsection

@section('script')
<script>
    function suspend(e) {
        e.preventDefault();
        $.post($(e.target).attr('action'), $(e.target).serialize(), function() {
            var tr = $(e.target).parents('tr');

            tr.toggleClass('table-danger');

            var btn = tr.find('button.to-toggle-text');
            if(btn.text() == '冻结') btn.text('取消冻结');
            else btn.text('冻结');
        });
    }

    $('tr.toggle-on-hover').mouseover(function() {
        $(this).find('ul').removeClass('invisible');
    }).mouseout(function() {
        $(this).find('ul').addClass('invisible');
    });
</script>
@endsection