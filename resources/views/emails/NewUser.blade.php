@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<!-- Logo -->
{!! HTML::image("http://www.prognica.com/app/images/logo.png", "Prognica Labs", ['height' => '54']) !!}
@endcomponent
@endslot

{{-- Body --}}
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# @lang('Hello!')
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
switch ($level) {
    case 'success':
    case 'error':
        $color = $level;
        break;
    default:
        $color = 'primary';
}
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
@lang(
"If you’re having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
'into your web browser: [:actionURL](:actionURL)',
[
'actionText' => $actionText,
'actionURL' => $actionUrl,
]
)
@endcomponent
@component('mail::subcopy')
@lang("If you have any issue with your account, please dont hesitate to contact us by replying to this email.")
@endcomponent
@endisset

{{-- table --}}
@isset($user)
@component('mail::table')
<table border="0">
    <tr>
        <th style="text-align: right">Name: </th>
        <th style="font-weight: normal; text-align: left;">{{$user->profile->name}}</td>
    </tr>
    <tr>
        <th style="text-align: right">Email Address:</th>
        <th style="font-weight: normal; text-align: left;">{{$user['email']}}</td>
    </tr>
    <tr>
        <th style="text-align: right">Mobile Number:</th>
        <th style="font-weight: normal; text-align: left;">{{ $user->profile->mobile }}</td>
    </tr>
    <tr>
        <th style="text-align: right">Organization:</th>
        <th style="font-weight: normal; text-align: left;">{{$user->profile->organisation->organisation_name}}</td>
    </tr>
</table>
@endcomponent
@endisset

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
<br/>
@lang('Thank you'),<br>@lang('Team Prognica')
@endif

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
<div>
    <div class="links">
        <a href="http://www.prognica.com/" style="color: gray; text-decoration: none; font-weight: bold;">Home</a> &nbsp;|&nbsp;
        <a href="http://www.prognica.com/contact-us/" style="color: gray; text-decoration: none; font-weight: bold;">Support</a> &nbsp;|&nbsp;
        <a href="http://www.prognica.com/terms-of-use/" style="color: gray; text-decoration: none; font-weight: bold;">Terms & Conditions</a> &nbsp;|&nbsp;
        <a href="http://www.prognica.com/privacy/" style="color: gray; text-decoration: none; font-weight: bold;">Privacy Policy</a> &nbsp;|&nbsp;
        <a href="{{ url('/login') }}" style="color: gray; text-decoration: none; font-weight: bold;">Login</a>
    </div>
    <br/>
</div>
© {{ date('Y') }} {{ config('app.name') }}. @lang('PO Box 124971,Dubai,UAE')
@endcomponent
@endslot
@endcomponent
