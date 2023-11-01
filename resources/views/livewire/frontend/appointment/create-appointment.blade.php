<div class="flex w-full items-center  justify-center shrink-0">
    <div class="flex w-[70rem]  pt-24 flex-col  lg:pb-10 ">
        <div class="lg:pt-8 p-2">
            <div class="flex justify-between items-center">
                <div class="font-bold text-primary text-2xl">{{__('main.make_appointment')}}</div>
                <a href="{{ route('frontend.place-order') }}"
                    class="px-4 py-2 text-sm flex justify-center items-center gap-2 hover:bg-secondary/20 rounded-lg font-medium shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd"
                            d="M20.25 12a.75.75 0 01-.75.75H6.31l5.47 5.47a.75.75 0 11-1.06 1.06l-6.75-6.75a.75.75 0 010-1.06l6.75-6.75a.75.75 0 111.06 1.06l-5.47 5.47H19.5a.75.75 0 01.75.75z"
                            clip-rule="evenodd" />
                    </svg>
                    {{__('main.back')}}
                </a>
            </div>
        </div>

        <div class="flex lg:flex-row flex-col gap-8 p-2">
            <div
                class="shadow-[0px_0px_30px_10px_#00000007] lg:w-[60%] lg:shrink-0  flex flex-col rounded-lg w-full p-5 h-fit  grow-0 ">
                <div class=" font-semibold text-md  mt-4 ">
                    {{__('main.preferred_appointment_date')}}<span class="text-red-400">*</span>
                </div>
                @php
                    $mindate = date('Y-m-d');
                    $mintime = date('h:i');
                    $min = $mindate . 'T' . $mintime;
                    $maxdate = date('Y-m-d', strtotime('+10 Days'));
                    $maxtime = date('h:i');
                    $max = $maxdate . 'T' . $maxtime;
                @endphp

                <div class="">
                    <input type="datetime-local" class="border border-black/10 mt-2 rounded-lg p-2 w-full"
                        id="date-local" min="{{$min}}" wire:model="date">
                </div>
                @error('date') <span class="error text-xs text-red-500">{{ $message }}</span>@enderror
                <div class=" font-semibold text-md mt-4 ">
                    {{__('main.notes')}} <span class="font-normal text-sm"></span>
                </div>
                <div class="">
                    <textarea name="" id="" class="border border-black/10 mt-2 rounded-lg p-2 w-full resize-none"
                        rows="6" placeholder="Enter notes (optional)" wire:model="notes"></textarea>
                </div>
            </div>
            <div class="shadow-[0px_0px_30px_10px_#00000007] w-full lg:w-full flex flex-col rounded-lg h-fit">


                <div class="p-5 mt-1 flex gap-2 lg:flex-row flex-col">
                    <a href="#" class=" w-full">
                        <button class="w-full px-5 py-3 rounded-lg bg-secondary text-white"
                            @click.prevent="save($wire,$dispatch)">{{__('main.create_appointment')}}</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="" x-data="modalFunction" id="appointment-create-success">
        <div class="fixed h-screen w-screen bg-black/50 inset-0 z-[200] " x-cloak x-show="modalShown"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        </div>
        <div class="fixed h-screen w-screen inset-0 z-[200] flex justify-center items-center" x-cloak
            x-show="modalShown" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-90">
            <div class="z-[201] lg:w-[35rem] w-[90%] p-4 bg-white rounded-xl">
                <div class="h-full w-full flex flex-col">
                    <div class="flex justify-between items-center">
                        <div class="text-lg font-semibold tracking-wide text-primary">
                            {{__('main.appointment_created')}}
                        </div>
                    </div>
                    <div class="font-light mt-3 text-sm flex flex-col">
                        <div class="flex items-center justify-center flex-col">
                            <div class="text-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-20 h-20">
                                    <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="text-lg">{{__('main.success_appointment')}}</div>
                            <div class="text-xs">{{__('main.auto_redirecting')}}</div>
                        </div>
                    </div>
                    <div class="flex justify-start items-center mt-5 gap-4 flex-wrap lg:flex-nowrap">
                        <a href="#" @click.prevent="goHomeScreen"
                            class="w-full px-5 py-3 rounded-lg bg-secondary text-white text-center">{{__('main.okay')}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('head')
    <script>
        'use strict';
        function save(wire,dispatch)
        {
            wire.save().then((response) => {
                console.log(response)
                if(response === true)
                {
                    dispatch('show-my-modal','appointment-create-success')
                setTimeout(() => {
                    window.location.href="{{URL::to('/')}}";
                },5000)
                }
              
            })
        }

        function goHomeScreen()
        {
            window.location.href="{{URL::to('/')}}";
        }
    </script>
@endpush