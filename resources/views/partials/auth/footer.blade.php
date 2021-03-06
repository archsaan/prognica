@yield('customFooter')
<p class="text-center text-muted mt-md mb-md">&copy; Copyright {{ Carbon\Carbon::now()->year }}. All rights reserved.</p>

<div class="clearfix"></div>

@section('script')
<!-- jQuery -->
{{ HTML::script('/vendors/jquery/jquery.js') }}
{{ HTML::script('/vendors/jquery-browser-mobile/jquery.browser.mobile.js')}}
{{ HTML::script('/vendors/jquery-placeholder/jquery.placeholder.js')}}

<!-- Bootstrap -->
{{ HTML::script('/vendors/bootstrap/js/bootstrap.js')}}
<!-- vendor -->
{{ HTML::script('/vendors/nanoscroller/nanoscroller.js')}}

<!-- Head Libs -->
{{ HTML::script('/vendors/modernizr/modernizr.js') }}

<!-- Theme Base, Components and Settings -->
{{ HTML::script('/js/theme.js')}}

<!-- Theme Custom -->
{{ HTML::script('/js/theme.custom.js')}}

<!-- Theme Initialization Files -->
{{ HTML::script('/js/theme.init.js')}}
<!-- Validation -->
{{ HTML::script('https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js') }}

<!-- custom js -->
{{ HTML::script('/js/custom.js')}}
@endsection