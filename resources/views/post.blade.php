@extends('layouts.app')

@section('title')
{{ ($post->chinese_title? $post->chinese_title : $post->title).'-' }}
@endsection

@section('description')
<meta name="description" content="{{ $post->excerpt }}">
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <h1 id="job-title" class="h3">{{ $post->chinese_title ?: $post->title }}</h1>
            <div class="text-muted">{{ $post->created_at->diffforhumans() }} - {{ $post->company->name }} - {{ $post->location }}</div>
            <div class="mb-3">
                <span class="badge badge-pill badge-secondary">{{ $post->catagory->name }}</span>
                <span class="badge badge-pill badge-secondary">{{ $post->job_type }}</span>
                @foreach($post->tags as $tag)
                    <span class="badge badge-pill badge-secondary">{{ $tag->name }}</span>
                @endforeach
            </div>
            <div class="mb-3">
                <div>{{ __('front.we guess sponsor odds') }}{{ $post->sponsor_odds.'%' }}</div>
                <div class="progress" style="height:1px;">
                    <div class="progress-bar bg-primary" role="progressbar" data-width="{{ $post->sponsor_odds }}%"
                        aria-valuenow="{{ $post->sponsor_odds }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="jobPageLeft" class="col-md-7">
            <div class="my-3">
                @include('_applyBtn', ['post' => $post])
            </div>
            
            <div>
                <ul class="nav nav-tabs mb-3" id="contentTab" role="tablist">
                    @if($post->chinese_description)
                        <li class="nav-item">
                            <a class="nav-link {{ $post->chinese_description? 'active' : '' }}" id="zh-desc-tab" data-toggle="tab" href="#zh-desc" role="tab" aria-controls="zh-desc" aria-selected="true">中文</a>
                        </li>
                    @endif

                    @if($post->description)
                    <li class="nav-item">
                        <a class="nav-link {{ $post->chinese_description? '' : 'active' }}" id="en-desc-tab" data-toggle="tab" href="#en-desc" role="tab" aria-controls="en-desc" aria-selected="false">English</a>
                    </li>
                    @endif

                    @auth
                        <li class="ml-auto nav-item">
                            <form action="/job/favorite/toggle/{{ $post->id }}" method="post" onsubmit="toggleFavorite(event)" 
                            class="{{ auth()->user()->isFavorited($post->id)? 'filled' : '' }}"
                            >
                                <button type="submit" class="btn btn-link">
                                    <span data-feather="star"></span>
                                </button>
                            </form>
                        </li>
                    @endauth
                </ul>
                <div class="tab-content" id="myTabContent">
                    @if($post->chinese_description)
                        <div class="tab-pane fade {{ $post->chinese_description? 'show active' : '' }}" id="zh-desc" role="tabpanel" aria-labelledby="zh-desc-tab">
                            {!! $post->cleanedDescription('zh') !!}
                        </div>
                    @endif
                    <div class="tab-pane fade {{ $post->chinese_description? '' : 'show active' }}" id="en-desc" role="tabpanel" aria-labelledby="en-desc-tab">
                        {!! $post->cleanedDescription() !!}
                    </div>
                </div>
            </div>

            <div class="my-3">
                @include('_applyBtn', ['post' => $post])
            </div>
            
            <div class="modal fade" id="applyModal" tabindex="-1" role="dialog" aria-labelledby="applyModal" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('front.job apply') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            @auth
                                <!-- @if(auth()->user()->apply_counts < 5)
                                    <div class="alert alert-success">{{ __('front.free times left', ['times' => 5]) }}</div>
                                @endif -->
                                <form method="post" action="/apply" @submit="onSubmit">
                                    @csrf
                                    <div class="form-group">
                                        <label class="col-form-label pt-0">{{ __('front.name') }}</label>
                                        <div>
                                            <input type="text" readonly class="form-control" value="{{ auth()->user()->name }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('admin.email') }}</label>
                                        <div>
                                            <input type="text" readonly class="form-control" value="{{ auth()->user()->email }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('admin.phone') }}</label>
                                        <div>
                                            <input type="text" readonly class="form-control" value="{{ auth()->user()->phone }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('front.resume') }}</label>
                                        <div>
                                            <input type="text" readonly class="form-control" :class="{'is-invalid': errors.resume}" value="{{ auth()->user()->maskResumeName() }}">
                                            <span v-if="errors.resume" v-text="errors.resume" class="invalid-feedback"></span>
                                        </div>
                                    </div>

                                    <!-- <div class="form-group">
                                        <label class="col-form-label">{{ __('front.points') }}</label>
                                        <div>
                                            <input type="text" readonly class="form-control" :class="{'is-invalid': errors.points}" value="{{ auth()->user()->points }}">
                                            <span v-if="errors.points" v-text="errors.points" class="invalid-feedback"></span>
                                        </div>
                                    </div>

                                    <hr> -->

                                    <input type="hidden" name="job" value="{{ $post->title }}">
                                    <input type="hidden" name="identity" value="{{ $post->identity }}">
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('front.confirm') }}
                                        </button>
                                        <!-- <small id="passwordHelpInline" class="text-muted {{ (auth()->user()->apply_count < 5)? 'del':'' }}">
                                            {{ __('front.consume points', ['points' => 20]) }}
                                        </small> -->
                                    </div>
                                </form>
                            @else
                                @include('_loginForm')
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="jobPageRight" class="col-md-5">
            @if(count($post->company->visaJobs))
                <div class="card border-light mb-3">
                    <div class="card-header">{{ __('front.bar chart title') }}</div>
                    <div class="card-body">
                        <chart :data-set="{{ $post->company->visaJobs }}"></chart>
                    </div>
                </div>
            @endif

            @if($post->company->jobs)
                <div class="card border-light mb-3">
                    <div class="card-header">{{ __('front.pie chart title') }}</div>
                    <div class="card-body">
                        <pie-chart data-set="{{ $post->company->jobs }}"></pie-chart>
                    </div>
                </div>
            @endif

            @if($post->company->posts()->count() > 1)
                <div class="card border-light">
                    <div class="card-header">{{ __('front.company other jobs', ['company' => $post->company->name]) }}</div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @foreach($post->company->posts as $job)
                                @if($post->id != $job->id)
                                    <li>
                                        <a href="{{ $job->link() }}">{{ $job->chinese_title ?: $job->title }}</a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/search.js') }}"></script>
<script src="{{ asset('js/chart.js') }}"></script>
<script src="{{ asset('js/pie.js') }}"></script>
<script>
    let progressBarWidth = $('.progress-bar').data('width');
    $('.progress-bar').css({'animation': 'widthGrow 1500ms','width': progressBarWidth});

    function toggleFavorite(e) {
        e.preventDefault();
        var feedback = e.target.classList.contains('filled')? '关注已被取消。' : '已添加到关注列表。';
        $.post(e.target.getAttribute('action'), [], function() {
            e.target.classList.toggle('filled');
            toastr.success(feedback);
        });
    }
</script>

<script type="application/ld+json"> {
  "@context" : "http://schema.org/",
  "@type" : "JobPosting",
  "title" : "{{ $post->chinese_title? $post->chinese_title : $post->title }}",
  "description" :"{{ str_limit(html_entity_decode(strip_tags($post->chinese_description? $post->chinese_description : $post->description)), 120) }}",
  "identifier": {
    "@type": "PropertyValue",
    "name": "{{ $post->company->name }}"
  },
  "datePosted" : "{{ $post->created_at->format('Y-m-d') }}",
  "employmentType" : "{{ $post->job_type }}",
  "hiringOrganization" : {
    "@type" : "Organization",
    "name" : "{{ $post->company->name }}",
    "sameAs" : "{{ $post->url? $post->url : $post->company->website }}"
  },
  "jobLocation" : {
    "@type" : "Place",
    "address" : {
      "@type" : "PostalAddress",
      @php
        $location = explode(',', $post->location);
      @endphp
      "addressLocality" : "{{ $location[0] }}",
      "addressRegion" : "{{ isset($location[1]) ? $location[1] : $location[0] }}"
    }
  }
}
</script>
@endsection
