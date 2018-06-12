@component('mail::message')

<strong>Dear Sir or Madam,</strong>

<strong>Re: Application for a Position as {{ $post->title }}</strong><br>

Your recent job posting for a <strong>{{ $post->title }}</strong> position has captured my serious attention. I am very interested in this position because my background appears to match your needs. Attached is my resume for your consideration. 

I believe that my unique background and skills would be a good fit in your team. Your consideration of my qualifications would be greatly appreciated. The chance to meet with you would be a privilege and a pleasure! 

Thank you very much for your time and consideration.

Sincerely,<br>
{{ auth()->user()->name }}<br>
{{ auth()->user()->phone }}<br>
{{ auth()->user()->email }}
@endcomponent