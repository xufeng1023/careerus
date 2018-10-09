@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">爬公众号</h1>
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
                        <th>名称</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($crawlBlogs as $user)
                        <tr class="{{ $user->suspended? 'table-danger' : '' }} toggle-on-hover">
                            <td>{{ $user->name }}</td>
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
        <form method="POST" action="/admin/crawlBlogCategories/{{ request('id')? 'update/'.request('id') : 'add' }}">
            @csrf
            <input type="text" class="z-9999">
            <input type="password" class="z-9999">
            <div class="form-group">
                <label class="col-form-label">名称</label>

                <input type="text" class="form-control" name="name" value="{{ request('id')? $crawlBlogs[0]->name: '' }}">
            </div>
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