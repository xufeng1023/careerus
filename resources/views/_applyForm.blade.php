<form id="applyForm" method="POST" action="{{ $user? '/apply' : '/applyRegister' }}">
    @csrf
    <div class="alert alert-secondary d-flex justify-content-between align-items-center flex-column flex-lg-row flex-wrap">
        <div class="d-flex flex-column align-items-center mb-3">
            <img src="{{ asset('images/apply1.png') }}" width="136" alt="">
            <div>点击右侧申请</div>
            <div>立即锁定HR内推名额</div>
        </div>
        <div class="d-none d-lg-flex flex-column justify-content-center h1 text-white m-0">&#8674;</div>
        <div class="d-flex d-lg-none flex-column justify-content-center h1 text-white m-0">&#8675;</div>
        <div class="d-flex flex-column align-items-center mb-3">
            <img src="{{ asset('images/apply2.png') }}" width="136" alt="">
            <div>系统审核通过</div>
            <div>24小时之后直达HR邮箱</div>
        </div>
        <div class="d-none d-lg-flex flex-column justify-content-center h1 text-white m-0">&#8674;</div>
        <div class="d-flex d-lg-none flex-column justify-content-center h1 text-white m-0">&#8675;</div>
        <div class="d-flex flex-column align-items-center">
            <img src="{{ asset('images/apply3.png') }}" width="136" alt="">
            <div>HR内推名额限时限量</div>
            <div>早早申请，让HR最先看到你！</div>
        </div>
    </div>

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

        <div class="col-md-7">
            <div class="custom-file">
                <input lang="zh" id="resume" type="file" class="custom-file-input" name="resume">
                <label class="custom-file-label" for="resumeFileUpload">{{ $user? $user->maskResumeName() : '' }}</label>
            </div>
        </div>
    </div>
    <input type="hidden" name="job" id="job">
    <input type="hidden" name="identity" id="identity">

    <div class="d-flex flex-column align-items-center">
        <button type="submit" class="btn btn-primary">申请</button>
    </div>
</form>