@component('mail::message')
# {{ parse_url($url, PHP_URL_HOST) }}

绿卡排期更新{{ \Carbon\Carbon::now()->addMonth()->format('Y-F') }}。

{{ $url.'/绿卡排期' }}

@endcomponent