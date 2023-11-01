<div class="flex w-full items-center  justify-center">
    <div class="flex w-[70rem]  pt-24 flex-col  lg:pb-10 ">
        
        <div class="lg:pt-8 p-2">
            <div class="flex justify-between items-center">
                <div class="font-bold text-primary text-2xl">{{__('main.terms_conditions')}}</div>
                <a href="{{route('frontend')}}" class="px-4 py-2 text-sm flex justify-center items-center gap-2 hover:bg-secondary/20 rounded-lg font-medium shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                        <path fill-rule="evenodd" d="M20.25 12a.75.75 0 01-.75.75H6.31l5.47 5.47a.75.75 0 11-1.06 1.06l-6.75-6.75a.75.75 0 010-1.06l6.75-6.75a.75.75 0 111.06 1.06l-5.47 5.47H19.5a.75.75 0 01.75.75z" clip-rule="evenodd" />
                    </svg>
                    {{__('main.frontend_home')}}
                </a>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center lg:pt-3 p-2">
            {!! $content ? $content : 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, aspernatur assumenda maiores modi quam quia officiis et dolorem quo ea, a consequatur facilis error vero sunt voluptatibus repellendus rerum nostrum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, aspernatur assumenda maiores modi quam quia officiis et dolorem quo ea, a consequatur facilis error vero sunt voluptatibus repellendus rerum nostrum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, aspernatur assumenda maiores modi quam quia officiis et dolorem quo ea, a consequatur facilis error vero sunt voluptatibus repellendus rerum nostrum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, aspernatur assumenda maiores modi quam quia officiis et dolorem quo ea, a consequatur facilis error vero sunt voluptatibus repellendus rerum nostrum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, aspernatur assumenda maiores modi quam quia officiis et dolorem quo ea, a consequatur facilis error vero sunt voluptatibus repellendus rerum nostrum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, aspernatur assumenda maiores modi quam quia officiis et dolorem quo ea, a consequatur facilis error vero sunt voluptatibus repellendus rerum nostrum.
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, aspernatur assumenda maiores modi quam quia officiis et dolorem quo ea, a consequatur facilis error vero sunt voluptatibus repellendus rerum nostrum.
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, aspernatur assumenda maiores modi quam quia officiis et dolorem quo ea, a consequatur facilis error vero sunt voluptatibus repellendus rerum nostrum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, aspernatur assumenda maiores modi quam quia officiis et dolorem quo ea, a consequatur facilis error vero sunt voluptatibus repellendus rerum nostrum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, aspernatur assumenda maiores modi quam quia officiis et dolorem quo ea, a consequatur facilis error vero sunt voluptatibus repellendus rerum nostrum. Lorem ipsum dolor sit amet consectetur adipisicing elit. Magnam, aspernatur assumenda maiores modi quam quia officiis et dolorem quo ea, a consequatur facilis error vero sunt voluptatibus repellendus rerum nostrum.' !!}
         </div>
    </div>
</div>