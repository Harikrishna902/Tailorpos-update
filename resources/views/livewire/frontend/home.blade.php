<div class="flex w-full items-center  justify-center" x-data="cartHome">
    <div class="flex w-[70rem]  pt-24 flex-col  lg:pb-10 ">
        @if(count($sliders) > 0) 
        <div class="" wire:ignore>

            <div class="swiper w-[100vw] h-[12rem] lg:h-[25rem] max-w-[70rem]  rounded-lg shrink-0">
                <div class="swiper-wrapper">
                    @foreach ($sliders as $item)
                    @if($item->photo())
                    <div class="swiper-slide " data-swiper-autoplay="2000">
                        <a href="{{$item->url ? $item->url : '#'}}">

                            <img src="{{ $item->photo() }}" alt=""
                                class="w-full object-contain ">
                        </a>
                    </div>
                    @endif
                    @endforeach
                    
                </div>
                <div class="swiper-pagination "></div>

                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
        @endif
        @if (count($featured_products) > 0)
            <div class="pt-8 p-2">
                <div class="font-bold text-primary text-2xl">{{__('main.popular_products')}}</div>
            </div>
            <div class="grid grid-cols-2 gap-3  lg:grid-cols-4  lg:gap-8 lg:pt-3 pt-0 p-3   ">
                @foreach ($featured_products as $product)
                    <div class="rounded-xl overflow-clip  flex flex-col mt-3 lg:mt-0 group ">
                        <div class="h-[15rem] overflow-clip">
                            @if ($product->image && file_exists(public_path($product->image)))
                                <img src="{{ $product->image }}" class=" object-cover h-full rounded-xl w-full">
                            @else
                                <img src="{{ asset('assets/images/sample.jpg') }}"
                                    class=" object-cover h-full rounded-xl w-full">
                            @endif
                        </div>
                        <div class="flex flex-col pt-1">
                            <div class="mt-2 text-sm font-normal">{{ $product->name }}</div>
                            <div class="text-xs text-neutral-500 ">
                                @if(!$product->description)
                                <div class="h-[16px] w-full"></div>
                                @endif
                                <div class="text-xs text-neutral-500 truncate w-[90%]">
                                    {{Str::limit($product->description)}}
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <div class="font-medium  pt-1">{{ getFormattedCurrency($product->stitching_cost) }}</div>
                            <button @click="addItemToCart('{{$product->id}}')"
                                class="w-8 h-8 hover:bg-primary hover:text-white flex justify-center items-center rounded-full text-black border border-primary/80 ">
                                <template x-if="getItemCountFromCart({{$product->id}}) == 0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </template>
                                <template x-if="getItemCountFromCart({{$product->id}}) != 0">
                                    <div class="font-semibold text-xs" x-text="'+'+getItemCountFromCart({{$product->id}})"></div>
                                </template>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="p-2 pt-8">
            <div
                class="flex   bg-gradient-to-br bg-black from-secondary to-indigo-950 p-10  w-full rounded-lg  relative group overflow-clip">
                <div class="flex flex-col">
                    <div class="text-white text-4xl   uppercase font-extrabold pb-0 mb-0">{{__('main.book_appointment')}}</div>
                    <div class="text-neutral-100 pt-2 text-sm w-full lg:w-[50%]">{{__('main.book_appointment_text')}}</div>
                    <a href="{{route('frontend.create-appointment')}}"
                        class="mt-5 lg:mt-8 px-6 py-3 border border-white text-white font-bold w-full lg:w-[20rem] hover:bg-white hover:text-black transition-all duration-200 rounded-lg flex justify-center">
                        {{__('main.book_appointment_btn')}}
                    </a>
                </div>
                <a href="#"
                    class="absolute -right-10 top-6 bg-red-500 p-5 py-2 rotate-45 w-[10rem] shadow-lg text-center font-bold text-white">
                    {{__('main.book_now')}}
                </a>
            </div>
        </div>
        @if(count($products) > 0)
        <div class="pt-8 p-2">
            <div class="font-bold text-primary text-2xl">{{__('main.products')}}</div>
        </div>
        <div class="grid grid-cols-2 gap-3  lg:grid-cols-4  lg:gap-8 lg:pt-3 pt-0 p-3   ">
            @foreach ($products as $product)
                <div class="rounded-xl overflow-clip  flex flex-col mt-3 lg:mt-0 group ">
                    <div class="h-[15rem] overflow-clip">
                        @if ($product->image && file_exists(public_path($product->image)))
                            <img src="{{ $product->image }}" class=" object-cover h-full rounded-xl w-full">
                        @else
                            <img src="{{ asset('assets/images/sample.jpg') }}"
                                class=" object-cover h-full rounded-xl w-full">
                        @endif
                    </div>
                    <div class="flex flex-col pt-1">
                        <div class="mt-2 text-sm font-normal">{{ $product->name }}</div>
                        @if(!$product->description)
                        <div class="h-[16px] w-full"></div>
                        @endif
                        <div class="text-xs text-neutral-500 truncate w-[90%] ">
                            {{Str::limit($product->description)}}
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <div class="font-medium  pt-1">{{ getFormattedCurrency($product->stitching_cost) }}</div>
                        <button @click="addItemToCart('{{$product->id}}')"
                            class="w-8 h-8 hover:bg-primary hover:text-white flex justify-center items-center rounded-full text-black border border-primary/80 ">
                            <template x-if="getItemCountFromCart({{$product->id}}) == 0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                            </template>
                            <template x-if="getItemCountFromCart({{$product->id}}) != 0">
                                <div class="font-semibold text-xs" x-text="'+'+getItemCountFromCart({{$product->id}})"></div>
                            </template>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
        @else
        
        @endif

        <div class="pt-8 p-2">
        </div>

    </div>


</div>

@push('js')
    @if(count($sliders) > 0) 

    <script defer>
        const Swiper = window.Swiper;
        const swiper = new Swiper('.swiper', {
            modules: [window.SwiperCustom.Navigation, window.SwiperCustom.Pagination],
            // Optional parameters
            direction: 'horizontal',
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false
            },
            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
            },

            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },

            on: {
                autoplayTimeLeft(s, time, progress) {}
            },

            // And if we need scrollbar
            scrollbar: {
                el: '.swiper-scrollbar',
            },
        });
    </script>
    @endif
    @endpush
    @push('head')
    <script defer>
        'use strict'
        function cartHome()
        {
            return{
                init()
                {
                    this.getProducts()

                    @if(Session::has('clear_items'))
                    this.$dispatch('clear-items',response)
                    @endif
                },
                getProducts()
                {
                    this.$wire.getProducts().then((response) => {
                       this.$dispatch('get-products',response)
                    })
                },
            }
        }
    </script>
@endpush