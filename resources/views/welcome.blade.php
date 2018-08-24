@extends('layouts.app')

@section('title')
{{ '提供H1B工作查找并且有HR内推机会 - ' }}
@endsection

@section('description')
<meta name="description" content="每日都有最新的H1B工作介绍,尤其是在纽约和加州这种华人公司较多的大城市,并且可直接向HR递交简历申请并有机会得到内推,同时还有不断更新的原创美国求职攻略可参考.">
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
                    @foreach($categories->take(9) as $category)
                        <div class="col-4">
                            <a href="/jobs?ct={{ $category }}" title="{{ $category }}" class="d-block">{{ $category }}</a>
                        </div>
                    @endforeach
                </div><br>
                <h2 class="h4 text-muted d-flex align-items-center">
                    <span data-feather="map-pin"></span><small>&nbsp;地点</small>
                    <a href="/jobs?s=&l=" class="ml-auto h6 m-0">>>></a>
                </h2><hr>
                <div class="row">
                    @foreach($locations as $key => $lo)
                        <div class="col-4">
                            <a href="/jobs?l={{ $key }}" title="{{ $lo }}" class="d-block">{{ $lo }}</a>
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
                    <div class="card mb-5">
                        <div class="card-header">
                            <div class="justify-content-between d-flex align-items-center">
                                <h5 class="card-title m-0" title="{{ $job->showTitle }}">{{ str_limit($job->showTitle, 20) }}</h5>
                                <a href="#" class="btn btn-success btn-sm">点选直申
                                <div style="width:15px;height:15px;border-radius:50%;" class="bg-white d-inline-flex justify-content-center align-items-center text-success">&#10004;</div>
                                </a>
                            </div>
                            
                            <div>
                                @foreach($job->tags as $tag)
                                    <span class="badge badge-pill badge-secondary">{{ $tag->name }}</span>
                                @endforeach
                            </div>

                            <div class="justify-content-between d-flex align-items-center">
                                <small class="text-muted">{{ $job->posted_at }}</small>
                            </div>
                        </div>

                        <div class="card-body">
                            <p class="card-text">{{ $job->excerpt }}</p>
                            <div>{{ html_entity_decode($job->company->name) }}</div>
                            <div class="small d-flex justify-content-between">
                                <span>地点: <span class="text-secondary">{{ $job->company->state }}</span></span>
                                <span>规模: <span class="text-secondary">{{ $job->company->scale }}</span></span>
                                <span>2017 H1B: <span class="text-secondary">{{ $job->company->totalSponsor }}人</span></span>
                            </div>
                        </div>

                        <div class="card-footer d-flex align-items-center justify-content-around">
                            <button type="button" class="btn btn-sm p-0 btn-light border-0 icon heart"></button>
                            <button type="button" class="btn btn-sm p-0 btn-light border-0 icon wechat"></button>
                            <button type="button" class="btn btn-sm p-0 btn-light border-0 icon website"></button>
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
