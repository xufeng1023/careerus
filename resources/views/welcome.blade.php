@extends('layouts.app')

@section('title')
{{ '提供H1B工作查找并且有HR内推机会 - ' }}
@endsection

@section('content')
    <div class="container">
        <div class="row mt-4">
            <div class="col-sm-4 mb-5">
                <h2 class="h4 text-muted d-flex align-items-center">
                    <span data-feather="grid"></span><small>&nbsp;工作领域</small>
                    <a href="/jobs?s=&l=" class="ml-auto h6 m-0">>>></a>
                </h2><hr>
                <div class="row">
                    @foreach($categories as $category)
                        <div class="col-3">
                            <a href="/jobs?ct={{ $category }}" class="d-block">{{ $category }}</a>
                        </div>
                    @endforeach
                </div><br>
                <h2 class="h4 text-muted d-flex align-items-center">
                    <span data-feather="map-pin"></span><small>&nbsp;地点</small>
                    <a href="/jobs?s=&l=" class="ml-auto h6 m-0">>>></a>
                </h2><hr>
                <div class="row">
                    @foreach($locations as $lo)
                        <div class="col-4">
                            <a href="/jobs?l={{ $lo->simplified_name }}" class="d-block">{{ $lo->simplified_name }}</a>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-sm-8">
                <div id="carouselExampleControls" class="carousel slide mb-5" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img class="d-block w-100" src="{{ asset('images/home-banner.jpg') }}" alt="First slide">
                        </div>
                        <div class="carousel-item">
                            <img class="d-block w-100" src="{{ asset('images/home-banner2.jpg') }}" alt="Second slide">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
        </div>

        <h2 class="h4 text-muted d-flex align-items-center">
            <span data-feather="list"></span><small>&nbsp;最新工作</small>
            <a href="/jobs?s=&l=" class="ml-auto h6 m-0">>>></a>
        </h2><hr>

        <div class="row">
            @foreach($newJobs as $key => $job)
                <div class="col-sm-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <a href="{{ $job->link() }}">
                                <h5 class="card-title mb-0">{{ $job->chinese_title ?: $job->title }}</h5>
                            </a>
                            <p class="card-text"><small class="text-muted">{{ $job->location.'-'.$job->company->name }}</small></p>
                            <p class="card-text">{{ str_limit(html_entity_decode(strip_tags($job->chinese_description? $job->chinese_description : $job->description)), 120) }}</p>
                            <p class="card-text d-flex justify-content-between">
                                <small class="text-muted">发布于{{ $job->posted_at }}</small>
                                <small class="text-danger">{{ $job->availibility }}</small>
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('script')
<script src="{{ asset('js/search.js') }}"></script>
@endsection
