<div class="">
    <div class="fixed inset-0 h-[100dvh]  w-full z-50 bg-black/40 transition-all duration-200 shadow-md flex justify-end "
        x-show="cartShown" x-cloak x-transition.opacity.duration.300ms @click="hideCart">

    </div>
    <div class="w-full lg:w-[30rem] bg-white flex flex-col p-5 shadow-md justify-between fixed top-0 right-0 z-50  h-[100dvh] "
        x-show="cartShown" x-cloak x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0  lg:translate-x-96  scale-75 lg:scale-100"
        x-transition:enter-end="opacity-100  lg:translate-x-0 scale-100 lg:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100  lg:translate-x-0 scale-100 lg:scale-100"
        x-transition:leave-end="opacity-0  lg:translate-x-96 scale-75 lg:scale-100">
        <div class="flex flex-col">
            <div class="flex justify-between items-center ">
                <div class="">
                </div>
                <div class="text-xl font-bold text-primary">
                    {{__('main.cart')}}
                </div>
                <a href="#" class="text-primary" @click="hideCart">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd"
                            d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zm-1.72 6.97a.75.75 0 10-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 101.06 1.06L12 13.06l1.72 1.72a.75.75 0 101.06-1.06L13.06 12l1.72-1.72a.75.75 0 10-1.06-1.06L12 10.94l-1.72-1.72z"
                            clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
            <div class="mt-4 flex flex-col gap-3 h-[calc(100svh-14rem)] overflow-y-auto">
                <template x-for="item in cartItems" :key="item.id">
                    <div class="flex justify-between items-center">
                        <div class="flex">
                            <div class=" h-14 w-14 shrink-0">
                                <template x-if="item.image"> 
                                <img :src="'{{URL::to('/')}}'+item.image"
                                    class=" object-cover h-full rounded-xl w-full " alt="">
                                </template>
                                <template x-if="!item.image"> 
                                    <img src="{{asset('assets/images/sample.jpg')}}"
                                        class=" object-cover h-full rounded-xl w-full " alt="">
                                    </template>
                            </div>
                            <div class="flex-col px-2">
                                <div class="mt-2 text-sm font-normal" x-text="item.name"></div>
                                <div class="text-xs text-neutral-500 truncate max-w-[10rem] lg:max-w-[16rem] " x-text="item.description">
                                   
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 ">
                            <button @click="reduceItemFromCart(item.id)"
                                class="h-4 w-4 rounded-full border border-primary flex justify-center items-center text-sm hover:bg-primary hover:text-white font-bold"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5">
                                    <path fill-rule="evenodd"
                                        d="M5.25 12a.75.75 0 01.75-.75h12a.75.75 0 010 1.5H6a.75.75 0 01-.75-.75z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                            <span x-text="getItemCountFromCart(item.id)"></span>
                            <button @click="addItemToCart(item.id)"
                                class="h-4 w-4 rounded-full border border-primary flex justify-center items-center text-sm hover:bg-primary hover:text-white font-bold"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="w-5 h-5">
                                    <path fill-rule="evenodd"
                                        d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </template>
                <template x-if="cartItems.length == 0">
                    <div class="flex justify-center items-center h-full w-full text-center">
                        {{__('main.cart_empty_message')}}, <br>{{__('main.cart_empty_message_line2')}}
                    </div>
                </template>
            </div>
        </div>
        <template x-if="cartItems.length > 0">
            <div class="flex flex-col">
                <div class="w-full p-4 border rounded-lg border-neutral-200 flex justify-between">
                    <div class="text-sm text-secondary">{{__('main.sub_total')}} </div>
                    <div class="text-sm font-semibold text-primary" x-text="replaceCurrencyText('{{getFormattedCurrency(0)}}',cartData.subtotal)"></div>
                </div>
                <a href="#" @click="checkoutFromCart($wire)" class="mt-4">
                    <button class="w-full px-5 py-3 rounded-lg bg-secondary text-white">{{__('main.checkout')}}</button>
                </a>
            </div>
        </template>
    </div>
</div>