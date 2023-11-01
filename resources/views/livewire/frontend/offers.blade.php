<div class="flex w-full items-center  justify-center">
    <div class="flex w-[70rem]  pt-24 flex-col  lg:pb-10 ">
        
        <div class="lg:pt-8 p-2">
            <div class="font-bold text-primary text-2xl">{{__('main.offers')}}</div>
        </div>

        @if(count($offers) > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3 lg:gap-8 lg:pt-3 pt-0 p-3   ">
            @foreach ($offers as $item)
            <a href="{{$item->url ? $item->url : '#'}}" class="h-[300px] aspect-video w-full rounded-lg overflow-clip">
                    <img src="{{$item->photo()}}" class="object-cover h-full w-full" alt="">
            </a>
            @endforeach
        </div>
        @else
        <div class="w-full h-[40dvh] flex justify-center items-center flex-col gap-1">
            <div class="text-2xl font-bold text-secondary">{{__('main.no_offers_title')}}</div>
            <div class="text-sm text-secondary/70 font-semibold">{{__('main.no_offers_available')}}</div>
        </div>
        @endif
    </div>
</div>