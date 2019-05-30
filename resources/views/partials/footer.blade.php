@yield('customFooter')

@section('script')
<!-- jQuery -->
{{ HTML::script('/vendors/jquery/jquery.js') }}
{{ HTML::script('/vendors/jquery-browser-mobile/jquery.browser.mobile.js')}}
{{ HTML::script('/vendors/jquery-placeholder/jquery.placeholder.js')}}

<!-- Bootstrap -->
{{ HTML::script('/vendors/bootstrap/js/bootstrap.js')}}

<!-- vendor -->
{{ HTML::script('/vendors/nanoscroller/nanoscroller.js')}}
{{ HTML::script('/vendors/bootstrap-datepicker/js/bootstrap-datepicker.js')}}
{{ HTML::script('/vendors/magnific-popup/magnific-popup.js')}}

<!-- Head Libs -->
{{ HTML::script('/vendors/modernizr/modernizr.js') }}

<!-- Theme Base, Components and Settings -->
{{ HTML::script('/js/theme.js')}}

<!-- Theme Custom -->
{{ HTML::script('/js/theme.custom.js')}}

<!-- Theme Initialization Files -->
{{ HTML::script('/js/theme.init.js')}}

<!-- Examples -->
{{ HTML::script('/js/forms/examples.advanced.form.js') }}

<!-- Validation -->
{{ HTML::script('js/jquery.validate.min.js') }}

<!-- custom js -->
{{ HTML::script('/js/custom.js')}}

@endsection