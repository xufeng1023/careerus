@component('mail::message')

感谢您使用CAREERUS,请点击下面的连接完成注册。

@component('mail::button', ['url' => url('/register/verification?token='.$user->confirm_token)])
点击验证
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
