@extends('layouts.app')

@section('title')
{{ $post->title.'-' }}
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-7">
            <h1 class="h2">{{ $post->title }}</h1>
            <div class="text-muted">{{ $post->company->name }} - {{ $post->location }}</div>
            <div>
                <span class="badge badge-pill badge-secondary">{{ $post->created_at->diffforhumans() }}</span>
                <span class="badge badge-pill badge-secondary">{{ $post->catagory->name }}</span>
                <span class="badge badge-pill badge-secondary">{{ $post->is_fulltime? 'Full-time' : 'Part-time' }}</span>
            </div>
            <chart :data-set="{{ $post->company->visaJobs }}"></chart>
            <hr>
            <p>{!! $post->description !!}</p>

            <div class="mb-1">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#applyModal">
                    {{ __('front.apply') }}
                </button>
            </div>

            @if($apply = auth()->user()->isApplied($post->id))
                <div class="text-muted">*{{ __('front.applied already', ['time' => $apply->created_at->diffForHumans()]) }}</div>
            @endif

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
                                @if(auth()->user()->apply_counts < 5)
                                    <div class="alert alert-success">{{ __('front.free times left', ['times' => 5]) }}</div>
                                @endif
                                <form method="post" action="/apply" @submit="onSubmit">
                                    @csrf
                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('front.name') }}</label>
                                        <div>
                                            <input type="text" readonly class="form-control  form-control-sm" value="{{ auth()->user()->name }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('admin.email') }}</label>
                                        <div>
                                            <input type="text" readonly class="form-control  form-control-sm" value="{{ auth()->user()->email }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('admin.phone') }}</label>
                                        <div>
                                            <input type="text" readonly class="form-control  form-control-sm" value="{{ auth()->user()->phone }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('front.resume') }}</label>
                                        <div>
                                            <input type="text" readonly class="form-control  form-control-sm" value="{{ auth()->user()->maskResumeName() }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('front.points') }}</label>
                                        <div>
                                            <input type="text" readonly class="form-control  form-control-sm" :class="{'is-invalid': errors.points}" value="{{ auth()->user()->points }}">
                                            <span v-if="errors.points" v-text="errors.points" class="invalid-feedback"></span>
                                        </div>
                                    </div>

                                    <hr>

                                    <input type="hidden" name="job" value="{{ $post->title }}">
                                    <input type="hidden" name="identity" value="{{ $post->identity }}">
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('front.confirm') }}
                                        </button>
                                        <small id="passwordHelpInline" class="text-muted {{ (auth()->user()->apply_count < 5)? 'del':'' }}">
                                            {{ __('front.consume points', ['points' => 20]) }}
                                        </small>
                                    </div>
                                </form>
                            @else
                                <form method="POST" action="/applyRegister" enctype="multipart/form-data" @submit="onSubmit">
                                    @csrf
                                    <div class="form-group alert alert-primary" role="alert">
                                    {{ __('front.free alert', ['times' => 5]) }}
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="col-form-label">{{ __('front.name') }}</label>
                                        <div>
                                            <input id="name" type="text" class="form-control  form-control-sm" :class="{'is-invalid': errors.name}" name="name" value="{{ old('name') }}" required>
                                            <span v-if="errors.name" v-text="errors.name[0]" class="invalid-feedback"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="col-form-label">{{ __('front.E-Mail Address') }}</label>
                                        <div>
                                            <input id="email" type="email" class="form-control  form-control-sm" :class="{'is-invalid': errors.email}" name="email" value="{{ old('email') }}" required>
                                            <span v-if="errors.email" v-text="errors.email[0]" class="invalid-feedback"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone" class="col-form-label">{{ __('admin.phone') }}</label>
                                        <div>
                                            <input id="phone" type="text" class="form-control  form-control-sm" :class="{'is-invalid': errors.phone}" name="phone" value="{{ old('phone') }}" required>
                                            <span v-if="errors.phone" v-text="errors.phone[0]" class="invalid-feedback"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="col-form-label">{{ __('front.Password') }}</label>
                                        <div>
                                            <input id="password" type="password" class="form-control  form-control-sm" :class="{'is-invalid': errors.password}" name="password" required>
                                            <span v-if="errors.password" v-text="errors.password[0]" class="invalid-feedback"></span>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password-confirm" class="col-form-label">{{ __('front.Confirm Password') }}</label>
                                        <div>
                                            <input id="password-confirm" type="password" class="form-control  form-control-sm" name="password_confirmation" required>
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

                                    <hr>
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
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<script type="text/x-template" id="chart-template">
    <canvas id="myChart" width="400" height="200"></canvas>
</script>

<script>
    Vue.component('chart', {
        props: ['dataSet'],
        data() {
            return {
                bars: ''
            }
        },
        template: '#chart-template',
        mounted: function() {
            console.log(this.dataSet);
            var newData = this.dataSet.filter(function(key, val) {
                return key == 'year'
            });
            console.log(newData);
            var ctx = document.getElementById("myChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                    datasets: [{
                        label: '# of Votes',
                        data: [12, 19, 3, 5, 2, 3],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255,99,132,1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero:true
                            }
                        }]
                    }
                }
            });
        }
    });


    const app = new Vue({
        el: '#app',
        data: {
            errors: ''
        },
        methods: {
            onSubmit(e) {
                e.preventDefault();
                let uri = e.target.getAttribute('action');
                let fd = new FormData(e.target);
                $.ajax(uri, {
                    type: 'post',
                    context: this,
                    data: fd,
                    processData: false,
                    contentType: false,
                    error(data) {
                        this.errors = JSON.parse(data.responseText).errors;
                    },
                    success(data) {
                        location.assign(data);
                    }
                });
            },
            onChange(e) {
                let file = e.target.files[0];
                $(e.target).siblings('label').text(file.name);
            }
        }
    });
</script>
@endsection
