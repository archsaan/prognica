<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- Meta, title, CSS, favicons, etc. -->
<meta name="keywords" content="Prognica, Breast Cancer" />
<meta name="description" content="AI based healthtech company">
<meta name="author" content="okler.net">
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<title> @if(View::hasSection('title')) @yield('title') @endif | {{ config('app.name') }} </title>

<link rel="shortcut icon" href="{{ asset('images/favicon.ico')  }}" />

<!-- Mobile Metas -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

<!-- Web Fonts  -->
{{ HTML::style('http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Shadows+Into+Light') }}

<!-- Bootstrap -->
{{ HTML::style('/vendors/bootstrap/css/bootstrap.css') }}
<!-- Font Awesome -->
{{ HTML::style('/vendors/font-awesome/css/font-awesome.css') }}

<!-- Custom Theme Style -->
{{ HTML::style('/css/theme.css') }}

<!-- Skin CSS -->
{{ HTML::style('/css/skins/default.css') }}

<!-- Theme Custom CSS -->
{{ HTML::style('/css/theme-custom.css')}}


