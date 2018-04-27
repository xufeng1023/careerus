<form action="/jobs" autocomplete="off">
    <div class="input-group">
        <input type="text" value="{{ request('s') }}" name="s" class="form-control border-right-0" placeholder="{{ __('front.search job title') }}">
        <location default="{{ request('l') }}" placeholder="{{ __('admin.job location') }}"></location>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary rounded-right border-search" type="submit">
                <span data-feather="search"></span>
            </button>
        </div>
    </div>
</form>
