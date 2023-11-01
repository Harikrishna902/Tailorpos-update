<div x-data="alpineInstaller" class="h-screen w-screen bg-neutral-50 flex justify-center items-center">
    <div class="w-[40%] min-w-[45rem] bg-white rounded-xl shrink-0 shadow-sm flex p-5 relative">
        <div class="flex flex-col w-full relative">
            <div class="flex items-center justify-center pt-4" :class="step == 1 ? 'opacity-50' : 'opacity-100'">
                <div class="h-12 w-12 rounded-full flex justify-center items-center transition-all duration-300"
                    :class="step > 1 ? 'bg-green-100 text-green-500' : ' text-neutral-400  bg-neutral-100 '">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3l1.5 1.5 3-3.75" />
                      </svg>
                </div>
                <div class="h-0.5  w-10"  :class="step > 3 ? 'bg-green-400': ' bg-neutral-200 '"></div>
                <div class="h-12 w-12 rounded-full flex justify-center items-center transition-all duration-300"
                    :class="step > 3 ? 'bg-green-100 text-green-500' : ' text-neutral-400  bg-neutral-100 '">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                      </svg>
                </div>
                <div class="h-0.5  w-10"  :class="step > 4 ? 'bg-green-400': ' bg-neutral-200 '"></div>
                <div class="h-12 w-12 rounded-full flex justify-center items-center transition-all duration-300"
                    :class="step > 4 ? 'bg-green-100 text-green-500' : ' text-neutral-400  bg-neutral-100 '">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                      </svg>
                </div>
            </div>
            <div class="flex w-full justify-center items-center flex-col">
                <div class="text-2xl font-semibold leading-tight  text-neutral-700 text-center w-[70%] py-4 pb-0">
                    {{__('installer.tailor_pos_installer')}}
                </div>
                <div class="text-blue-600 pt-1 " x-show="step == 1">{{__('installer.lets_get_started')}}</div>
                <div class="text-blue-600 pt-1 " x-show="step == 3">{{__('installer.requirements')}}</div>
                <div class="text-blue-600 pt-1 " x-show="step == 4">{{__('installer.database')}}</div>
                <div class="text-blue-600 pt-1 " x-show="step == 7">{{__('installer.done')}}</div>
            </div>
            <div class="flex flex-col h-[300px] justify-center items-center" x-show="step == 'loading'" x-cloak>
                {{__('installer.hold_on')}}
            </div>
            <div class="flex flex-col" x-show="step == 1" x-cloak >
                <div class="flex justify-center pt-4">
                    <div class="w-[70%] text-center text-xs pt-4 text-neutral-700">
                        {{__('installer.welcome_description')}}
                    </div>
                </div>
                <div class="flex justify-center pt-8    ">
                    <button class="bg-blue-500 px-12 text-white py-2 rounded-2xl"
                        @click="checkRequirements">{{__('installer.next')}}</button>
                </div>
                <div class="text-xs text-center py-4 text-neutral-400">{{__('installer.do_not_close')}}</div>
            </div>
            <div class="flex flex-col  py-4" x-show="step == 3" x-cloak >
                <div class="flex justify-center">
                    <div class=" w-[70%]">
                        {{__('installer.extensions_requirements')}}
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="flex  justify-center pt-2 flex-col w-[70%]">
                        @if ($extensions)
                            @foreach ($extensions as $key => $row)
                                <div class="flex justify-between items-center  text-neutral-600 ">
                                    <div class="text-neutral-600 text-xs">{{ $key }}</div>
                                    <div class="">
                                        @if ($row == 1)
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="flex justify-center pt-4">
                    <div class=" w-[70%]">
                        {{__('installer.directories')}}
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="flex  justify-center pt-2 flex-col w-[70%]">
                        @if ($directories)
                            @foreach ($directories as $key => $row)
                                <div class="flex justify-between items-center  text-neutral-600 ">
                                    <div class="text-neutral-600 text-xs">{{ $key }}</div>
                                    <div class="">
                                        @if ($row == 1)
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor"
                                                class="w-6 h-6 text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
                @if (!$requirement_satisfied)
                    <p class="text-danger text-center text-xs text-red-500">{{ __('installer.cannot_proceed') }}</p>
                @endif
                @if ($requirement_satisfied)
                    <div class="flex justify-center pt-8  pb-4  ">
                        <button class="bg-blue-500 px-12 text-white py-2 rounded-2xl"
                            @click="showDatabase">{{__('installer.next')}}</button>
                    </div>
                @endif
            </div>
            <div class="flex flex-col  py-4" x-show="step == 4" x-cloak >
                <div class="flex justify-center ">
                    <div class="flex flex-col  w-[70%]">
                        <div class="">
                            {{__('installer.enter_database_info')}}
                        </div>
                        <div class="flex flex-col py-4">
                            <div class="mb-3 flex flex-col">
                                <label class="form-label text-neutral-600 text-xs">{{ __('installer.database_host') }}</label>
                                <input type="text" wire:model="host" class="mt-2 rounded-lg border border-neutral-300 text-sm p-2" name="host"
                                    placeholder="Enter Database Host">
                                @error('host')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 flex flex-col">
                                <label class="form-label text-neutral-600 text-xs">{{ __('installer.database_port') }}</label>
                                <input type="text" wire:model="port" class="mt-2 rounded-lg border border-neutral-300 text-sm p-2" name="port"
                                    placeholder="Enter Database Port">
                                @error('port')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 flex flex-col">
                                <label class="form-label text-neutral-600 text-xs">{{ __('installer.database_name') }}</label>
                                <input type="text" wire:model="name" class="mt-2 rounded-lg border border-neutral-300 text-sm p-2" name="name"
                                    placeholder="Enter Database Name">
                                @error('name')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 flex flex-col">
                                <label class="form-label text-neutral-600 text-xs">{{ __('installer.database_username') }}</label>
                                <input type="text" wire:model="username" class="mt-2 rounded-lg border border-neutral-300 text-sm p-2" name="username"
                                    placeholder="Enter Database Username">
                                @error('username')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="mb-3 flex flex-col">
                                <label class="form-label text-neutral-600 text-xs">{{ __('installer.database_password') }}</label>
                                <input type="password" wire:model="password" class="mt-2 rounded-lg border border-neutral-300 text-sm p-2" name="password"
                                    placeholder="Enter Database Password">
                                @error('password')
                                    <span class="text-xs text-red-500">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="w-full">
                                <span class="text-xs text-red-500 text-center">{{ $errormessage }}</span>
                            </div>
                            <div class="progress" x-show="loading == true" x-transition>
                                <span class="loader"></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex justify-center pt-8  pb-4  ">
                    <button class="bg-blue-500 px-12 text-white py-2 rounded-2xl"
                        @click="checkDatabase">{{__('installer.next')}}</button>
                </div>
            </div>
            <div class="flex flex-col  py-4" x-show="step == 7" x-cloak >
                <div class="flex justify-center ">
                    <div class="flex flex-col text-center px-10  bg-neutral-200 p-4 rounded-lg shadow text-sm">
                        <div class="text-neutral-600">{{__('installer.your_email_is')}} </div>
                        <div class="text-neutral-600 pt-2">{{__('installer.your_password_is')}} </div>
                    </div>
                </div>
                <div class="flex justify-center pt-8  pb-4  " >
                    <a href="#" wire:click="goToDashboard" class="bg-blue-500 px-12 text-white py-2 rounded-2xl">{{__('installer.go_to_dashboard')}}</a>
                </div>
            </div>
        </div>
    </div>
</div>