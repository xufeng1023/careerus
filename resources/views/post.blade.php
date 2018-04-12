@extends('layouts.app')

@section('title')
{{ $post->title.'-' }}
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>{{ $post->title }}</h1>
            <div class="text-muted">{{ $post->company->name }} - {{ $post->location }}</div>
            <div>
                {{ $post->created_at->diffforhumans() }}
                {{ $post->catagory->name }}
                {{ $post->is_fulltime? 'Full-time' : 'Part-time' }}
            </div>
            <hr>
            <p>{!! $post->description !!}</p>
            <form method='post' action="/apply">
                @csrf
                <input type="hidden" name="title" value="{{ $post->title }}">
                <input type="hidden" name="title" value="{{ $post->identity }}">
                <button class="btn btn-primary" type="submit">{{ __('front.apply') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
