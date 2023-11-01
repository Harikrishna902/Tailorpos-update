<!DOCTYPE html>
<html lang="en" >
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{__('installer.app_installer')}}</title>
    <link id="color" rel="stylesheet" href="{{ asset('assets/css/installer.css') }}" media="screen" />
    @livewireStyles()
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/custom.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}" />
</head>
<body >
    <div class="overflow-y-clip h-[100svh] bg-white flex flex-col w-[100vw] overflow-x-hidden relative ">
        <div class="page-body-wrapper sidebar-icon">
            {{ $slot }}
        </div>
    </div>
    @livewireScripts()
    <script src="{{ asset('assets/js/updater.js') }}"></script>
    <script src="{{ mix('js/app.js') }}" defer></script>
    @stack('js')
</body>
</html>