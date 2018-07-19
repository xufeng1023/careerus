@extends('layouts.app')

@section('title')
{{ request('l') && $locationTitleChinese? $locationTitleChinese.'工作-' : '' }}
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-3 order-sm-1 order-1">
            <button class="btn btn-light btn-block d-block d-sm-none mb-2" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                <span data-feather="search"></span>
            </button>
            <div class="collapse d-sm-block" id="collapseFilter">
                <div class="mb-3">
                    <div>{{ __('admin.job type') }}</div>
                    @foreach($types as $type)
                    <a href="/jobs?s={{ request('s') }}&ct={{ request('ct') }}&tp={{ $type }}&l={{ request('l') }}" class="badge badge-light">{{ $type }}</a>
                    @endforeach
                </div>
                
                <!-- <div class="mb-3">
                    <div>{{ __('front.job features') }}</div>
                    {{--  @foreach($usedTags as $tag) --}}
                        <a href="/jobs?s={{ request('s') }}&ct={{ request('ct') }}&tp={{ request('tp') }}&l={{ request('l') }}&t={{-- {{ $tag->name }} --}}" class="badge badge-light">{{-- {{ $tag->name }} --}}</a>
                        {{-- @endforeach --}}
                </div> -->

                <div class="mb-3">
                    <div>{{ __('front.job category') }}</div>
                    @foreach($categories as $cat)
                        <a href="/jobs?s={{ request('s') }}&ct={{ $cat }}&tp={{ request('tp') }}&l={{ request('l') }}" class="badge badge-light">{{ $cat }}</a>
                    @endforeach
                </div>

                <!-- <div class="mb-3">
                    <div>{{ __('admin.job location') }}</div>
                    {{-- @foreach($locations as $location) --}}
                        <a href="/jobs?s={{ request('s') }}&ct={{ request('ct') }}&tp={{ request('tp') }}&l={{-- {{ $location }} --}}" 
                        class="badge badge-light">{{-- {{ $location }} --}}</a>
                        {{-- @endforeach --}}
                </div> -->
            </div>
        </div>

        <div class="col-sm-6 order-sm-2 order-2">
            <div class="mb-3">
                @foreach(collect(request()->all())->except('page')->filter() as $key => $param)
                    <a href="{{ str_replace($key.'='.$param, $key.'=', urldecode(url()->full())) }}" class="badge badge-pill badge-secondary">
                    {{ __('front.'.$key) }}:{{ $param }} <span class="badge badge-light">X</span>
                    </a>
                @endforeach
            </div>
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
                        <div class="small text-muted">{{ $post->created_at->diffforhumans() }}</div>
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
