<!DOCTYPE html>
<html lang="{{ App::currentLocale() }}">
    <head>
        <title>Login</title>
        <meta name="description" content="Raindo App - Reinovasi" />
        <meta name="keywords" content="Raindo App - Reinovasi" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta charset="utf-8" />
        <meta property="og:locale" content="en_US" />
        <meta property="og:type" content="article" />
        <meta property="og:title" content="Quotation TBINA MKT" />

        {{-- global stylesheet bundle --}}
        <link rel="stylesheet" href="{{ asset('assets/plugins/global/plugins.bundle.css') }}">
        {{-- theme stylesheet --}}
        <link rel="stylesheet" href="{{ asset('assets/lte/css/adminlte.css') }}">
        {{-- select2 stylesheet --}}
        <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
        {{-- iziToast --}}
        <link rel="stylesheet" href="{{ asset('assets/plugins/izitoast/dist/css/iziToast.min.css') }}">
        {{-- DataTable stylesheet --}}
        <link rel="stylesheet" href="{{ asset('assets/css/datatable.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/amr.css') }}">
        {{-- global javascript bundle --}}
        <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
        <script src="{{ asset('assets/lte/js/scripts.bundle.js') }}"></script>

        <style>
            .bg-primary-blue {
                background: #2F5CCF;
                color: #fff;
            }
        </style>

        @yield('styles')
    </head>
    <body class="hold-transition login-page">

		<!-- Main content -->
		@yield('content')

        {{-- jquery --}}
        <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
        {{-- Bootstrap --}}
        <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        {{-- adminlte --}}
        <script src="{{ asset('assets/lte/js/adminlte.js') }}"></script>
        {{-- select2 --}}
        <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
        {{-- iziToast --}}
        <script src="{{ asset('assets/plugins/izitoast/dist/js/iziToast.min.js') }}"></script>
        {{-- Show message alert from session flash --}}
		@include('adminLte.helpers.message-alert')

        @yield('scripts')

    </body>
</html>
