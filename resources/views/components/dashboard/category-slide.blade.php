<div>
    <div class="w-full inline-flex items-center justify-between gap-4">
        <h1 class="uppercase font-bold text-4xl">Ẩm thực</h1>
        <div class="inline-flex w-fit items-center gap-2">
            <button class="btn btn-sm rounded-2xl bg-green-600 hover:bg-green-400 text-white">
                Mới nhất
            </button>
            <button class="btn btn-sm rounded-2xl bg-gray-200 hover:bg-gray-100">
                Đánh giá cao nhất
            </button>
            <button class="btn btn-sm rounded-2xl bg-gray-200 hover:bg-gray-100">
                Lượt xem nhiều nhất
            </button>
        </div>
        <div class="inline-flex items-center gap-2">
            <a href="#" class="font-medium hover:text-blue-500 transition-all duration-200">
                Ăn uống
            </a>
            <span class="font-medium">-</span>
            <a href="#" class="font-medium hover:text-blue-500 transition-all duration-200">
                Quán nhậu
            </a>
            <span class="font-medium">-</span>
            <a href="#" class="font-medium hover:text-blue-500 transition-all duration-200">
                Nhà hàng
            </a>
            <span class="font-medium">-</span>
            <a href="#" class="font-medium  text-blue-500">
                Xem tất cả
            </a>
        </div>
    </div>
    <div class="relative mt-10">
        <div class="swiper-container !w-full !h-fit overflow-hidden store__category">
            <div class="swiper-wrapper py-4 !w-full !h-fit">
                @foreach(range(0, 6) as $number)
                    <div class="swiper-slide !h-fit">
                        <x-card-store/>
                    </div>
                @endforeach
            </div>
            <div class="swiper-button-prev !w-[40px] !h-[40px] bg-white border rounded-full !border-gray-600 !left-[-23px] shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="!size-5 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </div>
            <div class="swiper-button-next !w-[40px] !h-[40px] bg-white border rounded-full !border-gray-600 !right-[-23px] shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="!size-5 text-gray-600">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </div>
        </div>
    </div>
</div>
