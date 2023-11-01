<div class="pb-[3.6rem] lg:pb-0 flex flex-col">
    <div class="text-primary z-10">
        <svg id="svg" xmlns="http://www.w3.org/2000/svg" viewBox="-300 0 950 270" class="h-32 w-full"
            preserveAspectRatio="none">
            <path d="M-314,267 C105,364 400,100 812,279" fill="currentColor" stroke="currentColor" stroke-width="120"
                stroke-linecap="round" />
        </svg>
    </div>
    <div class="flex  w-full bg-primary flex-col lg:items-center">
        <div class="flex lg:w-[70rem] justify-between flex-col lg:flex-row lg:p-5 p-5">
            <div class=" text-white lg:pt-0 pt-8">
                <div class="font-bold text-white text-2xl pt-5 italic">{{getApplicationName()}}</div>
                <div class="font-semibold">{{__('main.footer')}}</div>
            </div>
            <div class="  text-white lg:pt-0 pt-8 flex flex-col">
                <div class="font-bold text-white text-lg pt-5">{{__('main.useful_links')}}</div>
                <a href="{{route('frontend.contact-us')}}" class=" pt-4 text-sm">{{__('main.contact_us')}}</a>
                <a href="{{route('frontend.privacy-policy')}}" class=" pt-2 text-sm">{{__('main.privacy_policy')}}</a>
                <a href="{{route('frontend.terms-conditions')}}" href="#"iv class=" pt-2 text-sm">{{__('main.terms_conditions')}}</a>
                <a href="{{route('frontend.create-appointment')}}" href="#"iv class=" pt-2 text-sm">{{__('main.book_appointment')}}</a>
            </div>
            <div class="  text-white lg:pt-0 pt-8">
                <a href="#" class=" text-sm flex gap-2 items-center pt-5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                      </svg>
                    {{$email}}
                </a>
                <a href="#" class=" flex gap-2 items-center pt-2 text-sm">
                    <div class=" text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                        </svg>
                    </div>
                    {{getCountryCode()}} {{$phone}}
                </a>
            </div>
        </div>

        <div class=" border-t border-white/5 text-center text-white text-sm w-full mt-8 p-5 ">
            {{__('main.footer_copyright')}}
        </div>
    </div>
</div>
