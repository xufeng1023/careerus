<form id="searchForm" action="/jobs" autocomplete="off">
    <div class="input-group d-flex justify-content-center">
        <job class="w-25" v-model="searchJob" default-job="{{ request('s') }}" placeholder="{{ __('front.search job title') }}"></job>
        <location class="w-25" v-model="searchLocation" default-location="{{ request('l') }}" placeholder="{{ __('admin.job location') }}"></location>
        <div class="input-group-append">
            <button class="btn border-0 text-white" type="submit">
                <span data-feather="search"></span>
            </button>
        </div>
    </div>
</form>