<div class="flex w-full items-center  justify-center shrink-0">
    <div class="flex w-[70rem]  pt-24 flex-col  lg:pb-10 ">
        <div class="lg:pt-8 p-2">
            <div class="flex justify-between items-center">
                <div class="font-bold text-primary text-2xl">{{__('main.edit_profile')}}</div>
                <a href="{{ route('frontend') }}"
                    class="px-4 py-2 text-sm flex justify-center items-center gap-2 hover:bg-secondary/20 rounded-lg font-medium shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd"
                            d="M20.25 12a.75.75 0 01-.75.75H6.31l5.47 5.47a.75.75 0 11-1.06 1.06l-6.75-6.75a.75.75 0 010-1.06l6.75-6.75a.75.75 0 111.06 1.06l-5.47 5.47H19.5a.75.75 0 01.75.75z"
                            clip-rule="evenodd" />
                    </svg>
                    {{__('main.frontend_home')}}
                </a>
            </div>
        </div>

        <div class="flex lg:flex-row flex-col gap-8 p-2">
            <div class="shadow-[0px_0px_30px_10px_#00000007]  flex flex-col rounded-lg w-full p-5 h-fit  grow-0 ">

                <div class="flex gap-8 items-center lg:flex-row flex-col">
                    <div class="flex flex-col lg:col-span-2 ">
                        <div
                            class="h-32 w-32 rounded-full bg-neutral-200 outline-dashed outline-1 outline-primary outline-offset-2 relative ">
                            @if (Auth::guard('customer')->user()->avatar && !$profileImage)
                                <img src="{{ Auth::guard('customer')->user()->avatar }}" alt=""
                                    class="w- object-cover h-32 w-32 rounded-full">
                            @elseif($profileImage)
                                <img src="{{ $profileImage->temporaryUrl() }}"
                                    class="w- object-cover h-32 w-32 rounded-full">
                            @endif
                            <div class="w-fit absolute bottom-0 right-0">
                                <a href="#" @click.prevent="$refs.input.click()"
                                    class="bg-primary text-white rounded-full  p-2 flex justify-center items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </a>
                            </div>
                            <input type="file" class="hidden" accept="image/*" x-ref="input"
                                wire:model="profileImage">
                        </div>

                    </div>
                    <div class="grid lg:grid-cols-2 grid-cols-1 gap-4 mt-2 text-sm w-full">

                        <div class="flex flex-col">
                            <label for="" class="text-sm">{{__('main.name')}}<span class="text-red-400">*</span></label>
                            <input type="text" name="country" autocomplete="name" placeholder="{{__('main.enter_your_name')}}"
                                id="" class="border border-black/10 mt-1  rounded-lg p-2 w-full resize-none"
                                wire:model="name">
                        </div>
                        <div class="flex flex-col">
                            <label for="" class="text-sm">{{__('main.email')}}<span class="text-red-400">*</span></label>
                            <input type="text" name="email" autocomplete="email" id=""
                                placeholder="{{__('main.enter_your_email')}}"
                                class="border border-black/10 mt-1  rounded-lg p-2 w-full resize-none" wire:model="email">
                        </div>
                        <div class="flex flex-col">
                            <label for="" class="text-sm">{{__('main.phone')}}<span class="text-red-400">*</span></label>
                            <input type="text" name="phone" autocomplete="phone" id=""
                                placeholder="{{__('main.enter_your_phone')}}"
                                class="border border-black/10 mt-1  rounded-lg p-2 w-full resize-none" wire:model="phone">
                        </div>
                        <div class="flex flex-col">
                            <label for="" class="text-sm">{{__('main.password')}}</label>
                            <input type="password" name="password" autocomplete="new-password" id=""
                                placeholder="{{__('main.enter_your_password')}}"
                                class="border border-black/10 mt-1  rounded-lg p-2 w-full resize-none" wire:model="password">
                        </div>
                      
                    </div>

                </div>
                <div class="flex justify-end items-center lg:col-span-2 mt-7">
                    <button class="w px-5 py-3 rounded-lg bg-secondary text-white" wire:click="save">{{__('main.update_profile')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>