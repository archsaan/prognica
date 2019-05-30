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
<h1 style='text-align: center;'>
    @if (! empty($greeting))
    {{ $greeting }} 
    @else
    @if ($level === 'error')
    # @lang('Whoops!')
    @else
    # @lang('Hello!')
    @endif
    @endif
</h1>

{{-- Intro Lines --}}
@foreach ($introLines as $line)
<p style="text-align: center; margin-left: 35px; margin-right: 35px;">
{{ $line }}
</p>
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
<p style="text-align: center; margin-left: 35px; margin-right: 35px;">
    @lang("Or paste this link into your browser:\n") <br/>
    @lang(
    ':actionURL',
    [
    'actionText' => $actionText,
    'actionURL' => $actionUrl,
    ]
    )
</p>
@endcomponent
@component('mail::subcopy')
<p style="text-align: center; margin-left: 35px; margin-right: 35px;">
    @lang("If you have any issue with your account, please dont hesitate to contact us by replying to this email.")
</p>
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
