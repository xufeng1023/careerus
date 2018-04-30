@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <ul class="list-group list-group-flush">
                @forelse($posts as $post)
                    <li class="list-group-item">
                        <h2 class="h5 m-0 font-weight-bold">
                            <a href="/job/{{ str_slug($post->title) }}?i={{ $post->identity }}">{{ $post->title }}</a>
                        </h2>
                        <div>
                            <span>{{ $post->company->name }} - {{ $post->location }}</span>
                            <span class="badge badge-pill badge-secondary">
                                {{ __('front.jobs this year', ['number' => 444]) }}
                            </span>
                        </div>
                        <div class="text-muted">{{ str_limit(strip_tags($post->description), 150) }}</div>
                        <div class="small text-muted">{{ $post->created_at->diffforhumans() }}</div>
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
<script>
    new Vue({
        el: '#app'
    });
</script>
@endsection
