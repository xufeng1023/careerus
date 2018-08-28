@extends('layouts.admin')

@section('style')
<link href="{{ asset('css/editor.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
    <h1 class="h2">{{ __('admin.blog') }}</h1>
    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        @if(request('id'))
            <li class="nav-item">
                <a class="nav-link active" href="/admin/blog">
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
                <a class="nav-link" data-toggle="pill" href="#company-add" role="tab" aria-controls="company-add" aria-selected="false">
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
                <tbody>
                    @foreach($blogs as $blog)
                        <tr>
                            <td><a href="?id={{ $blog->id }}">{{ str_limit(strip_tags($blog->title), 60) }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade {{ request('id')? 'show active' : '' }}" id="company-add" role="tabpanel" aria-labelledby="company-add-tab">
        @if(session('updated'))
            <div class="alert alert-success" role="alert">{{ session('updated') }}</div>
        @endif
        <form method="POST" onsubmit="onSubmit(event)" class="mb-3" action="/admin/blog/{{ request('id')? 'update/'.request('id') : 'add' }}">
            @csrf

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.blog title') }}</label>

                <input type="text" class="form-control" name="title" value="{{ request('id')? $blogs[0]->title: '' }}">
            </div>

            <div class="form-group">
                <label class="col-form-label">SEO描述(155字内)</label>

                <textarea class="form-control" name="description" rows="3">{{ request('id')? $blogs[0]->description: '' }}</textarea>
                <small class="form-text text-muted"></small>
            </div>

            <div class="form-group">
                <label class="col-form-label">{{ __('admin.content') }}</label>

                <div id="editor" data-content="{{ request('id')? $blogs[0]->content : '' }}"></div>
            </div>

            <button type="submit" class="btn btn-primary">{{ request('id')? __('admin.update') : __('admin.save') }}</button>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/editor.js') }}"></script>
<script>
    var oldContent = document.querySelector('#editor').dataset.content;
    if(oldContent) {
        window.Quill.setContents(JSON.parse(oldContent));
    }

    $('textarea[name=description]').on('input', function() {
        var numbers = $(this).val().length;
        if(numbers > 0) $(this).next().text(numbers+'字');
        else $(this).next().text('');
    });

    function onSubmit(e) {
        e.preventDefault();
        var change = new Delta();
        var content = window.Quill.getContents();

        $.ajax(e.target.getAttribute('action'), {
            type: 'post',
            data: $(e.target).serialize() + '&content=' + JSON.stringify(content),
            success: function(data) {
                if(data) location.assign(data);
                else toastr.success('更新成功!');
            }
        });
    }
</script>
@endsection
