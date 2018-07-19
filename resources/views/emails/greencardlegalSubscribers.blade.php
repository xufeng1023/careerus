@component('mail::message')
# {{ parse_url($url, PHP_URL_HOST) }}提醒您：

绿卡排期已经更新到{{ date('F, Y') }}。

@component('mail::button', ['url' => $url.'/绿卡排期'])
前去查看
@endcomponent

@endcomponent