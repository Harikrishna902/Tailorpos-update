<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{getApplicationName()}}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/toastr.min.css') }}">
    <link rel="icon" href="{{ getFavIcon() }}" type="image/x-icon" />
    <link rel="shortcut icon" href="{{ getFavIcon() }}" type="image/x-icon" />
    @livewireScripts()
    @livewireStyles()
    @stack('head')
</head>

<body class="overflow-y-clip h-[100svh] bg-white flex flex-col w-[100vw] overflow-x-hidden relative "
    x-data="main">
    <div class="fixed h-[100svh] w-screen inset-0 bg-white z-[200] flex overflow-clip justify-center items-center  "
        x-show="showPreloader" x-transition.opacity.duration.500ms>
        <span class="loader "></span>
    </div>

    <livewire:frontend.components.header />
    <livewire:frontend.components.cart />
    {{ $slot }}
    <livewire:frontend.components.footer />

    <div class="fixed top-5 right-5 pointer-events-none flex flex-col gap-3  z-[99999]" x-data="toasterCustom">
        <template x-for="(toast,index) in toasties" :key="toast.id">
            <div
                class="  lg:w-[28rem] p-4 bg-gray-200/80 backdrop-blur-lg shadow-[0px_0px_30px_10px_#0000000C] rounded-lg pointer-events-auto text-black flex flex-col " >
                <div class="flex items-center gap-3">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-14 h-14 text-green-500">
                            <path fill-rule="evenodd"
                                d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="flex flex-col">
                        <div class="text-lg font-semibold text-primary" x-text="toast.title">
                        </div>
                        <div class="text-black/60" x-text="toast.message">
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
    <script src="{{ asset('assets/js/front-end.js') }}"></script>
    <script src="{{ asset('assets/frontend/modal.js') }}"></script>
    <script src="{{ asset('assets/frontend/custom-toastr.js') }}"></script>
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    @stack('js')
</body>

</html>
