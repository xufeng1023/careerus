<form id="applyForm" method="POST" action="{{ $user? '/apply' : '/applyRegister' }}">
    @csrf
    @include('_applyIllustration')
    <div class="form-group row">
        <label for="email2" class="col-sm-3 col-form-label text-md-right">{{ __('front.E-Mail Address') }}</label>

        <div class="col-md-7">
            <input id="email2" type="email" class="form-control" name="email" value="{{ $user? $user->email : '' }}" required {{ $user? 'disabled' : '' }}>
        </div>
    </div>

    <div class="form-group row">
        <label for="name" class="col-sm-3 col-form-label text-md-right">{{ __('front.name') }}</label>

        <div class="col-md-7">
            <input id="name" type="text" class="form-control" name="name" value="{{ $user? $user->name : '' }}" required {{ $user? 'disabled' : '' }}>
        </div>
    </div>

    @guest
    <div class="form-group row">
        <label for="password2" class="col-md-3 col-form-label text-md-right">{{ __('front.Password') }}</label>

        <div class="col-md-7">
            <input id="password2" type="password" class="form-control" name="password" required>
        </div>
    </div>

    <div class="form-group row">
        <label for="password-confirm" class="col-md-3 col-form-label text-md-right">{{ __('front.Confirm Password') }}</label>

        <div class="col-md-7">
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
        </div>
    </div>
    @endguest

    <div class="form-group row">
        <label for="resume" class="col-sm-3 col-form-label text-md-right">{{ __('front.resume') }}</label>

        <div class="col-11 col-md-6">
            <div class="custom-file">
                <input lang="zh" id="resume" type="file" class="custom-file-input" name="resume">
                <label class="custom-file-label" for="resumeFileUpload">{{ $user? $user->maskResumeName() : '' }}</label>
            </div>
        </div>
        <div class="col-1 pl-0 d-flex align-items-center justify-content-end">
            <a class="text-muted" href="/dashboard/resume/download?r={{ $user? $user->resume : '' }}" title="下载简历" download>
                <span data-feather="download"></span>
            </a>
        </div>
    </div>
    <input type="hidden" name="job" id="job">
    <input type="hidden" name="identity" id="identity">

    <div class="d-flex flex-column align-items-center">
        <button type="submit" class="btn btn-primary">申请</button>
    </div>
</form>