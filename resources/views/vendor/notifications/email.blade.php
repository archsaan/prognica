@component('mail::message')
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

@endcomponent
