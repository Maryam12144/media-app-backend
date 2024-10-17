@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
{{ config('app.name') }}
@endcomponent
@endslot

{{-- Body --}}
<h2 style="font-size: 24px;">
Welcome!
</h2>


<div style="text-align: center;
/*background-color: #eaeaea;*/
border-radius: 8px;
padding: 10px 4px;
width: 100%;">

</div>

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
