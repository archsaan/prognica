<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
        @yield('page-css')
    </head>
    <body>
        <section class="body">
            <!-- start: header -->
            <header class="header">
                @include('partials.header')
            </header>
            <div class="inner-wrapper">
                @yield('sidebar')
                <!-- start: page -->
                <section role="main" class="content-body">
                        @yield('content')
                </section>
            </div>
        </section>
        @include('partials.footer')
    </body>
    @yield('script')
    @yield('page-JS')
</html>
