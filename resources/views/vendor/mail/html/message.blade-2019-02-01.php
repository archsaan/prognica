@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
<!-- Logo -->
{!! HTML::image("http://www.prognica.com/app/images/logo.png", "Prognica Labs", ['height' => '54']) !!}
@endcomponent
@endslot

{{-- Body --}}
{{ $slot }}

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
<div>
    <div class="links">
        <a href="http://www.prognica.com/" style="color: gray; text-decoration: none; font-weight: bold;">Home</a> &nbsp;|&nbsp;
        <a href="{{ url('/consent') }}" style="color: gray; text-decoration: none; font-weight: bold;">Terms & Condisions</a> &nbsp;|&nbsp;
        <a href="{{ url('/consent') }}" style="color: gray; text-decoration: none; font-weight: bold;">Privacy Policy</a> &nbsp;|&nbsp;
        <a href="{{ url('/login') }}" style="color: gray; text-decoration: none; font-weight: bold;">Privacy Policy</a> 
    </div>
    <br/>
</div>
© {{ date('Y') }} {{ config('app.name') }}. @lang('PO Box 124971,Dubai,UAE')
@endcomponent
@endslot
@endcomponent
