@component('mail::message')
    # Type of Inquiry or Feedback : {{$contact_type}}

    {{$message}}

    Thanks,
    {{ config('app.name') }}
@endcomponent