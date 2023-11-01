<div class="flex w-full items-center  justify-center">
    <div class="flex w-[70rem]  pt-24 flex-col  lg:pb-10 ">
        <div class="lg:pt-8 p-2">
            <div class="flex justify-between items-center lg:px-0 px-4">
                <div class="font-bold text-primary text-2xl">{{__('main.contact_us')}}</div>
                <a href="{{route('frontend')}}" class="px-4 py-2 text-sm flex justify-center items-center gap-2 hover:bg-secondary/20 rounded-lg font-medium shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M20.25 12a.75.75 0 01-.75.75H6.31l5.47 5.47a.75.75 0 11-1.06 1.06l-6.75-6.75a.75.75 0 010-1.06l6.75-6.75a.75.75 0 111.06 1.06l-5.47 5.47H19.5a.75.75 0 01.75.75z" clip-rule="evenodd" />
                    </svg>
                    {{__('main.frontend_home')}}
                </a>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center lg:pt-3 p-2">
            <div class="flex w-full lg:w-fit">
         
                <div class=" h-fit flex flex-col bg-white shadow-[0px_0px_30px_10px_#0000000C] p-5 rounded-lg  lg:w-[42rem] w-[95%]">
                    <div class="font-semibold text-lg tracking-wider">
                        {{__('main.send_us_a_message')}}
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-3  mt-3 text-sm w-full">
                        <div class="flex flex-col lg:col-span-1 col-span-2">
                            <label for="" class="text-sm">{{__('main.name')}}</label>
                            <input type="text" name="country" autocomplete="name" placeholder="{{__('main.enter_your_name')}}"
                                id="" class="border border-black/10  rounded-lg p-2 w-full resize-none mt-2" wire:model="name">
                                @error('name') <span class="error text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="flex flex-col lg:col-span-1 col-span-2">
                            <label for="" class="text-sm">{{__('main.last_name')}}</label>
                            <input type="text" name="last-name" autocomplete="family-name" placeholder="{{__('main.enter_your_last_name')}}"
                                id="" class="border border-black/10  rounded-lg p-2 w-full resize-none mt-2" wire:model="last_name">
                        </div>
                        <div class="flex flex-col lg:col-span-1 col-span-2">
                            <label for="" class="text-sm">{{__('main.email')}}</label>
                            <input type="text" name="email" autocomplete="email" id=""
                                placeholder="{{__('main.enter_email')}}"
                                class="border border-black/10  rounded-lg p-2 w-full resize-none mt-2" wire:model="email">
                                @error('email') <span class="error text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="flex flex-col lg:col-span-1 col-span-2">
                            <label for="" class="text-sm">{{__('main.phone')}}</label>
                            <input type="text" name="mobile" autocomplete="mobile" id=""
                                placeholder="{{__('main.enter_phone_number')}}"
                                class="border border-black/10  rounded-lg p-2 w-full resize-none mt-2" wire:model="phone">
                                @error('phone') <span class="error text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>
                        <div class="flex flex-col col-span-2">
                            <label for="" class="text-sm">{{__('main.message')}}</label>
                            <textarea type="text" name="message" autocomplete="off" id=""
                                placeholder="{{__('main.enter_your_message')}}" rows="8"
                                class="border border-black/10  rounded-lg p-2 w-full resize-none mt-2" wire:model="message"></textarea>
                                @error('message') <span class="error text-xs text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    <button class="w-full px-5 py-3 rounded-lg bg-secondary text-white mt-4 flex items-center gap-3 justify-center group" wire:click.prevent="save">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 group-hover:translate-x-2 transition-transform duration-200">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                            </svg>
                        </div>
                        <div class="">
                            {{__('main.send_message')}}
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>