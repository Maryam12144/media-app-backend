@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
<h2 style="font-size: 24px;">
Email Verification
</h2>

<p>
You're recently registered on  Kanact Media.
</p>
<p style="line-height: 0.1em;">Your email verification pin is:</p>

<div style="text-align: center;
/*background-color: #eaeaea;*/
border-radius: 8px;
padding: 10px 4px;
width: 100%;">
<h3 style="letter-spacing: 5px;
display: inline-block;
/*color: #106EE8;*/
margin-bottom: 0;
font-weight: bold;
font-size: 30px">
{{ $pin }}
</h3>
</div>

<p style="margin-top: 16px;">
This pin will be valid for <strong>{{ isset($expiry) ? $expiry : '24' }}</strong> hours.
</p>

<p style="margin-top: 32px">
If you have any questions or requests, we're here for you.
</p>

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
@endcomponent
@endslot
@endcomponent
