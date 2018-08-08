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