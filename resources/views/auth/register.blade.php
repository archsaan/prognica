<!-- Set title -->
@section('title', 'Register')

@extends('layouts.auth')

@section('content')
<!-- Logo -->
{!! html_entity_decode( HTML::link("/", HTML::image("images/logo-w.png", "Prognica Admin", ['height' => '54']), ['class' => 'logo pull-left'] ) ) !!}
<div class="panel panel-sign">
    <div class="panel-title-sign mt-xl text-right">
        <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> Register</h2>
    </div>
    <div class="panel-body">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if (session($msg))
        <div class="alert alert-{{ $msg }}">{{ session($msg) }}</div>
        @endif
        @endforeach        
        {{ FORM::open(array('url' => 'create', 'method' => 'post', 'id' => 'RegisterForm'))}} 
        <div class="form-group mb-lg">
            <div class="input-group input-group-icon">
                {{ FORM::email('email', old('email'), ['class' => 'form-control input-lg', 'placeholder' => 'Email Address', 'id' => 'email', 'required' => 'true', 'CustomEmail' => 'true', 'autocomplete'=>"false"]) }}
                @if ($errors->has('email'))
                <span class="invalid-feedback alert-danger" role="alert">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif                
            </div>
            <label id="email-error" class="error" for="email" style="display: inline;"></label>
        </div>

        <div class="form-group mb-lg">
            <div class="input-group input-group-icon">
                {{ FORM::password('password', ['class' => 'form-control input-lg', 'placeholder' => 'Password', 'id' => 'password', 'required' => 'true', 'minlength' => '8']) }}
                @if ($errors->has('password'))
                <span class="invalid-feedback alert-danger" role="alert">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
            <label id="password-error" class="error" for="password" style="display:inline;"></label>
        </div>

        <div class="form-group mb-lg">
            <div class="input-group input-group-icon">
                {{ FORM::password('confirm_password', ['class' => 'form-control input-lg', 'placeholder' => 'Confirm Password', 'id' => 'confirm_password', 'required' => 'true', 'equalTo' => '#password']) }}
                @if ($errors->has('confirm_password'))
                <span class="invalid-feedback alert-danger" role="alert">
                    <strong>{{ $errors->first('confirm_password') }}</strong>
                </span>
                @endif
            </div>
            <label id="confirm_password-error" class="error" for="confirm_password" style="display:inline;"></label>
        </div>
        <div class="form-group mb-lg">
            <div class="input-group input-group-icon">
                {{ FORM::text('name', old('name'), ['class' => 'form-control input-lg', 'placeholder' => 'Full Name', 'id' => 'name', 'required' => 'true', 'inputCheck' => '[a-zA-Z\s]+']) }}
                @if ($errors->has('name'))
                <span class="invalid-feedback alert-danger" role="alert">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
                @endif
            </div>
            <label id="name-error" class="error" for="name" style="display:inline;"></label>
        </div>
        <div class="form-group mb-lg">
            <div class="input-group input-group-icon">
                {{ FORM::text('mobile', old('mobile'), ['class' => 'form-control input-lg', 'placeholder' => 'Mobile Number', 'id' => 'phone', 'required' => 'true', 'PhoneCheck' => '([0-9]{10})|(\([0-9]{3}\)\s+[0-9]{3}\-[0-9]{4})']) }}
                @if ($errors->has('mobile'))
                <span class="invalid-feedback alert-danger" role="alert">
                    <strong>{{ $errors->first('mobile') }}</strong>
                </span>
                @endif
            </div>
            <label id="mobile-error" class="error" for="mobile" style="display:inline;"></label>
        </div>
        {{ FORM::select('organisation_id', $organisation, old('organisation_id'), ['class' => 'form-control input-sm mb-md', 'id' => 'organisation_id', 'required' => 'true'])}}
        @if ($errors->has('organisation_id'))
        <span class="invalid-feedback alert-danger" role="alert">
            <strong>{{ $errors->first('organisation_id') }}</strong>
        </span>
        @endif
        <label id="organisation_id-error" class="error" for="organisation_id" style="display:inline;"></label>
        <div class="mb-xs text-center">
            {{ FORM::submit('Register', ['class' => 'mb-xs mt-xs mr-xs btn btn-primary btn-sm btn-block']) }}
        </div>
        <div class="mb-xs text-center">
            {{ HTML::link(url('/login') , 'Cancel', ['class' => 'mb-xs mt-xs mr-xs btn btn-danger btn-sm btn-block']) }}
        </div>
        {{ FORM::close() }}
    </div>
    @endsection