@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <ul class="list-group list-group-flush">
                @foreach($posts as $post)
                    <li class="list-group-item">
                        <h2 class="h5 m-0 font-weight-bold">
                            <a href="/job/{{ str_slug($post->title) }}?i={{ $post->identity }}">{{ $post->title }}</a>
                        </h2>
                        <div class="text-muted">{{ $post->company->name }} - {{ $post->location }}</div>
                        <div class="text-dark">{{ str_limit(strip_tags($post->description), 150) }}</div>
                        <div class="small">
                            <span data-feather="list"></span>
                            {{ $post->created_at->diffforhumans() }}
                        </div>
                    </li> 
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
