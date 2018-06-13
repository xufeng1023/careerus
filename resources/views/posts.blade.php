@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-3">
            <button class="btn btn-light btn-block d-block d-sm-none mb-2" type="button" data-toggle="collapse" data-target="#collapseFilter" aria-expanded="false" aria-controls="collapseFilter">
                <span data-feather="search"></span>
            </button>
            <div class="collapse d-sm-block" id="collapseFilter">
                <div class="mb-3">
                    <div>{{ __('admin.job type') }}</div>
                    @foreach($types as $type)
                    <a href="/jobs?s={{ request('s') }}&ct={{ request('ct') }}&tp={{ $type }}&l={{ request('l') }}&t={{ request('t') }}" class="badge badge-light">{{ $type }}</a>
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
                        <a href="/jobs?s={{ request('s') }}&ct={{ $cat }}&tp={{ request('tp') }}&l={{ request('l') }}&t={{ request('t') }}" class="badge badge-light">{{ $cat }}</a>
                    @endforeach
                </div>

                <!-- <div class="mb-3">
                    <div>{{ __('admin.job location') }}</div>
                    {{-- @foreach($locations as $location) --}}
                        <a href="/jobs?s={{ request('s') }}&ct={{ request('ct') }}&tp={{ request('tp') }}&l={{-- {{ $location }} --}}&t={{ request('t') }}" 
                        class="badge badge-light">{{-- {{ $location }} --}}</a>
                        {{-- @endforeach --}}
                </div> -->
            </div>
        </div>

        <div class="col-sm-6">
            <div class="mb-3">
                @foreach(collect(request()->all())->except('page')->filter() as $key => $param)
                    <a href="{{ str_replace($key.'='.request($key), $key.'=', urldecode(url()->full())) }}" class="badge badge-pill badge-secondary">
                        {{ $param }} <span class="badge badge-light">X</span>
                    </a>
                @endforeach
            </div>
            <ul class="list-group list-group-flush box-shadow mb-3">
                @forelse($posts as $key => $post)
                    <li class="list-group-item {{ $key? '' : 'border-0' }}">
                        <h2 class="h6 m-0">
                            <a href="/job/{{ str_slug($post->title) }}?i={{ $post->identity }}">{{ $post->title }}</a>
                        </h2>
                        <div class="d-flex justify-content-between">
                            <small>{{ $post->company->name }} - {{ $post->location }}</small>
                            <small>{{ $post->sponsor_odds }}% sponsor odds</small>
                        </div>
                        <div class="text-muted h6 job-excerpt">{{ str_limit(html_entity_decode(strip_tags($post->description)), 120) }}</div>
                        <div class="small text-muted">{{ $post->created_at->diffforhumans() }}</div>
                    </li> 
                @empty
                    <div class="alert alert-light mb-0" role="alert">
                        {{ __('front.no job found') }}
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
