<div class="mx-auto w-full max-w-[1200px] sm:px-4 sm:py-6"
     x-data="{ mobileFilterOpen: false }"
     x-init="window.addEventListener('geolocation-updated', (e) => {
    $wire.set('userLat', e.detail.lat);
    $wire.set('userLng', e.detail.lng);
});
if (window.GeoPlugin?.state.user.lat && window.GeoPlugin?.state.user.lng) {
    $wire.set('userLat', window.GeoPlugin.state.user.lat);
    $wire.set('userLng', window.GeoPlugin.state.user.lng);
}">
    <div class="lg:flex">
        <div class="md:w-1/4 hidden lg:flex flex-col p-3">
            <div class="w-full h-[152px] bg-cover bg-center flex items-center justify-center rounded-xl mb-5"
                style="background-image: url('{{ asset('images/img_map.png') }}');">
                <x-button label="Xem bản đồ" />
            </div>
            <div class="rounded-lg bg-white p-4 shadow-sm">
                <h2 class="mb-3 text-xl font-semibold py-4 border-b-1 border-gray-200">Lọc kết quả</h2>
                <x-select title="Giờ mở cửa" :options="['all' => 'Tất cả', 'open' => 'Đang mở cửa']" type="radio" model="openingNow" />
                <x-select title="Danh mục" :options="$categories" type="checkbox" model="selectedCategories" />
                <div class="mb-4">
                    <x-select title="Tỉnh/Thành phố" :options="$provinces" type="radio" model="selectedProvince"
                        search="true" />
                    @if (!empty($districts))
                        <x-select title="Quận/Huyện" :options="$districts" type="radio" model="selectedDistrict"
                            search="true" />
                    @endif
                    @if (!empty($wards))
                        <x-select title="Phường/Xã" :options="$wards" type="radio" model="selectedWard"
                            search="true" />
                    @endif
                </div>
                <x-select title="Tiện ích" :options="$utilities" type="checkbox" model="selectedUtilities" />
            </div>
        </div>
        <div class="lg:w-3/4 p-1 md:p-3">
            <div class="mb-2.5 flex items-center justify-between">
                <div class="text-lg items-center gap-2">
                    <span><b>{{ $stores->total() }}</b> địa điểm khớp với tìm kiếm của bạn:</span>
                    @php
                        $hasAnyFilter = ($openingNow !== 'all') || !empty($selectedCategories) || !empty($selectedUtilities) || $selectedProvince || $selectedDistrict || $selectedWard;
                    @endphp
                    @if ($hasAnyFilter)
                        <button wire:click="clearFilter('all')" class="text-sm underline text-gray-600 hover:text-red-600">Xóa tất cả bộ lọc</button>
                    @endif
                    <div>
                        @if ($openingNow !== 'all')
                            <span class="badge badge-outline border-green-500 text-green-700 gap-1 h-8">
                                Đang mở cửa
                                <button wire:click="clearFilter('openingNow')" class="ml-1">×</button>
                            </span>
                        @endif
                        @foreach (($selectedCategories ?? []) as $cid)
                            <span class="badge badge-outline border-green-500 text-green-700 gap-1 h-8">
                                {{ $categories[$cid] ?? ('Danh mục #'.$cid) }}
                                <button wire:click="clearFilter('category', {{ (int)$cid }})" class="ml-1">×</button>
                            </span>
                        @endforeach
                        @foreach (($selectedUtilities ?? []) as $uid)
                            <span class="badge badge-outline border-green-500 text-green-700 gap-1 h-8">
                                {{ $utilities[$uid] ?? ('Tiện ích #'.$uid) }}
                                <button wire:click="clearFilter('utility', {{ (int)$uid }})" class="ml-1">×</button>
                            </span>
                        @endforeach
                        @if ($selectedProvince)
                            <span class="badge badge-outline border-green-500 text-green-700 gap-1 h-8">
                                {{ $provinces[$selectedProvince] ?? ('Tỉnh '.$selectedProvince) }}
                                <button wire:click="clearFilter('province')" class="ml-1">×</button>
                            </span>
                        @endif
                        @if ($selectedDistrict)
                            <span class="badge badge-outline border-green-500 text-green-700 gap-1 h-8">
                                {{ $districts[$selectedDistrict] ?? ('Quận/Huyện '.$selectedDistrict) }}
                                <button wire:click="clearFilter('district')" class="ml-1">×</button>
                            </span>
                        @endif
                        @if ($selectedWard)
                            <span class="badge badge-outline border-green-500 text-green-700 gap-1 h-8">
                                {{ $wards[$selectedWard] ?? ('Phường/Xã '.$selectedWard) }}
                                <button wire:click="clearFilter('ward')" class="ml-1">×</button>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="md:flex hidden items-center gap-3 min-w-0">
                    <div class="whitespace-nowrap">Sắp xếp theo:</div>
                    <select wire:model.live="sortBy" class="select select-bordered w-36 shrink-0">
                        <option value="#">Đúng nhất</option>
                        <option value="rating">Điểm đánh giá</option>
                        <option value="distance">Gần tôi nhất</option>
                    </select>
                </div>
            </div>
            @foreach ($stores ?? [] as $store)
                <div wire:key="store-{{ $store->id }}">
                <x-card-product
                    :image="$store->image_url"
                    :title="$store->name"
                    :description="$store->short_description ?? $store->description"
                    :overall_rating="(float) ($store->overall_rating ?? 0)"
                    :reviews_count="$store->reviews_count"
                    :address="$store->address"
                    :status="$store->status"
                    :status_label="$store->status_label"
                    :opening_time="$store->opening_time"
                    :closing_time="$store->closing_time"
                    :latitude="$store->latitude"
                    :longitude="$store->longitude"
                    :distance="$store->distance_km ?? null"
                    href="#" />
                </div>
            @endforeach
            <div class="mt-5">
                {{ $stores->onEachSide(1)->links() }}
            </div>
        </div>
    </div>

    <div class="fixed bottom-2 left-0 right-0 flex justify-center gap-3 z-40 lg:hidden">
        <button @click="mobileFilterOpen = true" class="btn btn-outline bg-white shadow-sm border-gray-200 px-4 h-10 rounded-3xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4.5A1.5 1.5 0 0 1 4.5 3h15A1.5 1.5 0 0 1 21 4.5v.026a1.5 1.5 0 0 1-.439 1.06l-6.495 6.495a1.5 1.5 0 0 0-.439 1.06V19.5a1.5 1.5 0 0 1-2.121 1.342l-2.25-1.05A1.5 1.5 0 0 1 8 18.45v-4.309a1.5 1.5 0 0 0-.439-1.06L1.066 5.586A1.5 1.5 0 0 1 .627 4.526V4.5Z"/></svg>
            Bộ lọc
        </button>
        <a href="#map" class="btn btn-outline bg-white shadow-sm border-gray-200 px-4 h-10 rounded-3xl">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-1" viewBox="0 0 24 24" fill="currentColor"><path d="M9 3.75a.75.75 0 0 0-.345.083L5.4 5.25 2.345 3.833A.75.75 0 0 0 1.5 4.5v14.25a.75.75 0 0 0 1.155.632L5.4 18.75l3.255 1.632a.75.75 0 0 0 .69 0l3.255-1.632 3.255 1.632a.75.75 0 0 0 1.095-.632V4.5a.75.75 0 0 0-1.095-.667L12.6 5.25 9.345 3.833A.75.75 0 0 0 9 3.75Z"/></svg>
            Bản đồ
        </a>
    </div>

    <div x-show="mobileFilterOpen" x-transition class="fixed inset-0 z-50 lg:hidden">
        <div class="absolute inset-0 bg-black/50" @click="mobileFilterOpen = false"></div>
        <div class="absolute inset-x-0 bottom-0 top-0 bg-white rounded-t-2xl flex flex-col">
            <div class="px-4 py-3 bg-[#52ab5c] text-white rounded-t-2xl flex items-center justify-between">
                <div class="text-lg font-semibold">Bộ lọc</div>
                <button @click="mobileFilterOpen = false" class="w-8 h-8 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" fill="currentColor"><path d="M6.225 4.811a1 1 0 0 0-1.414 1.414L10.586 12l-5.775 5.775a1 1 0 1 0 1.414 1.414L12 13.414l5.775 5.775a1 1 0 0 0 1.414-1.414L13.414 12l5.775-5.775a1 1 0 0 0-1.414-1.414L12 10.586 6.225 4.811Z"/></svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-4">
                <x-select title="Giờ mở cửa" :options="['all' => 'Tất cả', 'open' => 'Đang mở cửa']" type="radio" model="openingNow" />
                <x-select title="Danh mục" :options="$categories" type="checkbox" model="selectedCategories" />
                <div class="mb-4">
                    <x-select title="Tỉnh/Thành phố" :options="$provinces" type="radio" model="selectedProvince" search="true" />
                    @if (!empty($districts))
                        <x-select title="Quận/Huyện" :options="$districts" type="radio" model="selectedDistrict" search="true" />
                    @endif
                    @if (!empty($wards))
                        <x-select title="Phường/Xã" :options="$wards" type="radio" model="selectedWard" search="true" />
                    @endif
                </div>
                <x-select title="Tiện ích" :options="$utilities" type="checkbox" model="selectedUtilities" />
            </div>
            <div class="p-4 flex items-center gap-3">
                <button @click="mobileFilterOpen = false" class="btn bg-[#52ab5c] text-white flex-[5_1_0%]">Áp dụng</button>
                <button @click="$wire.clearFilter('all')" class="btn btn-outline flex-[2_1_0%]">Đặt lại</button>
            </div>
        </div>
    </div>
</div>
@vite(['resources/js/geo.js'])
