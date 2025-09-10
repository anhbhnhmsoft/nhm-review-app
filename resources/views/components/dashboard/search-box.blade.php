<div
    x-data="{
        openSearch: false,
        init() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((pos) => {
                    $wire.set('lat', pos.coords.latitude);
                    $wire.set('lng', pos.coords.longitude);
                });
            }
        }
    }"
    class="absolute flex top-[-30px] lg:top-[-50px] left-0 right-0 z-10">
    <div class="relative grid grid-cols-4 lg:grid-cols-5 w-full items-center p-2 lg:px-[40px] lg:py-[20px] gap-4 bg-white rounded-lg shadow-sm">
        <input type="text"
               placeholder="Bạn muốn tìm kiếm gì và ở đâu? Hãy tìm trên Afy ngay nhé"
               autocomplete="off"
               class="input !border-none !outline-none lg:px-[25px] lg:!py-[23px] w-full col-span-3"
               x-on:focus="openSearch = true"
               x-on:click.away="openSearch = false"
               wire:model.live.debounce.300ms="search"
        />
        <a href="{{ route('frontend.search-store', ['filters' => ['keyword' => trim($search ?? '')], 'sortBy' => ($search ?? '') === '' ? 'distance' : null, 'lat' => $lat, 'lng' => $lng]) }}"
           class="btn text-white bg-blue-600 hover:bg-blue-700 rounded-lg lg:px-[25px] lg:py-[23px] ">
            <i class="fa-solid fa-magnifying-glass"></i>
            <span class="text-base hidden lg:inline"> Tìm kiếm</span>
        </a>
        <a href="{{ route('frontend.search-store') }}"
           class="btn text-white bg-green-600 hover:bg-green-700 rounded-lg px-[25px] py-[23px] hidden lg:flex">
            <span class="text-base">Tìm địa điểm</span>
        </a>

        <div x-show="openSearch" x-transition
             class="absolute left-2 right-2 top-[64px] lg:left-[40px] lg:right-[40px] lg:top-[88px] bg-white rounded-lg shadow-xl border border-gray-100 overflow-hidden">
            @if($stores && $stores->count() > 0)
                <ul class="divide-y divide-gray-100 max-h-[360px] overflow-auto">
                    @foreach($stores as $store)
                        <li>
                            <a href="{{ route('frontend.store', ['slug' => $store->slug]) }}" class="flex items-center gap-3 p-3 hover:bg-gray-50">
                                <img src="{{ \App\Utils\HelperFunction::generateURLImagePath($store->logo_path) }}" alt="{{ $store->name }}" class="w-10 h-10 rounded object-cover"/>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 truncate">{{ $store->name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ $store->address }}</p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                    <li>
                        <a href="{{ route('frontend.search-store', ['filters' => ['keyword' => trim($search ?? '')], 'sortBy' => ($search ?? '') === '' ? 'distance' : null, 'lat' => $lat, 'lng' => $lng]) }}"
                           class="flex items-center gap-2 p-3 hover:bg-gray-50 text-green-600">
                            <i class="fa-solid fa-magnifying-glass-plus"></i>
                            <span>Xem tất cả tìm kiếm cho "{{ trim($search ?? '') }}"</span>
                        </a>
                    </li>
                </ul>
            @else
                <div class="p-4 text-sm text-gray-500">Không có kết quả</div>
            @endif
        </div>
    </div>
</div>
