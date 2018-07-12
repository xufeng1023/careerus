@extends('layouts.app')

@section('title')
{{ '求职攻略 -' }}
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <ul class="list-group list-group-flush">
                @forelse($blogs as $blog)
                    <li class="list-group-item">
                        <h2 class="h5 m-0 font-weight-bold">
                            <a href="/求职攻略/{{ $blog->title }}">{{ $blog->title }}</a>
                        </h2>
                        <div class="small text-muted">{{ $blog->created_at->diffforhumans() }}</div>
                    </li> 
                @empty
                    <div class="alert alert-light" role="alert">
                        {{ __('front.no job found', ['location' => request('l'), 'title' => request('s')]) }}
                    </div>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/search.js') }}"></script>
@endsection
