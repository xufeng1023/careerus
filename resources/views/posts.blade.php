@extends('layouts.app')

@section('title')
{{ request('l') && $locationTitleChinese? $locationTitleChinese.'工作-' : '' }}
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-8 ">
            <button class="btn btn-light btn-block d-block d-sm-none mb-2" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                <span data-feather="search"></span>
            </button>
            <div class="collapse d-sm-block" id="collapseFilter">
                <div id="filter" class="p-3">
                    <div class="mb-1 row">
                        <div class="col-auto">类别:</div>
                        <div class="col pl-0">
                            @foreach($types as $type)
                                <a href="/jobs?s={{ request('s') }}&ct={{ request('ct') }}&tp={{ $type }}&l={{ request('l') }}" 
                                class="badge {{ request('tp') == $type? 'badge-dark' : '' }}">{{ $type }}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-1 row">
                        <div class="col-auto">领域:</div>
                        <div class="col pl-0">
                            @foreach($categories as $cat)
                                <a href="/jobs?s={{ request('s') }}&ct={{ $cat }}&tp={{ request('tp') }}&l={{ request('l') }}" 
                                class="badge {{ request('ct') == $cat? 'badge-dark' : '' }}">{{ $cat }}</a>
                            @endforeach
                            <a href="/jobs?s={{ request('s') }}&ct=其他&tp={{ request('tp') }}&l={{ request('l') }}" 
                                class="badge {{ request('ct') == '其他'? 'badge-dark' : '' }}">其他</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-auto">地点:</div>
                        <div class="col pl-0">
                            @foreach($locations as $key => $location)
                                <a href="/jobs?s={{ request('s') }}&ct={{ request('ct') }}&tp={{ request('tp') }}&l={{ $key }}" 
                                class="badge {{ request('l') == $key? 'badge-dark' : '' }}">{{ $location }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
       
            <div class="my-2">
                @foreach(collect(request()->all())->except('page')->filter() as $key => $param)
                    <a href="{{ str_replace($key.'='.$param, $key.'=', urldecode(url()->full())) }}" class="badge badge-pill badge-secondary">
                    {{ __('front.'.$key) }}:{{ $param }} <span class="badge badge-light">X</span>
                    </a>
                @endforeach
            </div>
            
            <ul id="post-list" class="list-group list-group-flush box-shadow mb-3"></ul>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/search.js') }}"></script>
<script>
    
    const ul = $('#post-list');
    const dates = [];
    var offset = 0;
    var timeout;
    var isEnd = false;

    fetch();

    function fetch() {
        if(isEnd) return;
        $.ajax('/fetch/post'+location.search, {
            data: {offset:offset},
            error: function(data) {
                isEnd = true;
                ul.append('<div class="alert alert-light mb-0 rounded-0" role="alert">'+data.responseText+'</div>');
            },
            success: function(data) {
                offset = offset + data.length;
                data.forEach(function(post, index) {
                    if(!dates.includes(post.created_at)) {
                        dates.push(post.created_at);
                        ul.append('<div class="alert m-0 timeline">'+post.chineseDate+'</div>');
                    }
                    var li = '<li class="list-group-item">';
                    li += '<h2 class="h6 m-0">';
                    li += '<a href="'+post.path+'">'+post.showTitle+'</a>';
                    li += '</h2>';
                    li += '<div class="d-flex justify-content-between">';
                    li += '<small>'+post.company.name+' - '+post.location+'</small>';
                    li += '<small>'+post.sponsor_odds+'% sponsor odds</small>';
                    li += '</div>';
                    li += '<div class="text-muted h6 job-excerpt">'+post.excerpt+'</div>';
                    li += '<div class="d-flex justify-content-between">';
                    li += '<div class="small text-muted">'+post.posted_at+'</div>';
                    li += '<div class="small text-danger">'+post.availibility+'</div>';
                    li += '</div>';
                    li += '</li>';
                    ul.append(li);
                });
            },
            complete: function() {
                $(window).scroll(function() {
                    if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                        clearTimeout(timeout);
                        timeout = setTimeout(() => {
                            fetch();
                        }, 60);
                    }
                });
            }
        });
    }
</script>
@endsection
