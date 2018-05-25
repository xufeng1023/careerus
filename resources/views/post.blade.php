@extends('layouts.app')

@section('title')
{{ $post->title.'-' }}
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="h3">{{ $post->title }}</h1>
            <div class="text-muted">{{ $post->created_at->diffforhumans() }} - {{ $post->company->name }} - {{ $post->location }}</div>
            <div class="mb-3">
                <span class="badge badge-pill badge-secondary">{{ $post->catagory->name }}</span>
                <span class="badge badge-pill badge-secondary">{{ $post->job_type }}</span>
                @foreach($post->tags as $tag)
                    <span class="badge badge-pill badge-secondary">{{ $tag->name }}</span>
                @endforeach
            </div>
            <div class="mb-3">
                <div class="text-primary">{{ __('front.we guess sponsor odds') }}{{ $post->sponsor_odds.'%' }}</div>
                <div class="progress" style="height: 3px;">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $post->sponsor_odds }}%" aria-valuenow="{{ $post->sponsor_odds }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div id="jobPageLeft" class="col-md-7">
            <div>{!! $post->description !!}</div>

            <div class="my-3">
                <a href="{{ $post->url ?: $post->company->website }}" class="btn btn-primary">
                    {{ __('front.jump to apply') }}
                </a>

                @auth
                    @if($apply = auth()->user()->isApplied($post->id))
                        <div class="text-muted">*{{ __('front.applied already', ['time' => $apply->created_at->diffForHumans()]) }}</div>
                    @else
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#applyModal">
                            {{ __('front.apply') }}
                        </button>
                    @endif
                @else
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#applyModal">
                        {{ __('front.apply') }}
                    </button>
                @endauth
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
                                <form method="POST" action="/applyRegister" enctype="multipart/form-data" @submit="onSubmit">
                                    @csrf
                                    <!-- <div class="form-group alert alert-primary" role="alert">
                                        {{ __('front.free alert', ['times' => 5]) }}
                                    </div> -->

                                    <div class="form-group">
                                        <label for="name" class="col-form-label pt-0">{{ __('front.name') }}</label>
                                        <div>
                                            <input id="name" type="text" class="form-control" :class="{'is-invalid': errors.name}" name="name" value="{{ old('name') }}" required>
                                            <span v-if="errors.name" v-text="errors.name[0]" class="invalid-feedback"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="col-form-label">{{ __('front.E-Mail Address') }}</label>
                                        <div>
                                            <input id="email" type="email" class="form-control" :class="{'is-invalid': errors.email}" name="email" value="{{ old('email') }}" required>
                                            <span v-if="errors.email" v-text="errors.email[0]" class="invalid-feedback"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone" class="col-form-label">{{ __('admin.phone') }}</label>
                                        <div>
                                            <input id="phone" type="text" class="form-control" :class="{'is-invalid': errors.phone}" name="phone" value="{{ old('phone') }}" required>
                                            <span v-if="errors.phone" v-text="errors.phone[0]" class="invalid-feedback"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="col-form-label">{{ __('front.Password') }}</label>
                                        <div>
                                            <input id="password" type="password" class="form-control" :class="{'is-invalid': errors.password}" name="password" required>
                                            <span v-if="errors.password" v-text="errors.password[0]" class="invalid-feedback"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password-confirm" class="col-form-label">{{ __('front.Confirm Password') }}</label>
                                        <div>
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="resume" class="col-form-label">{{ __('front.resume') }}</label>
                                        <div class="custom-file">
                                            <input @change="onChange" type="file" name="resume" class="custom-file-input" :class="{'is-invalid': errors.resume}" id="resumeFileUpload" required>
                                            <label class="custom-file-label" for="resumeFileUpload"></label>
                                            <div v-if="errors.resume" class="invalid-feedback" v-text="errors.resume[0]"></div>
                                        </div>
                                    </div>

                                    <input type="hidden" name="job" value="{{ $post->title }}">
                                    <input type="hidden" name="identity" value="{{ $post->identity }}">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('front.apply and register') }}
                                    </button>
                                </form>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="jobPageRight" class="col-md-5">
            @if(count($post->company->visaJobs))
                <div class="card border-light mb-3">
                    <div class="card-header">{{ __('front.chart title') }}</div>
                    <div class="card-body">
                        <chart :data-set="{{ $post->company->visaJobs }}"></chart>
                    </div>
                </div>
            @endif

            @if($post->company->jobs)
                <div class="card border-light mb-3">
                    <div class="card-header">{{ __('front.chart title') }}</div>
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
                                        <a href="/job/{{ $job->title }}?i={{ $job->identity }}">{{ $job->title }}</a>
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
@endsection
