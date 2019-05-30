<!-- Set title -->
@section('title', 'Email Verification')

@extends('layouts.auth')

@section('content')

<div class="panel panel-sign">    
    <div class="panel-body">     
        <div class="panel-header" style="text-align: center;">
            <!-- Logo -->
            {!! html_entity_decode( HTML::link("/", HTML::image("images/logo.png", "Prognica Admin", ['height' => '54']), ['class' => 'logo'] ) ) !!}
        </div>
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if (session($msg))
        <div class="alert alert-{{ $msg }}">{{ session($msg) }}</div>
        @endif
        @endforeach
        @if (session('resent'))
        <div class="alert alert-success" role="alert">
            {{ __('A fresh verification link has been sent to your email address.') }}
        </div>
        @endif
        <h3>{{ $data['heading'] }}</h3>
        <p>
            {{ $data['content'] }}
        </p>
        @if(!is_null($data['resend_link']))
        <p>{{ __('If you did not receive the email') }}, <a href="{{ url('/resend/'.$data['resend_link']) }}">{{ __('click here to request another') }}</a>.</p>
        @endif
        <div class="mb-xs text-center">
            {{ HTML::link(url('/login'),'Go to Login Page', ['class' => 'mb-xs mt-xs mr-xs btn btn-success btn-sm btn-block'])}}
        </div>
    </div>
</div>
@endsection

@section('customFooter')
<p class="text-center text-muted mt-md mb-md">{{ HTML::link("http://www.prognica.com", 'Prognica Labs',['target' => '_blank']) }} || {{ HTML::link("http://www.prognica.com/terms-of-use/", 'Terms and Condition',['target' => '_blank']) }} || {{ HTML::link("http://www.prognica.com/privacy/", 'Privacy Policy',['target' => '_blank']) }}</p>
<p class="text-center text-muted mt-md mb-md">Prognica is optimized to run on the Chrome browser.</p>
@endsection