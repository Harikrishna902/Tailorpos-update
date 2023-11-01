<div class="flex w-full items-center  justify-center shrink-0">
    <div class="flex w-[70rem]  pt-24 flex-col  lg:pb-10 items-center  lg:min-h-[calc(100svh-10rem)] justify-center">
        <div class="flex flex-col gap-2 w-full  lg:w-[28rem] p-5 lg:p-9 lg:shadow-[0px_0px_30px_10px_#0000000C] lg:mt-8 rounded-lg lg:h-auto ">
            <div class="">
                <div class="font-bold text-primary text-2xl">{{__('main.login')}}</div>
                <div class="text-sm mt-2 text-neutral-700">{{__('main.login_welcome_back')}}</div>
            </div>
            <div class="flex flex-col mt-8 lg:mt-4 text-sm">
                <label class="font-semibold">{{__('main.email')}}</label>
                <input type="text" class="border border-black/10 mt-2 rounded-lg p-2" wire:model="email">
                @error('email') <span class="error text-xs text-red-500">{{ $message }}</span>@enderror
            </div>
            <div class="flex flex-col mt-2 text-sm">
                <label class="font-semibold">{{__('main.password')}}</label>
                <input type="password" class="border border-black/10 mt-2 rounded-lg p-2" wire:model="password">
                @error('password') <span class="error text-xs text-red-500">{{ $message }}</span>@enderror
            </div>
            @error('login_error') <span class="error text-xs text-red-500">{{ $message }}</span>@enderror
            <div class="flex mt-2 justify-between text-sm">
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="" id="remember-me">
                    <label for="remember-me">{{__('main.remember_me')}}</label>
                </div>
                <div class="">
                    <a href="#" class="text-secondary hover:underline">{{__('main.forgot_password')}}</a>
                </div>
            </div>
            <button class="w-full p-3 bg-secondary text-white mt-8 lg:mt-4 rounded-lg" wire:click.prevent="login">{{__('main.login')}}</button>
        </div>
        <div class="mt-6">{{__('main.dont_have_account')}} <a href="{{route('frontend.sign-up')}}" class="text-secondary">{{__('main.sign_up')}}</a></div>
    </div>
</div>