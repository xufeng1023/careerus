@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            @auth
                <form action="POST">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{ __('front.name') }}</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" value="{{ auth()->user()->name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{ __('admin.email') }}</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" value="{{ auth()->user()->email }}">
                        </div>
                    </div>
                    @if(auth()->user()->profile->phone)
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{ __('admin.phone') }}</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" value="{{ auth()->user()->profile->phone }}">
                            </div>
                        </div>
                    @endif

                    @if(auth()->user()->profile->resume)
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">{{ __('front.resume') }}</label>
                            <div class="col-sm-10">
                                <input type="text" readonly class="form-control-plaintext" value="{{ auth()->user()->profile->resume }}">
                            </div>
                        </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">{{ __('front.points') }}</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" value="{{ auth()->user()->points }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6 offset-sm-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('front.apply') }}
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <form method="POST" action="">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-md-2 col-form-label">{{ __('front.name') }}</label>

                        <div class="col-md-6">
                            <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-2 col-form-label">{{ __('front.E-Mail Address') }}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password" class="col-md-2 col-form-label">{{ __('front.Password') }}</label>

                        <div class="col-md-6">
                            <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                            @if ($errors->has('password'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-2 col-form-label">{{ __('front.Confirm Password') }}</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-md-2 col-form-label">{{ __('front.resume') }}</label>
                        <div class="col-md-6">
                            <input type="file">
                        </div>
                    </div>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('front.apply and register') }}
                            </button>
                        </div>
                    </div>
                </form>
            @endauth
        </div>
    </div>
</div>
@endsection
