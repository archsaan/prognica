<!-- Set title -->
@section('title', 'Select an option')

@extends('layouts.default')
<!-- this page CSS -->
@section('page-css')
<style type="text/css">
    body{
        background-color:#0B293D 
    }
    .pricing-table{
        width: 100%;
    }
</style>
@endsection

@section('content')
<!-- start: page -->
<section>
    <div class="row">
        <div class="col-xs-10 col-xs-push-1">
            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if (session($msg))
            <div class="alert alert-{{ $msg }}">{{ session($msg) }}</div>
            @endif
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="pricing-table">
            <div class="col-md-12">
                <div class="col-lg-3 col-sm-6">
                    <div class="plan">
                        <h3>Ultrasound</h3>
                        <a href="{{ url('/dashboard') }}" >
                            {{ HTML::image('images/prd_ultrasound.png', 'Ultrasound', array('height'=>'200'))}}
                        </a>
                        <br/><br/>
                        {{ HTML::link(url('/dashboard'), 'Select', ['class' => 'btn btn-lg btn-primary']) }}
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="plan">
                        <h3>Thermogram</h3>                        
                        {{ HTML::image('images/prd_thermo.png', 'Thermogram', array('height'=>'200'))}}                        
                        <br/><br/>
                        <div data-toggle="tooltip" data-placement="bottom" title="Coming Soon">
                            {{ HTML::link("javascript:void(0);", 'Select', ['class' => 'btn btn-lg btn-primary disabled', 'title' => 'Coming Soon']) }}                        
                        </div>                         
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="plan">
                        <h3>Mammogram</h3>
                        {{ HTML::image('images/prd_mammo.png', 'Mammogram', array('height'=>'200'))}}
                        <br/><br/>
                        <div data-toggle="tooltip" data-placement="bottom" title="Coming Soon">
                            {{ HTML::link("javascript:void(0);", 'Select', ['class' => 'btn btn-lg btn-primary disabled', 'title' => 'Coming Soon']) }}                        
                        </div> 
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6">
                    <div class="plan">
                        <h3>Biopsy</h3>
                        {{ HTML::image('images/prd_biopsy.png', 'Biopsy', array('height'=>'200'))}}
                        <br/><br/>
                        <div data-toggle="tooltip" data-placement="bottom" title="Coming Soon">
                            {{ HTML::link("javascript:void(0);", 'Select', ['class' => 'btn btn-lg btn-primary disabled', 'title' => 'Coming Soon']) }}                        
                        </div> 
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>
<!-- end: page -->
@endsection

<!-- this page JS -->
@section('page-JS')

@endsection