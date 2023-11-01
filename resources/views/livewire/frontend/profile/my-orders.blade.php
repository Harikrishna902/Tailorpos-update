<div class="flex w-full items-center  justify-center shrink-0">
    <div class="flex w-[70rem]  pt-24 flex-col  lg:pb-10 ">
        <div class="lg:pt-8 p-2 px-4">
            <div class="flex justify-between items-center">
                <div class="font-bold text-primary text-2xl">{{__('main.my_orders')}}</div>
                <a href="{{ route('frontend') }}"
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
            <div class="shadow-[0px_0px_30px_10px_#00000007]  flex flex-col rounded-lg w-full p-5 h-fit  grow-0 ">
                <div class="mb-3">{{__('main.orders')}}</div>
                <div class="grid grid-cols-1 gap-4  text-sm  h-[calc(100dvh-20rem)] overflow-y-auto">
                    @foreach ($active_orders as $item)
                        <div class="flex justify-between items-center p-5 border rounded-lg border-primary/10 ">
                            <div class="flex flex-col">
                                <div class="">{{__('main.order_id')}}: <span class="font-bold">#{{ $item->order_number }}</span>
                                </div>
                                <div class="text-xs"> {{ \Carbon\Carbon::parse($item->date)->format('h:i A, d-m-Y') }}
                                </div>
                                <div class="font-bold mt-3">{{__('main.total')}} : {{ getFormattedCurrency($item->total) }}</div>
                            </div>
                            <div class="flex flex-col h-full items-end justify-end">
                                <div class="@if($item->status == 0)  bg-orange-500/10 text-orange-500 @elseif($item->status == 1)  bg-blue-500/10 text-blue-500 @elseif($item->status == 2)  bg-yellow-500/10 text-yellow-500 @else($item->status == 3)  bg-green-500/10 text-green-500 @endif p-1 px-5  rounded-full text-xs">
                                    {{getOrderStatus($item->status)}}
                                </div>
                                <a href="#"
                                    class="@if ($activeOrder && $activeOrder->id == $item->id) text-blue-500  @else text-secondary @endif mt-3 text-xs font-bold flex w-fit items-center gap-1"
                                    @click.prevent="viewOrder($wire,{{$item->id}},$refs.orderDetails)">
                                    {{__('main.view_details')}}
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-3 h-3">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
               
            </div>
            <div class="shadow-[0px_0px_30px_10px_#00000007] w-full lg:w-[40%] shrink-0 flex flex-col rounded-lg" x-ref="orderDetails">
                @if ($activeOrder)
                    <div class="text-center font-semibold text-md py-5 ">
                        {{__('main.order_summary')}} (<span>#{{$activeOrder->order_number}}</span>)
                    </div>
                    <div class=" flex flex-col gap-3 overflow-y-auto p-5 pt-4 pb-0  h-[calc(100dvh-29rem)]">
                        @foreach ($activeOrder->details as $detail)
                            <div class="flex justify-between items-center border-b pb-4 border-neutral-100">
                                <div class="flex items-center">
                                    @php
                                        $item = \App\Models\Product::whereId($detail->item_id)->first();
                                    @endphp
                                    <div class=" h-14 w-14 shrink-0 relative">
                                        @if ($item && $item->image && file_exists(public_path($item->image)))
                                            <img src="{{ $item->image }}"
                                                class=" object-cover h-full rounded-xl w-full">
                                        @else
                                            <img src="{{ asset('assets/images/sample.jpg') }}"
                                                class=" object-cover h-full rounded-xl w-full">
                                        @endif
                                        <div
                                            class="absolute h-6 w-6 text-xs bg-primary rounded-full text-white flex justify-center items-center bottom-0 right-0 font-semibold">
                                            {{ $detail->quantity }}</div>
                                    </div>
                                    <div class="flex-col px-3">
                                        <div class=" text-sm ">
                                            {{ $detail->item_name }}
                                        </div>
                                        <div class="text-xs text-neutral-500 max-w-[10rem] truncate">
                                            {{ $detail->notes ?? '-' }}
                                        </div>
                                    </div>
                                </div>

                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-neutral-100 w-full p-5 flex flex-col pb-5">
                        <div class="border rounded-lg border-neutral-200 flex flex-col p-3 gap-3">
                            <div class="w-full  flex justify-between">
                                <div class="text-sm text-secondary">{{__('main.sub_total')}}</div>
                                <div class="text-sm font-medium text-primary">{{ getFormattedCurrency($activeOrder->sub_total)  }}</div>
                            </div>
                            <div class="w-full   flex justify-between">
                                <div class="text-sm text-secondary">{{__('main.tax_total')}}</div>
                                <div class="text-sm font-medium text-primary">{{ getFormattedCurrency($activeOrder->tax_total) }}</div>
                            </div>

                            <div class="w-full   flex justify-between">
                                <div class="text-sm text-secondary">{{__('main.total')}}</div>
                                <div class="text-sm font-medium text-primary">{{ getFormattedCurrency($activeOrder->total) }}</div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="h-full w-full text-center flex justify-center items-center">
                        {{__('main.click_on_order')}}
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

@push('head')
    <script>
        'use strict';
        function viewOrder(wire,id,ref)
        {
            wire.viewOrder(id); 
            if(window.innerWidth < 1024)
            {
                ref.scrollIntoView({ behavior: 'smooth',block : 'start' });
            }
        }
    </script>
@endpush