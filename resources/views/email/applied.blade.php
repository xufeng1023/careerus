@component('mail::message')
# 您好，{{ auth()->user()->name }}

您刚刚成功的申请了{{ $post->title }}的工作。

@component('mail::button', ['url' => url('/job/'.$post->title.'?i='.$post->identity)])
点击查看
@endcomponent

祝您好运,<br>
{{ config('app.name') }}
@endcomponent
