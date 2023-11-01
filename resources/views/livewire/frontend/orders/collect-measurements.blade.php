<div class="flex w-full items-center  justify-center shrink-0" x-data="finalHome">
    <div class="flex w-[70rem]  pt-24 flex-col  lg:pb-10 ">
        <div class="lg:pt-8 p-2">
            <div class="flex justify-between items-center">
                <div class="font-bold text-primary text-2xl">{{__('main.payment')}}</div>
                <a href="{{route('frontend.place-order')}}" class="px-4 py-2 text-sm flex justify-center items-center gap-2 hover:bg-secondary/20 rounded-lg font-medium shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M20.25 12a.75.75 0 01-.75.75H6.31l5.47 5.47a.75.75 0 11-1.06 1.06l-6.75-6.75a.75.75 0 010-1.06l6.75-6.75a.75.75 0 111.06 1.06l-5.47 5.47H19.5a.75.75 0 01.75.75z" clip-rule="evenodd" />
                    </svg>
                    {{__('main.back')}}
                </a>
            </div>
        </div>
        <div class="flex lg:flex-row flex-col gap-8 p-2">
            <div class="shadow-[0px_0px_30px_10px_#00000007] w-full lg:w-[60%] shrink-0 flex flex-col rounded-lg">
                {{-- <div class="text-center font-semibold text-md py-5 ">
                    Payment
                </div> --}}
                <div class=" flex flex-col gap-3 overflow-y-auto p-5 pt-3 pb-0 ">
                    @foreach ($itemList as $i => $item )
                        <div
                            class="flex justify-between items-center border-b pb-4 border-neutral-100">
                            <div class="flex items-center">
                                <div class=" h-14 w-14 shrink-0 relative">
                                    @if ($item['image'] && file_exists(public_path($item['image'])))
                                    <img src="{{ $item['image'] }}" class=" object-cover h-full rounded-xl w-full">
                                    @else
                                        <img src="{{ asset('assets/images/sample.jpg') }}"
                                            class=" object-cover h-full rounded-xl w-full">
                                    @endif
                                    <div
                                        class="absolute h-6 w-6 text-xs bg-primary rounded-full text-white flex justify-center items-center bottom-0 right-0 font-semibold">
                                        {{$item['quantity']}}</div>
                                </div>
                                <div class="flex-col px-3">
                                    <div class=" text-sm ">{{$item['name']}}</div>
                                    <div class="text-xs text-neutral-500 ">
                                        {{$item['notes'] ?? '..'}}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-md text-primary font-bold ">
                                {{getFormattedCurrency($item['total'])}}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="shadow-[0px_0px_30px_10px_#00000007] w-full lg:w-full flex flex-col rounded-lg h-fit">
                <div class="text-center font-semibold text-md py-5 ">
                    {{__('main.checkout')}}
                </div>
                <div class="border-t border-neutral-100 w-full p-5 flex flex-col pb-0">
                    <div class="text-primary font-semibold text-sm mb-2">
                        {{__('main.details')}}
                    </div>
                    <div class="border rounded-lg border-neutral-200 flex flex-col p-3 gap-3">
                        <div class="w-full  flex justify-between">
                            <div class="text-sm text-secondary">{{__('main.sub_total')}}</div>
                            <div class="text-sm font-medium text-primary">{{getFormattedCurrency($subtotal)}}</div>
                        </div>
                        <div class="w-full   flex justify-between">
                            <div class="text-sm text-secondary">{{__('main.tax_total')}}</div>
                            <div class="text-sm font-medium text-primary">{{getFormattedCurrency($tax_total)}}</div>
                        </div>

                        <div class="w-full   flex justify-between">
                            <div class="text-sm text-secondary font-bold">{{__('main.total')}}</div>
                            <div class="text-sm font-bold text-primary">{{getFormattedCurrency($total)}}</div>
                        </div>
                    </div>

                    <div class="text-primary font-semibold text-sm mb-2 mt-4">
                        {{__('main.payment_mode')}}
                    </div>
                    <div
                        class="border border-secondary/50 cursor-pointer flex justify-between rounded-lg p-3 items-center">
                        <div class="flex items-center gap-2 text-primary">
                            <div class="">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                                </svg>
                            </div>
                            <div class=" text-sm font-semibold">
                                {{__('main.cash_on_delivery')}}
                            </div>
                        </div>
                        <div class="text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="p-5 mt-1 flex gap-2 lg:flex-row flex-col">
                    <a href="#" class=" w-full">
                        <button class="w-full px-5 py-3 rounded-lg bg-secondary text-white"
                            @click="save">{{__('main.checkout')}}</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

    
    <div class="" x-data="modalFunction" id="order-create-success">
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
                            {{__('main.order_placed')}}
                        </div>
                    </div>
                    <div class="font-light mt-3 text-sm flex flex-col">
                        <div class="flex items-center justify-center flex-col">
                            <div class="text-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-20 h-20">
                                    <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="text-lg font-semibold" x-text="'#'+invoiceNumber"></div>
                            <div class="text-lg">{{__('main.order_text_success')}}</div>
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
<script defer>
    'use strict'
    function finalHome()
    {
        return{
            invoiceNumber : '',
            init()
            {
            },
            save()
            {
                this.$dispatch('clear-everything')
                this.$wire.save().then((response) => {
                    this.invoiceNumber = response
                    this.$dispatch('show-my-modal','order-create-success')
                    setTimeout(() => {
                        this.goHomeScreen()
                    },5000)
                })
            },
            goHomeScreen()
            {
                window.location.href="{{URL::to('/')}}";
            }
        }
    }
</script>
@endpush
