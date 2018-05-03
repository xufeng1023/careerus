@extends('layouts.dashboard')

@section('subcontent')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-6">
            <div class="my-3 p-3 bg-white rounded box-shadow">
        
                <h6 class="border-bottom border-gray pb-2 mb-4 d-flex justify-content-between">
                    <div><span data-feather="user"></span> {{ __('front.account info') }}</div>
                    <button type="submit" class="btn btn-primary btn-sm" form="accountForm">
                        {{ __('front.update') }}
                    </button>
                </h6>

                <form id="accountForm" method="POST" class="px-4" action="/dashboard/account" @submit="onSubmit">
                    @csrf
                    <div class="form-group row">
                        <label for="name" class="col-3 col-form-label">{{ __('front.name') }}</label>
                        <div class="col-9">
                            <input id="name" type="text" class="form-control" :class="{'is-invalid': errors.name}" name="name" value="{{ auth()->user()->name }}" required>
                            <span v-if="errors.name" v-text="errors.name[0]" class="invalid-feedback"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-3 col-form-label">{{ __('front.E-Mail Address') }}</label>
                        <div class="col-9">
                            <input id="email" type="email" class="form-control" :class="{'is-invalid': errors.email}" name="email" value="{{ auth()->user()->email }}" required>
                            <span v-if="errors.email" v-text="errors.email[0]" class="invalid-feedback"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="phone" class="col-3 col-form-label">{{ __('admin.phone') }}</label>
                        <div class="col-9">
                            <input id="phone" type="text" class="form-control" :class="{'is-invalid': errors.phone}" name="phone" value="{{ auth()->user()->phone }}" required>
                            <span v-if="errors.phone" v-text="errors.phone[0]" class="invalid-feedback"></span>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="my-3 p-3 bg-white rounded box-shadow">
        
                <h6 class="border-bottom border-gray pb-2 mb-4 d-flex justify-content-between">
                    <div><span data-feather="lock"></span> {{ __('front.password update') }}</div>
                    <button type="submit" class="btn btn-primary btn-sm" form="passForm">
                        {{ __('front.update') }}
                    </button>
                </h6>

                <form id="passForm" method="POST" class="px-4" action="/dashboard/password" @submit="onSubmit">
                    @csrf
                    <div class="form-group row">
                        <label for="old-password" class="col-3 col-form-label">{{ __('front.password old') }}</label>
                        <div class="col-9">
                            <input id="old-password" type="password" class="form-control" :class="{'is-invalid': errors.oldPass}" name="oldPass" required>
                            <span v-if="errors.oldPass" v-text="errors.oldPass" class="invalid-feedback"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="new-password" class="col-3 col-form-label">{{ __('front.password new') }}</label>
                        <div class="col-9">
                            <input id="new-password" type="password" class="form-control" :class="{'is-invalid': errors.password}" name="password" required>
                            <span v-if="errors.password" v-text="errors.password[0]" class="invalid-feedback"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password-confirm" class="col-3 col-form-label">{{ __('front.Confirm Password') }}</label>
                        <div class="col-9">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                        </div>
                    </div>
                </form>
            </div>

            <div class="my-3 p-3 bg-white rounded box-shadow">
        
                <h6 class="border-bottom border-gray pb-2 mb-4 d-flex justify-content-between">
                    <div><span data-feather="file"></span> {{ __('front.resume update') }}</div>
                    <button id="resumeFormBtn" type="submit" class="invisible" form="resumeForm">{{ __('front.update') }}</button>
                    <a title="{{ __('front.resume download') }}" href="/dashboard/resume/download?r={{ auth()->user()->resume }}">
                        <span data-feather="download"></span>
                    </a>
                </h6>

                <form id="resumeForm" method="POST" class="px-4" action="/dashboard/resume" enctype="multipart/form-data" @submit="onSubmit">
                    @csrf
                    <div class="form-group">
                        <div class="custom-file">
                            <input lang="zh" @change="onChange" type="file" name="resume" class="custom-file-input" :class="{'is-invalid': errors.resume}" id="resumeFileUpload" required>
                            <label class="custom-file-label" for="resumeFileUpload">{{ auth()->user()->maskResumeName() }}</label>
                            <div v-if="errors.resume" class="invalid-feedback" v-text="errors.resume[0]"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('js/search.js') }}"></script>
<script src="{{ asset('js/account.js') }}"></script>
@endsection
