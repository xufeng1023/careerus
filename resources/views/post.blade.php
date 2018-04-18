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
                <span class="badge badge-pill badge-secondary">{{ $post->created_at->diffforhumans() }}</span>
                <span class="badge badge-pill badge-secondary">{{ $post->catagory->name }}</span>
                <span class="badge badge-pill badge-secondary">{{ $post->is_fulltime? 'Full-time' : 'Part-time' }}</span>
            </div>
            <hr>
            <p>{!! $post->description !!}</p>

            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#applyModal">
                {{ __('front.apply') }}
            </button>

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
                                <form method="post" action="/apply">
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
                                    @if(auth()->user()->phone)
                                        <div class="form-group">
                                            <label class="col-form-label">{{ __('admin.phone') }}</label>
                                            <div>
                                                <input type="text" readonly class="form-control  form-control-sm" value="{{ auth()->user()->phone }}">
                                            </div>
                                        </div>
                                    @endif

                                    @if(auth()->user()->resume)
                                        <div class="form-group">
                                            <label class="col-form-label">{{ __('front.resume') }}</label>
                                            <div>
                                                <input type="text" readonly class="form-control  form-control-sm" value="{{ substr(auth()->user()->resume, 8) }}">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('front.points') }}</label>
                                        <div>
                                            <input type="text" readonly class="form-control  form-control-sm" value="{{ auth()->user()->points }}">
                                        </div>
                                    </div>

                                    <hr>

                                    <input type="hidden" name="job" value="{{ $post->title }}">
                                    <input type="hidden" name="identity" value="{{ $post->identity }}">
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('front.confirm') }}
                                        </button>
                                        <small id="passwordHelpInline" class="text-muted">
                                            {{ __('front.consume points', ['points' => 20]) }}
                                        </small>
                                    </div>
                                </form>
                            @else
                                <form method="POST" action="/applyRegister" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group alert alert-primary" role="alert">
                                        {{ __('front.free alert') }}
                                    </div>

                                    <div class="form-group">
                                        <label for="name" class="col-form-label">{{ __('front.name') }}</label>
                                        <div>
                                            <input id="name" type="text" class="form-control  form-control-sm" name="name" value="{{ old('name') }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="email" class="col-form-label">{{ __('front.E-Mail Address') }}</label>
                                        <div>
                                            <input id="email" type="email" class="form-control  form-control-sm" name="email" value="{{ old('email') }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="phone" class="col-form-label">{{ __('admin.phone') }}</label>
                                        <div>
                                            <input id="phone" type="text" class="form-control  form-control-sm" name="phone" value="{{ old('phone') }}" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="col-form-label">{{ __('front.Password') }}</label>
                                        <div>
                                            <input id="password" type="password" class="form-control  form-control-sm" name="password" required>
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
                                        <div>
                                            <input type="file" id="resume" name="resume">
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
