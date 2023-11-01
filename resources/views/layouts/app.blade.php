<!DOCTYPE html>
<html lang="en" @if (isRTL() == true) dir="rtl" @endif>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Xfortech" />
    <link rel="icon" href="{{ getFavIcon() }}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{ getFavIcon() }}" type="image/x-icon" />
    <title>{{ getApplicationName() }}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/font-awesome/css/font-awesome.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/feather-icon.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/animate.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/responsive.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/toastr.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/dragula/dragula.css') }}">
    @livewireScripts()
    @livewireStyles()
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body @if (isRTL() == true) class="rtl" @endif>
    <div class="loader-wrapper">
        <div class="theme-loader">
        </div>
    </div>
    <div class="page-wrapper null compact-wrapper" id="pageWrapper">
        @livewire('components.header')
        <div class="page-body-wrapper sidebar-icon">
            @if (Auth::user()->user_type == 1)
                @livewire('components.super-admin-sidebar')
            @endif
            @if (Auth::user()->user_type == 2)
                @livewire('components.sidebar')
            @endif
            @if (Auth::user()->user_type == 3)
                @livewire('components.sidebar')
            @endif
            {{ $slot }}
        </div>
    </div>
    @livewire('components.ledger-component')
    <script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="{{ asset('assets/js/tooltip-init.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/custom-script.js') }}"></script>
    <script src="{{ asset('assets/js/dragula/dragula.min.js') }}"></script>
    @stack('js')
</body>
</html>