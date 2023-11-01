<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="author" content="Xfortech" />
    <link rel="icon" href="{{getFavIcon()}}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{getFavIcon()}}" type="image/x-icon" />
    <title>{{getApplicationName()}}</title>
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
<body>
   @yield('content')
   <script src="{{ asset('assets/js/jquery-3.7.1.js') }}"></script>
    <script src="{{asset('assets/js/icons/feather-icon/feather.min.js')}}"></script>
    <script src="{{asset('assets/js/icons/feather-icon/feather-icon.js')}}"></script>
    <script src="{{asset('assets/js/config.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/script.js')}}"></script>
</body>
</html>