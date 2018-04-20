<form action="/jobs" autocomplete="off">
    <div class="input-group">
        <input type="text" name="s" class="form-control" placeholder="{{ __('front.search job title') }}">
        <location placeholder="{{ __('admin.job location') }}"></location>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="submit">{{ __('front.search') }}</button>
        </div>
    </div>
</form>
