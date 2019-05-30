<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.auth.head')
    </head>
    <body style="background-color:#0B293D">
        <!-- start: page -->
        <section class="body-sign">
            <div class="center-sign">
                @yield('content')
                
                @include('partials.auth.footer')
            </div>
        </section>
    </body>
    @yield('script')
</html>
