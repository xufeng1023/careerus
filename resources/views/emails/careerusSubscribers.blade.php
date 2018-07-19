@component('mail::message')
# {{ parse_url($url, PHP_URL_HOST) }}

绿卡排期更新{{ (date('Y-F')) }}。

{{ $url.'/绿卡排期' }}

@endcomponent