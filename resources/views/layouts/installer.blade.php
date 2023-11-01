<!DOCTYPE html>
<html lang="en" >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{__('installer.app_installer')}}</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/fonts/font-awesome/css/font-awesome.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/feather-icon.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}" />
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/installer.css') }}" media="screen" />
    @livewireScripts()
    @livewireStyles()
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}" />
</head>
<body >
    <div class="page-wrapper null compact-wrapper" id="pageWrapper">
        <div class="page-body-wrapper sidebar-icon">
            {{ $slot }}
        </div>
    </div>
    <script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>
    <script src="{{ asset('assets/js/installer.js') }}"></script>
    @stack('js')
</body>
</html>