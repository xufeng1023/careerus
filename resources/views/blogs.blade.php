@extends('layouts.app')

@section('title')
{{ '美国求职攻略 -' }}
@endsection

@section('description')
<meta name="description" content="这里有相当实用的美国求职攻略和心得体会供大家阅读,希望对每个人以后在美国找工作都能有所帮助.">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1 class="h3 mb-3">原创美国求职攻略</h1>
            <ul class="list-group list-group-flush">
                @forelse($blogs as $blog)
                    <li class="list-group-item">
                        <h2 class="h5 m-0 font-weight-bold">
                            <a href="/blog/{{ $blog->title }}">{{ $blog->title }}</a>
                        </h2>
                        <div class="small text-muted">{{ $blog->created_at->diffforhumans() }}</div>
                    </li> 
                @empty
                    <div class="alert alert-light" role="alert">
                        {{ __('front.no job found', ['location' => request('l'), 'title' => request('s')]) }}
                    </div>
                @endforelse
            </ul>
            <video width="320" height="568" controls></video>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    navigator.mediaDevices.getUserMedia({
        video: true
    })
.then(function(stream) {
    var video = document.querySelector('video');
  video.srcObject = stream;
  video.onloadedmetadata = function(e) {
    video.play();
  };

.catch(function(err) {
    console.log(err.name + ": " + err.message);
});
</script>
@endsection