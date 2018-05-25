@component('mail::message')

Hello {{ $post->company->hr }},

{!! $coverLetter? $coverLetter->content : '' !!}

Thank you very much,<br>
{{ auth()->user()->name }}
@endcomponent