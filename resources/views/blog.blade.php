@extends('layouts.app')

@section('title')
{{ '求职攻略-'.$blog->title.'-' }}
@endsection


@section('style')
<link href="{{ asset('css/editor.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-7">
            <h1 class="h2">{{ $blog->title }}</h1>
            <div>
                <span class="badge badge-pill badge-secondary">{{ $blog->created_at->diffforhumans() }}</span>
            </div>
            <hr>
            <div id="editor" data-content="{{ $blog->content }}"></div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/search.js') }}"></script>
<script src="{{ asset('js/blog.js') }}"></script>
<script>
    var oldContent = document.querySelector('#editor').dataset.content;
    if(oldContent) {
        window.Quill.setContents(JSON.parse(oldContent));
    }
</script>
@endsection