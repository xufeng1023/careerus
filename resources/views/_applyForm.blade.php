<form id="applyForm" method="POST" action="{{ $user? '/apply' : '/applyRegister' }}">
    @csrf
    <div class="alert alert-secondary">
        新申请的工作会经过审核后，在转天的中午前发送到HR的邮箱，同时申请者会收到邮件通知。
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
                <input lang="zh" id="resume" type="file" class="custom-file-input" name="resume" required {{ $user? 'disabled' : '' }}>
                <label class="custom-file-label" for="resumeFileUpload">{{ $user? $user->maskResumeName() : '' }}</label>
            </div>
        </div>
    </div>


    <div class="d-flex flex-column align-items-center">
        <button type="submit" class="btn btn-primary">申请</button>
    </div>
</form>