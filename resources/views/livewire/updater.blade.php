<div x-data="alpineUpdater" class="h-screen w-screen bg-neutral-50 flex justify-center items-center">
    @if ($complete == false)
        <div class="w-[40%] min-w-[45rem] bg-white rounded-xl shrink-0 shadow-sm flex p-5 relative py-20">
            <div class="flex flex-col w-full relative">
                <div class="flex items-center justify-center pt-4" :class="step == 1 ? 'opacity-50' : 'opacity-100'">
                    <div class="h-12 w-12 rounded-full flex justify-center items-center transition-all duration-300"
                        :class="step > 1 ? 'bg-green-100 text-green-500' : ' text-neutral-400  bg-neutral-100 '">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3V15" />
                        </svg>
                    </div>
                </div>
                <template x-if="step == 1">
                    <div class="flex w-full justify-center items-center flex-col">
                        <div
                            class="text-xl font-semibold leading-tight  text-neutral-700 text-center w-[70%] py-4 pb-0">
                            {{__('installer.tailor_pos_updater')}}
                        </div>

                        <div class="mt-4 text-sm text-black/70">{{__('installer.updater_message')}} </div>
                        <div class=" text-sm text-black/50">{{__('installer.updater_message_2')}}</div>


                        <button class="bg-blue-500 px-12 mt-8 text-white py-2 rounded-2xl"
                            @click="updateNow">{{__('installer.update')}}</button>
                    </div>
                </template>
                <template x-if="step == 2">
                    <div class="flex w-full justify-center items-center flex-col">
                        <div
                            class="text-xl font-semibold leading-tight  text-neutral-700 text-center w-[70%] py-4 pb-0">
                            {{__('installer.updating')}}

                        </div>

                        <div class="mt-4 text-sm text-black/70">{{__('installer.being_updated')}}<template
                                x-for="item in count" :key="item"><span>.</span></template>
                        </div>
                    </div>
                </template>

            </div>
        </div>
    @else
        <div class="w-[40%] min-w-[45rem] bg-white rounded-xl shrink-0 shadow-sm flex p-5 relative py-20 flex-col">
                <div class="flex items-center justify-center pt-4 ">
                    <div
                        class="h-12 w-12 rounded-full flex justify-center items-center transition-all duration-300 bg-green-100 text-green-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 8.25H7.5a2.25 2.25 0 00-2.25 2.25v9a2.25 2.25 0 002.25 2.25h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25H15m0-3l-3-3m0 0l-3 3m3-3V15" />
                        </svg>
                    </div>
                </div>
                <div class="flex w-full justify-center items-center flex-col">
                    <div class="text-xl font-semibold leading-tight  text-neutral-700 text-center w-[70%] py-4 pb-0">
                        {{__('installer.updater_complete')}}
                    </div>
                    <a href="{{route('frontend.login')}}" wire:click.prevent="goToDashboard" class="bg-blue-500 px-12 mt-8 text-white py-2 rounded-2xl"
                            >{{__('main.login')}}</a>
                </div>
        </div>
    @endif

</div>
