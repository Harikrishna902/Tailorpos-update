<div class="" x-data="header">
    <header class="bg-white shadow w-full p-5 flex justify-center items-center fixed top-0 z-50 ">
        <div class="flex w-[70rem]  justify-between items-center flex-wrap lg:flex-nowrap">
            <div class="flex gap-10 w-full justify-between lg:w-auto lg:justify-normal">
                <a class="text-2xl font-bold text-secondary" href="{{ route('frontend') }}">
                    <img class="img-fluid h-8" src="{{ getApplicationLogo() }}" alt="" />
                </a>
                <div class="relative">
                    <div href="#" class="flex justify-between items-center gap-2  cursor-pointer w-[7rem]"
                        @click.stop="toggleBranchDropdown" @click.outside="hideBranchDropdown">
                        <div class="flex flex-col pointer-events-none" @click.stop="toggleBranchDropdown">
                            <div class="text-xs text-neutral-700">{{ __('main.branch') }}</div>
                            <div class="text-xs text-neutral-900 font-semibold ">{{ $branch ? $branch->name : '?' }}
                            </div>
                        </div>
                        <div class="" @click.stop="toggleBranchDropdown">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </div>
                    </div>
                    <div class="absolute top-[100%] mt-2 left-0 bg-white p-4 shadow-md flex flex-col text-sm z-50 rounded-lg "
                        x-cloak x-show="branchDropdownShown" @click.stop="" @touch.stop="">
                        @foreach ($branches as $item)
                            @if ($branch && $item->id == $branch->id)
                                <a href="#" class="flex group items-center gap-2 py-2"
                                    wire:click="changeBranch({{ $item->id }})">
                                    <div class="text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            class="bi bi-check-circle-fill h-3.5 w-3.5" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                        </svg>
                                    </div>
                                    <div class="font-semibold group-hover:text-secondary/80">
                                        {{ $item->name }}
                                    </div>
                                </a>
                            @else
                                <a href="#" class="flex  items-center gap-2 group py-2"
                                    wire:click="changeBranch({{ $item->id }})">
                                    <div class="text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="currentColor"
                                            class="bi bi-circle " viewBox="0 0 16 16">
                                            <path
                                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                        </svg>
                                    </div>
                                    <div class="font-semibold group-hover:text-secondary/80">
                                        {{ $item->name }}
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="gap-3 font-semibold text-sm hidden lg:flex">
                <a href="{{ route('frontend') }}" class="text-primary">{{ __('main.frontend_home') }}</a>
                <a href="{{ route('frontend.offers') }}" class="text-primary">{{ __('main.offers') }}</a>
            </div>

            <div class="gap-3 items-center hidden lg:flex">
                {{-- <div
                    class="flex py-1.5 bg-secondary/10 p-2 text-sm items-center rounded-2xl border transition-all duration-200 border-transparent focus-within:border-secondary">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </div>
                    <input type="text" class="px-2 bg-transparent outline-none group"
                        placeholder="{{ __('main.frontend_search') }}">
                </div> --}}
                <div class="relative">
                    <a href="#"
                        class="flex p-2 py-1.5 text-sm items-center rounded-2xl border transition-all gap-2 duration-200 border-secondary/20 "
                        @click.prevent="toggleLanguageDropdown" @click.outside="hideLanguageDropdown">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>
                        </div>
                        <div class="font-semibold text-primary">
                            {{ isset($languages[$lang]) ? $languages[$lang] : 'English' }}</div>
                    </a>
                    <div class="absolute top-[100%] mt-2 left-0 bg-white p-4 shadow-md flex flex-col text-sm  z-50 rounded-lg"
                        x-cloak x-show="languageDropdownShown" @click.stop="">
                        @foreach ($languages as $index => $item)
                            @if ($lang && $index == $lang)
                                <button class="flex  items-center gap-2 group py-2"
                                    wire:click.prevent.stop="changeLanguage('{{ $index }}')">
                                    <div class="text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            class="bi bi-check-circle-fill h-3.5 w-3.5" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                        </svg>
                                    </div>
                                    <div class="font-semibold group-hover:text-secondary/80">{{ $item }}</div>
                                </button>
                            @else
                                <button class="flex  items-center gap-2 group py-2"
                                    wire:click.prevent.stop="changeLanguage('{{ $index }}')">
                                    <div class="text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="currentColor"
                                            class="bi bi-circle " viewBox="0 0 16 16">
                                            <path
                                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                        </svg>
                                    </div>
                                    <div class="font-semibold group-hover:text-secondary/80">{{ $item }}</div>
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
                <button @click="showCart"
                    class="flex py-1.5 p-4  text-sm gap-2 items-center rounded-2xl bg-white transition-all duration-200 text-secondary border border-secondary hover:bg-secondary hover:text-white">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="w-4 h-4">
                            <path
                                d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" />
                        </svg>

                    </div>
                    <div class="font-semibold">
                        {{ __('main.cart') }}
                    </div>
                </button>
                @if (Auth::guard('customer')->user())
                    <div class="flex py-1.5 p-4 text-sm gap-2 items-center rounded-2xl bg-secondary transition-all duration-200 text-white relative cursor-pointer border border-primary"
                        @click.prevent="toggleAccountDropdown" @click.outside="hideAccountDropdown">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="">
                            {{ Auth::guard('customer')->user() ? Str::limit(Auth::guard('customer')->user()->name,10)  : __('main.login') }}
                        </div>
                        <div class="lg:absolute top-[100%] mt-2 right-[0%] bg-white p-4 shadow-md flex flex-col text-sm gap-3 z-50 rounded-lg text-primary  w-[15rem] cursor-auto"
                            x-cloak x-show="accountDropdownShown" @click.stop="">
                            <div class="flex flex-col items-center ">
                                <div
                                    class="h-16 w-16 rounded-full bg-neutral-200 outline-dashed outline-1 outline-primary outline-offset-2 ">
                                    @if(Auth::guard('customer')->user()->avatar)
                                    <img src="{{Auth::guard('customer')->user()->avatar}}" alt="" class="w-full h-full object-cover rounded-full">
                                    @endif
                                </div>
                                <div class="dsf">
                                    <a href="#"
                                        class="bg-primary text-white rounded-full -translate-y-4 p-2 flex justify-center items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>
                                </div>
                                <div class="font-semibold -translate-y-2">
                                    {{ Str::limit(Auth::guard('customer')->user()->name,10) }}
                                </div>
                                <div class="text-xs -translate-y-2">
                                    {{ Auth::guard('customer')->user()->email }}
                                </div>
                                <div class="text-xs -translate-y-2">
                                    {{ getCountryCode() }} {{ Auth::guard('customer')->user()->phone }}
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <a href="{{route('frontend.my-orders')}}" class="flex  items-center gap-2 border-b p-3 px-1">
                                    <div class="text-black hover:text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                                        </svg>
                                    </div>
                                    <div class="">{{__('main.my_orders')}}</div>
                                </a>
                                <a href="{{route('frontend.edit-profile')}}" class="flex  items-center gap-2 border-b p-3 px-1">
                                    <div class="text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>

                                    </div>
                                    <div class=" ">{{__('main.edit_profile')}}</div>
                                </a>

                                <a href="{{ route('frontend.login') }}" wire:click.prevent="logout"
                                    class="flex  items-center gap-2 p-3 px-1">
                                    <div class="text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                        </svg>
                                    </div>
                                    <div class=" ">{{ __('main.logout') }}</div>
                                </a>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{route('frontend.login')}}"
                        class="flex py-1.5 p-4 text-sm gap-2 items-center rounded-2xl bg-secondary transition-all duration-200 text-white relative cursor-pointer border border-primary">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        </div>
                        <div class="font-semibold">
                            {{ __('main.login') }}
                        </div>
                    </a>
                @endif
            </div>
        </div>
    </header>
    <div class="lg:hidden fixed inset-0 w-full h-full z-[51] bg-black/40 flex justify-center items-center " x-cloak
        x-show="accountDropdownShown" @click.prevent.stop="hideAccountDropdown" @touchmove.prevent="">
        <div class="  bg-white p-4 shadow-md flex flex-col text-sm gap-3 z-50 rounded-lg text-primary  cursor-auto min-w-[20rem]"
            @click.stop="">
            @if (Auth::guard('customer')->user())

            <div class="flex flex-col items-center ">
                <div
                    class="h-16 w-16 rounded-full bg-neutral-200 outline-dashed outline-1 outline-primary outline-offset-2 ">
                    @if(Auth::guard('customer')->user()->avatar)
                    <img src="{{Auth::guard('customer')->user()->avatar}}" alt="" class="w-full h-full object-cover rounded-full">
                    @endif
                </div>
                <div class="dsf">
                    <a href="#"
                        class="bg-primary text-white rounded-full -translate-y-4 p-2 flex justify-center items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                    </a>
                </div>
                <div class="font-semibold -translate-y-2">
                    {{ Str::limit(Auth::guard('customer')->user()->name,10) }}
                </div>
                <div class="text-xs -translate-y-2">
                    {{ Auth::guard('customer')->user()->email }}
                </div>
                <div class="text-xs -translate-y-2">
                    {{ getCountryCode() }} {{ Auth::guard('customer')->user()->phone }}
                </div>
            </div>
            @endif
            <div class="flex flex-col">
                @if(Auth::guard('customer')->check())
                <a href="{{route('frontend.my-orders')}}" class="flex  items-center gap-2 border-b p-3 px-1">
                    <div class="text-black hover:text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                        </svg>
                    </div>
                    <div class="">{{__('main.my_orders')}}</div>
                </a>
                <a href="{{route('frontend.edit-profile')}}" class="flex  items-center gap-2 border-b p-3 px-1">
                    <div class="text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>

                    </div>
                    <div class=" ">{{__('main.edit_profile')}}</div>
                </a>
               
                @endif
                <div class="relative">
                    <a href="#" class="flex  items-center gap-2 border-b p-3 px-1"
                        @click.prevent="toggleLanguageDropdown" @click.outside="hideLanguageDropdown">
                        <div class="text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" />
                            </svg>
                        </div>
                        <div class=" ">
                            {{ isset($languages[$lang]) ? $languages[$lang] : 'English' }}</div>
                    </a>
                    <div class="absolute top-[100%] left-0 bg-white p-4 shadow-[0px_0px_30px_10px_#0000000C] flex flex-col text-sm  z-50 rounded-lg w-full"
                        x-cloak x-show="languageDropdownShown" @click.stop="">
                        @foreach ($languages as $index => $item)
                            @if ($lang && $index == $lang)
                                <button class="flex  items-center gap-2 group py-2"
                                    wire:click.prevent.stop="changeLanguage('{{ $index }}')">
                                    <div class="text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            class="bi bi-check-circle-fill h-3.5 w-3.5" viewBox="0 0 16 16">
                                            <path
                                                d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
                                        </svg>
                                    </div>
                                    <div class="font-semibold group-hover:text-secondary/80">{{ $item }}</div>
                                </button>
                            @else
                                <button class="flex  items-center gap-2 group py-2"
                                    wire:click.prevent.stop="changeLanguage('{{ $index }}')">
                                    <div class="text-secondary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                            fill="currentColor" class="bi bi-circle " viewBox="0 0 16 16">
                                            <path
                                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                        </svg>
                                    </div>
                                    <div class="font-semibold group-hover:text-secondary/80">{{ $item }}</div>
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
                @if(!Auth::guard('customer')->check())
                <a href="{{ route('frontend.login') }}" class="flex  items-center gap-2 p-3 px-1">
                    <div class="text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                    </div>
                    <div class=" ">{{ __('main.login') }}</div>
                </a>
                @else
                <a href="{{route('frontend.edit-profile')}}" class="flex  items-center gap-2 p-3 px-1">
                    <div class="text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>

                    </div>
                    <div class=" ">{{ __('main.logout') }}</div>
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="fixed bottom-0 bg-white shadow-custom w-full p-2 pb-4 lg:hidden z-50">
        <div class="flex justify-around items-center text-neutral-500">
            <a href="{{ route('frontend') }}"
                class="flex flex-col gap-1 items-center justify-center text-xs  w-[4rem] {{ Route::is('frontend') ? 'text-secondary' : '' }}">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-house-door-fill" viewBox="0 0 16 16">
                        <path
                            d="M6.5 14.5v-3.505c0-.245.25-.495.5-.495h2c.25 0 .5.25.5.5v3.5a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5v-7a.5.5 0 0 0-.146-.354L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.354 1.146a.5.5 0 0 0-.708 0l-6 6A.5.5 0 0 0 1.5 7.5v7a.5.5 0 0 0 .5.5h4a.5.5 0 0 0 .5-.5Z" />
                    </svg>
                </div>
                <div class="">
                    {{ __('main.frontend_home') }}
                </div>
            </a>
            <a href="{{route('frontend.offers')}}" class="flex flex-col gap-1 items-center justify-center text-xs  w-[4rem] {{ Route::is('frontend.offers') ? 'text-secondary' : '' }}">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-tags-fill" viewBox="0 0 16 16">
                        <path
                            d="M2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586V2zm3.5 4a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                        <path
                            d="M1.293 7.793A1 1 0 0 1 1 7.086V2a1 1 0 0 0-1 1v4.586a1 1 0 0 0 .293.707l7 7a1 1 0 0 0 1.414 0l.043-.043-7.457-7.457z" />
                    </svg>
                </div>
                <div class="">
                    {{ __('main.offers') }}
                </div>
            </a>
            <a href="#"
                class="flex flex-col gap-1 items-center justify-center text-xs bg-secondary  text-white rounded-full h-12 w-12  shadow-md"
                @click.prevent="showCart">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path
                            d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" />
                    </svg>

                </div>

            </a>
        
            <a href="{{ route('frontend.create-appointment') }}"
                class="flex flex-col gap-1 items-center justify-center text-xs w-[4rem] {{ Route::is('frontend.create-appointment') ? 'text-secondary' : '' }}">
                <div class="">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                        <path fill-rule="evenodd" d="M1.5 6.375c0-1.036.84-1.875 1.875-1.875h17.25c1.035 0 1.875.84 1.875 1.875v3.026a.75.75 0 01-.375.65 2.249 2.249 0 000 3.898.75.75 0 01.375.65v3.026c0 1.035-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 17.625v-3.026a.75.75 0 01.374-.65 2.249 2.249 0 000-3.898.75.75 0 01-.374-.65V6.375zm15-1.125a.75.75 0 01.75.75v.75a.75.75 0 01-1.5 0V6a.75.75 0 01.75-.75zm.75 4.5a.75.75 0 00-1.5 0v.75a.75.75 0 001.5 0v-.75zm-.75 3a.75.75 0 01.75.75v.75a.75.75 0 01-1.5 0v-.75a.75.75 0 01.75-.75zm.75 4.5a.75.75 0 00-1.5 0V18a.75.75 0 001.5 0v-.75zM6 12a.75.75 0 01.75-.75H12a.75.75 0 010 1.5H6.75A.75.75 0 016 12zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd" />
                      </svg>
                      
                      
                </div>
                <div class="">
                   {{__('main.booking')}}
                </div>
            </a>
            <a href="#" class="flex flex-col gap-1 items-center justify-center text-xs  w-[4rem]"
            @click.prevent="toggleAccountDropdown">
            <div class="">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd"
                        d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"
                        clip-rule="evenodd" />
                </svg>

            </div>
            <div class="">
                {{ __('main.profile') }}
            </div>
        </a>
        </div>
    </div>
</div>
