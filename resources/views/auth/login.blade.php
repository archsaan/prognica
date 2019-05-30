<!-- Set title -->
@section('title', 'Login')

@extends('layouts.auth')

@section('content')
<!-- Logo -->
{!! html_entity_decode( HTML::link("/", HTML::image("images/logo-w.png", "Prognica Admin", ['height' => '54']), ['class' => 'logo pull-left'] ) ) !!}

<div class="panel panel-sign">
    <div class="panel-title-sign mt-xl text-right">
        <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Login</h2>
    </div>
    <div class="panel-body">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if (session($msg))
                <div class="alert alert-{{ $msg }}">{{ session($msg) }}</div>
            @endif
        @endforeach
        {{ FORM::open(array('url' => 'login', 'methos' => 'post', 'id' => 'loginForm')) }}
            <div class="form-group mb-lg">
                <div class="input-group input-group-icon">
                    {{ FORM::email('email', old('email'), ['class' => 'form-control input-lg', 'placeholder' => 'Email Address', 'required' => 'true', 'CustomEmail' => 'true']) }}
                    <span class="input-group-addon">
                        <span class="icon icon-lg">
                            <i class="fa fa-user"></i>
                        </span>
                    </span>
                </div>
            </div>

            <div class="form-group mb-lg">
                <div class="input-group input-group-icon">
                    {{ FORM::password('password', ['class' => 'form-control input-lg', 'placeholder' => 'Password', 'required'=> 'true']) }}
                    <span class="input-group-addon">
                        <span class="icon icon-lg">
                            <i class="fa fa-lock"></i>
                        </span>
                    </span>
                </div>
            </div>

            <div class="clearfix">
                {{ HTML::link(url('/lost_password'), 'Lost Password?', ['class' => 'pull-left']) }}
            </div>

            <div class="mb-xs text-center">
                {{ FORM::submit('Login', ['class' => 'mb-xs mt-xs mr-xs btn btn-primary btn-sm btn-block'])}}
            </div>

            <span class="mt-lg mb-lg line-thru text-center text-uppercase">
                <span>or</span>
            </span>

            <div class="mb-xs text-center">
                {{ HTML::link(url('/consent'),'Register', ['class' => 'mb-xs mt-xs mr-xs btn btn-success btn-sm btn-block'])}}
            </div>

        {{ FORM::close() }}
    </div>
</div>
@endsection

@section('customFooter')
<p class="text-center text-muted mt-md mb-md">{{ HTML::link("http://www.prognica.com", 'Prognica Labs',['target' => '_blank']) }} || {{ HTML::link("http://www.prognica.com/terms-of-use/", 'Terms and Condition',['target' => '_blank']) }} || {{ HTML::link("http://www.prognica.com/privacy/", 'Privacy Policy',['target' => '_blank']) }}</p>
<p class="text-center text-muted mt-md mb-md">Prognica is optimized to run on the Chrome browser.</p>
@endsection