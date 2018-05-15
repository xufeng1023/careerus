<form id="searchForm" class="mb-3" action="/jobs" autocomplete="off">
    <div class="input-group d-flex justify-content-center">
        <job class="w-25" default="{{ request('s') }}" placeholder="{{ __('front.search job title') }}"></job>
        <location class="w-25" default="{{ request('l') }}" placeholder="{{ __('admin.job location') }}"></location>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary rounded-right border-search" type="submit">
                <span data-feather="search"></span>
            </button>
        </div>
    </div>
</form>
