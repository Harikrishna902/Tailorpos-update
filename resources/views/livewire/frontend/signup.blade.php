<div class="flex w-full items-center  justify-center shrink-0">
    <div class="flex w-[70rem]  pt-24 flex-col  lg:pb-10 items-center  lg:min-h-[calc(100svh-10rem)] justify-center">
        <div class="flex flex-col gap-2 w-full  lg:w-[28rem] p-5 lg:p-9 lg:shadow-[0px_0px_30px_10px_#0000000C] lg:mt-8 rounded-lg lg:h-auto ">
            <div class="">
                <div class="font-bold text-primary text-2xl">{{__('main.sign_up')}}</div>
                <div class="text-sm mt-2 text-neutral-700">{{__('main.enter_your_details')}}</div>
            </div>
            <div class="flex flex-col mt-8 lg:mt-4 text-sm">
                <label class="font-semibold">{{__('main.name')}}</label>
                <input type="text" class="border border-black/10 mt-2 rounded-lg p-2" wire:model="name">
                @error('name') <span class="error text-xs text-red-500">{{ $message }}</span>@enderror
            </div>
            <div class="flex flex-col mt-2 text-sm">
                <label class="font-semibold">{{__('main.email')}}</label>
                <input type="email" class="border border-black/10 mt-2 rounded-lg p-2" wire:model="email">
                @error('email') <span class="error text-xs text-red-500">{{ $message }}</span>@enderror
            </div>
            <div class="flex flex-col mt-2 text-sm">
                <label class="font-semibold">{{__('main.phone')}}</label>
                <input type="number" class="border border-black/10 mt-2 rounded-lg p-2" wire:model="phone">
                @error('phone') <span class="error text-xs text-red-500">{{ $message }}</span>@enderror
            </div>
            <div class="flex flex-col mt-2 text-sm">
                <label class="font-semibold">{{__('main.password')}}</label>
                <input type="password" class="border border-black/10 mt-2 rounded-lg p-2" wire:model="password">
                @error('password') <span class="error text-xs text-red-500">{{ $message }}</span>@enderror
            </div>
            <div class="flex mt-2 justify-between text-sm">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="" id="remember-me" wire:model="terms_conditions">
                    <label for="remember-me">{{__('main.i_agree_to_the')}} <a href="#" class="text-secondary">{{__('main.terms_conditions')}}</a>.</label>
                </div>
            </div>
            @error('terms_conditions') <span class="error text-xs text-red-500">{{ $message }}</span>@enderror

            <button class="w-full p-3 bg-secondary text-white mt-8 lg:mt-4 rounded-lg" wire:click="register">{{__('main.sign_up')}}</button>
        </div>
        <div class="mt-6">{{__('main.already_have_account')}} <a href="{{route('frontend.login')}}" class="text-secondary">{{__('main.login')}}</a></div>
    </div>
</div>