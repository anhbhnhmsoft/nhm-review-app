<div class="mx-auto w-full max-w-[1200px] px-4 py-6" x-data x-init="window.addEventListener('geolocation-updated', (e) => {
    $wire.set('userLat', e.detail.lat);
    $wire.set('userLng', e.detail.lng);
});
if (window.GeoPlugin?.state.user.lat && window.GeoPlugin?.state.user.lng) {
    $wire.set('userLat', window.GeoPlugin.state.user.lat);
    $wire.set('userLng', window.GeoPlugin.state.user.lng);
}">
    <div class="md:flex">
        <div class="md:w-1/4 flex flex-col p-3">
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
        <div class="md:w-3/4 p-3">
            <div class="mb-2.5 flex items-center justify-between">
                <div class="text-lg items-center gap-2">
                    <span><b>{{ $stores->total() }}</b> địa điểm khớp với tìm kiếm của bạn:</span>
                    @php
                        $hasAnyFilter = ($openingNow !== 'all') || !empty($selectedCategories) || !empty($selectedUtilities) || $selectedProvince || $selectedDistrict || $selectedWard;
                    @endphp
                    @if ($hasAnyFilter)
                        <button wire:click="clearAllFilters" class="text-sm underline text-gray-600 hover:text-red-600">Xóa tất cả bộ lọc</button>
                    @endif
                    <div>    
                        @if ($openingNow !== 'all')
                            <span class="badge badge-outline border-green-500 text-green-700 gap-1 h-8">
                                Đang mở cửa
                                <button wire:click="clearOpeningNow" class="ml-1">×</button>
                            </span>
                        @endif
                        @foreach (($selectedCategories ?? []) as $cid)
                            <span class="badge badge-outline border-green-500 text-green-700 gap-1 h-8">
                                {{ $categories[$cid] ?? ('Danh mục #'.$cid) }}
                                <button wire:click="removeCategory({{ (int)$cid }})" class="ml-1">×</button>
                            </span>
                        @endforeach
                        @foreach (($selectedUtilities ?? []) as $uid)
                            <span class="badge badge-outline border-green-500 text-green-700 gap-1 h-8">
                                {{ $utilities[$uid] ?? ('Tiện ích #'.$uid) }}
                                <button wire:click="removeUtility({{ (int)$uid }})" class="ml-1">×</button>
                            </span>
                        @endforeach
                        @if ($selectedProvince)
                            <span class="badge badge-outline border-green-500 text-green-700 gap-1 h-8">
                                {{ $provinces[$selectedProvince] ?? ('Tỉnh '.$selectedProvince) }}
                                <button wire:click="clearProvince" class="ml-1">×</button>
                            </span>
                        @endif
                        @if ($selectedDistrict)
                            <span class="badge badge-outline border-green-500 text-green-700 gap-1 h-8">
                                {{ $districts[$selectedDistrict] ?? ('Quận/Huyện '.$selectedDistrict) }}
                                <button wire:click="clearDistrict" class="ml-1">×</button>
                            </span>
                        @endif
                        @if ($selectedWard)
                            <span class="badge badge-outline border-green-500 text-green-700 gap-1 h-8">
                                {{ $wards[$selectedWard] ?? ('Phường/Xã '.$selectedWard) }}
                                <button wire:click="clearWard" class="ml-1">×</button>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-3 min-w-0">
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
                    :rating="(float) ($store->reviews_avg_rating ?? 0)"
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
</div>
@vite(['resources/js/geo.js'])
