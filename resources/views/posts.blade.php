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
                    </div>
                </div>
                <div class="row">
                    <div class="col-auto">地点:</div>
                    <div class="col pl-0">
                        @foreach($locations as $location)
                            <a href="/jobs?s={{ request('s') }}&ct={{ request('ct') }}&tp={{ request('tp') }}&l={{ $location->simplified_name }}" 
                            class="badge {{ request('l') == $location->simplified_name? 'badge-dark' : '' }}">{{ $location->simplified_name }}</a>
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

            {!! $posts->appends(request()->all())->links() !!}

            <ul class="list-group list-group-flush box-shadow mb-3">
                @forelse($posts as $key => $post)
                    <li class="list-group-item {{ $key? '' : 'border-top-0' }}">
                        <h2 class="h6 m-0">
                            <a href="{{ $post->link() }}">{{ $post->chinese_title ?: $post->title }}</a>
                        </h2>
                        <div class="d-flex justify-content-between">
                            <small>{{ $post->company->name }} - {{ $post->location }}</small>
                            <small>{{ $post->sponsor_odds }}% sponsor odds</small>
                        </div>
                        <div class="text-muted h6 job-excerpt">{{ str_limit(html_entity_decode(strip_tags($post->chinese_description? $post->chinese_description : $post->description)), 120) }}</div>
                        <div class="d-flex justify-content-between">
                            <div class="small text-muted">{{ $post->created_at->diffforhumans() }}</div>
                            <div class="small text-danger">{{ $post->availibility }}</div>
                        </div>
                    </li> 
                @empty
                    <div class="alert alert-light mb-0" role="alert">
                        {{ __('front.no job found') }}
                        @if(request('s'))
                            {!! __('front.no job found s', ['value' => request('s')]) !!}
                        @endif
                        @if(request('tp'))
                            {!! __('front.no job found tp', ['value' => request('tp')]) !!}
                        @endif
                        @if(request('ct'))
                            {!! __('front.no job found ct', ['value' => request('ct')]) !!}
                        @endif
                        @if(request('l'))
                            {!! __('front.no job found l', ['value' => request('l')]) !!}
                        @endif
                        {{ __('front.no job found2') }}
                    </div>
                @endforelse
            </ul>

            {!! $posts->appends(request()->all())->links() !!}
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/search.js') }}"></script>
@endsection
