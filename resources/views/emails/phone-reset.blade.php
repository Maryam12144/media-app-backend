@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
<h2 style="font-size: 24px;">
Reset Phone Number
</h2>

<p>
You recently requested to reset your phone number to <strong>{{ $phoneNumber }}</strong> on Kanact Media. Please click on the link below to complete the reset process, otherwise you can just ignore this email.
</p>

@component('mail::button', ['url' => $url])
    Reset Phone
@endcomponent

<p style="margin-top: 16px;">
This link will be valid for <strong>{{ isset($expiry) ? $expiry : \Labs\Core\Entities\User\PhoneReset::DEFAULT_EXPIRY_IN_MINUTES }}</strong> minutes.
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
