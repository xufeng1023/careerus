@component('mail::message')

{!! $coverLetter? $coverLetter->content : '' !!}

Thanks,<br>
{{ config('app.name') }}
@endcomponent