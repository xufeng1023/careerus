@component('mail::message')
# {{ __('admin.good news') }}

{{ __('admin.has been applied', ['job' => $apply->post->title]) }}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
