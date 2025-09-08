<div x-data="{
    order_by: @entangle('order_by'),
}">
    <div class="w-full flex items-center justify-between gap-4 flex-wrap">
        <h1 class="uppercase font-bold text-xl lg:text-4xl">{{$category->name}}</h1>
        <div class="inline-flex w-fit items-center gap-2">
            <button
                wire:click="sortOrder('created_at')"
                class="btn btn-sm rounded-2xl bg-gray-200 hover:bg-gray-100" :class="order_by == 'created_at' && 'bg-green-600 hover:bg-green-400 text-white'">
                Mới nhất
            </button>
            <button
                wire:click="sortOrder('rating')"
                class="btn btn-sm rounded-2xl bg-gray-200 hover:bg-gray-100" :class="order_by == 'rating' && 'bg-green-600 hover:bg-green-400 text-white'" >
                Đánh giá cao nhất
            </button>
            <button
                wire:click="sortOrder('view')"
                class="btn btn-sm rounded-2xl bg-gray-200 hover:bg-gray-100" :class="order_by == 'view' && 'bg-green-600 hover:bg-green-400 text-white'">
                Lượt xem nhiều nhất
            </button>
        </div>
        <div class="hidden lg:inline-flex items-center gap-2">
            @foreach($category_child as $child)
                <a href="{{route('frontend.search-store',['filters' => ['category_id' => $child->id]])}}" class="font-medium hover:text-blue-500 transition-all duration-200">
                    {{$child->name}}
                </a>
                <span class="font-medium">-</span>
            @endforeach
            <a href="{{route('frontend.search-store',['filters' => ['category_id' => $category->id]])}}" class="font-medium  text-blue-500">
                Xem tất cả
            </a>
        </div>
    </div>
    <div class="relative mt-10">
        @if($stores->count() > 0)
            <div class="swiper-container !w-full !h-fit overflow-hidden" data-swiper-id="{{ $this->getId() }}">
                <div class="swiper-wrapper py-4 !w-full !h-fit">
                    @foreach($stores as $store)
                        <div class="swiper-slide !h-fit" wire:key="{{$store->id . time()}}">
                            <livewire:dashboard.card-store :store="$store"   :key="$store->id . time()" />
                        </div>
                    @endforeach
                </div>
                <div
                    class="swiper-button-prev">
                    <i class="fa-solid fa-chevron-left text-gray-600"></i>
                </div>
                <div
                    class="swiper-button-next">
                    <i class="fa-solid fa-chevron-right text-gray-600"></i>
                </div>
            </div>
        @else
            <div role="alert" class="alert alert-warning lg:mt-16">
                <i class="fa-solid fa-triangle-exclamation text-4xl"></i>
                <span>Hiện tại không có địa điểm nào</span>
            </div>
        @endif
    </div>
    @script
    <script>
        if (!window.swiperInstances) {
            window.swiperInstances = {};
        }

        const componentId = $wire.__instance.id;

        const initThisSwiper = () => {
            if (window.swiperInstances[componentId]) {
                try {
                    window.swiperInstances[componentId].destroy(true, true);
                } catch (e) {}
            }

            setTimeout(() => {
                const swiperEl = document.querySelector(`[data-swiper-id="${componentId}"]`);
                if (swiperEl) {
                    window.swiperInstances[componentId] = new window.Swiper(swiperEl, {
                        slidesPerView: 1,
                        spaceBetween: 30,
                        navigation: {
                            nextEl: swiperEl.querySelector(".swiper-button-next"),
                            prevEl: swiperEl.querySelector(".swiper-button-prev"),
                        },
                        speed: 500,
                        breakpoints: {
                            640: { slidesPerView: 2, spaceBetween: 16 },
                            1024: { slidesPerView: 4 },
                        }
                    });
                }
            }, 100);
        };

        initThisSwiper();

        $wire.on('refresh-swiper', () => {
            initThisSwiper();
        });
    </script>
    @endscript
</div>
