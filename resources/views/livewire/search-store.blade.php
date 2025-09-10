<div class="mx-auto w-full max-w-[1200px] sm:px-4 sm:py-6"
     x-data="{
        mobileFilterOpen: false,
        mapModal: false,
        lat: @entangle('lat').live,
        lng: @entangle('lng').live,
        async getLoc(){
          try {
            if ($GeoPlugin.hasStoredLocation()) {
              const stored = $GeoPlugin.getStoredLocation();
              this.lat = stored.lat;
              this.lng = stored.lng;
              $GeoPlugin.clearStoredLocation();
              return;
            }
            
            const p = await $GeoPlugin.getCurrentLocation()
            this.lat = p.lat
            this.lng = p.lng
          } catch (e) {
            console.error(e)
          } finally { this.loading = false }
        }
    }"
     x-init="getLoc()"
     x-effect="getLoc()"
>
    @section('vite_includes')
        @vite(['resources/css/map.css'])
    @endsection
    <div class="lg:flex">
        <div class="md:w-1/4 hidden lg:flex flex-col p-3">
            <div class="w-full h-[152px] bg-cover bg-center flex items-center justify-center rounded-xl mb-5"
                 style="background-image: url('{{ asset('images/img_map.png') }}');">
                <button class="btn btn-outline bg-white" @click="mapModal = true">
                    Xem bản đồ
                </button>
            </div>
            <div class="rounded-lg bg-white p-4 shadow-sm">
                <h2 class="mb-3 text-xl font-semibold py-4 border-b-1 border-gray-200">Lọc kết quả</h2>
                <x-select title="Giờ mở cửa" :options="['all' => 'Tất cả', 'open' => 'Đang mở cửa']" type="radio"
                          model="filters.opening_now"/>
                <x-select title="Danh mục" :options="$categories" type="checkbox" model="filters.category_ids"
                          search="true"/>
                <div class="mb-4">
                    <x-select title="Tỉnh/Thành phố" :options="$provinces" type="radio" model="filters.province_code"
                              search="true"/>
                    @if (!empty($filters['province_code']))
                        <x-select title="Quận/Huyện" :options="$districts" type="radio" model="filters.district_code"
                                  search="true"/>
                    @endif
                    @if (!empty($filters['district_code']))
                        <x-select title="Phường/Xã" :options="$wards" type="radio" model="filters.ward_code"
                                  search="true"/>
                    @endif
                </div>
                <x-select title="Tiện ích" :options="$utilities" type="checkbox" model="filters.utilities"/>
            </div>
        </div>
        <div class="lg:w-3/4 p-1 md:p-3">
            <div class="mb-2.5 flex items-center justify-between">
                <div class="text-lg items-center gap-2">
                    <span><b>{{ $stores->total() }}</b> địa điểm khớp với tìm kiếm của bạn:</span>
                    @if ($this->hasActiveFilters())
                        <button wire:click="setDefaultFilters()" class="btn btn-primary-green btn-xs py-1 px-2">
                            Xóa bộ lọc
                        </button>
                    @endif
                    <div>
                        @if ($filters['opening_now'] == 'open')
                            <button
                                wire:click="clearFilter('opening_now')"
                                type="button"
                                class="badge badge-outline border-green-500 text-green-600 inline-flex items-center px-3 py-2 gap-1 cursor-pointer hover:bg-green-500 hover:text-white transition-all duration-200">
                                Đang mở cửa
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        @endif
                        @foreach (($filters['category_ids'] ?? []) as $category_id)
                            <button
                                wire:click="clearFilter('category_ids', '{{$category_id}}')"
                                type="button"
                                class="badge badge-outline border-green-500 text-green-600 inline-flex items-center px-3 py-2 gap-1 cursor-pointer hover:bg-green-500 hover:text-white transition-all duration-200">
                                {{ $categories[$category_id] ?? ('Danh mục #'.$category_id) }}
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        @endforeach
                        @foreach (($filters['utilities'] ?? []) as $utility_id)
                            <button
                                wire:click="clearFilter('utilities', '{{$utility_id}}')"
                                type="button"
                                class="badge badge-outline border-green-500 text-green-600 inline-flex items-center px-3 py-2 gap-1 cursor-pointer hover:bg-green-500 hover:text-white transition-all duration-200">
                                {{ $utilities[$utility_id] ?? ('Tiện ích #'.$utility_id) }}
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        @endforeach
                        @if ($filters['province_code'])
                            <button
                                wire:click="clearFilter('province_code')"
                                type="button"
                                class="badge badge-outline border-green-500 text-green-600 inline-flex items-center px-3 py-2 gap-1 cursor-pointer hover:bg-green-500 hover:text-white transition-all duration-200">
                                {{ $provinces[$filters['province_code']] ?? ('Mã tỉnh #'.$filters['province_code']) }}
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        @endif
                        @if ($filters['district_code'])
                            <button
                                wire:click="clearFilter('district_code')"
                                type="button"
                                class="badge badge-outline border-green-500 text-green-600 inline-flex items-center px-3 py-2 gap-1 cursor-pointer hover:bg-green-500 hover:text-white transition-all duration-200">
                                {{ $districts[$filters['district_code']] ?? ('Mã tỉnh #'.$filters['district_code']) }}
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        @endif
                        @if ($filters['ward_code'])
                            <button
                                wire:click="clearFilter('ward_code')"
                                type="button"
                                class="badge badge-outline border-green-500 text-green-600 inline-flex items-center px-3 py-2 gap-1 cursor-pointer hover:bg-green-500 hover:text-white transition-all duration-200">
                                {{ $wards[$filters['ward_code']] ?? ('Mã tỉnh #'.$filters['ward_code']) }}
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        @endif
                    </div>
                </div>
                <div class="md:flex hidden items-center gap-3 min-w-0">
                    <div class="whitespace-nowrap">Sắp xếp theo:</div>
                    <select wire:model.live="sortBy" class="select select-bordered w-36 shrink-0">
                        <option value="">Đúng nhất</option>
                        <option value="rating">Điểm đánh giá</option>
                        <option value="distance">Gần tôi nhất</option>
                    </select>
                </div>
            </div>
            @foreach ($stores ?? [] as $store)
                <div wire:key="store-{{ $store->id . time()}}">
                    <livewire:search-store.card-store :store="$store" :key="$store->id . time()" :lat_location="$lat"
                                                      :lng_location="$lng"/>
                </div>
            @endforeach
            <div class="mt-5">
                {{ $stores->onEachSide(1)->links() }}
            </div>
        </div>
    </div>

    <div class="fixed bottom-2 left-0 right-0 flex justify-center gap-3 z-40 lg:hidden">
        <button @click="mobileFilterOpen = true"
                class="btn btn-outline bg-white shadow-sm border-gray-200 px-4 h-10 rounded-3xl">
            <i class="fa-solid fa-filter"></i>
            Bộ lọc
        </button>
        <button @click="mapModal = true"
                class="btn btn-outline bg-white shadow-sm border-gray-200 px-4 h-10 rounded-3xl">
            <i class="fa-solid fa-map"></i>
            Bản đồ
        </button>
    </div>

    <div x-show="mobileFilterOpen" x-transition class="fixed inset-0 z-50 lg:hidden">
        <div class="absolute inset-0 bg-black/50" @click="mobileFilterOpen = false"></div>
        <div class="absolute inset-x-0 bottom-0 top-0 bg-white rounded-t-2xl flex flex-col">
            <div class="px-4 py-3 bg-[#52ab5c] text-white rounded-t-2xl flex items-center justify-between">
                <div class="text-lg font-semibold">Bộ lọc</div>
                <button @click="mobileFilterOpen = false" class="w-8 h-8 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-6 h-6" fill="currentColor">
                        <path
                            d="M6.225 4.811a1 1 0 0 0-1.414 1.414L10.586 12l-5.775 5.775a1 1 0 1 0 1.414 1.414L12 13.414l5.775 5.775a1 1 0 0 0 1.414-1.414L13.414 12l5.775-5.775a1 1 0 0 0-1.414-1.414L12 10.586 6.225 4.811Z"/>
                    </svg>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-4">
                <x-select title="Giờ mở cửa" :options="['all' => 'Tất cả', 'open' => 'Đang mở cửa']" type="radio"
                          model="filters.opening_now"/>
                <x-select title="Danh mục" :options="$categories" type="checkbox" model="filters.category_ids"
                          search="true"/>
                <div class="mb-4">
                    <x-select title="Tỉnh/Thành phố" :options="$provinces" type="radio" model="filters.province_code"
                              search="true"/>
                    @if (!empty($filters['province_code']))
                        <x-select title="Quận/Huyện" :options="$districts" type="radio" model="filters.district_code"
                                  search="true"/>
                    @endif
                    @if (!empty($filters['district_code']))
                        <x-select title="Phường/Xã" :options="$wards" type="radio" model="filters.ward_code"
                                  search="true"/>
                    @endif
                </div>
                <x-select title="Tiện ích" :options="$utilities" type="checkbox" model="filters.utilities"/>
            </div>
            <div class="p-4 flex items-center gap-3">
                <button @click="mobileFilterOpen = false" class="btn btn-primary-green text-white flex-[5_1_0%]">Áp dụng
                </button>
                <button @click="$wire.setDefaultFilters(), mobileFilterOpen = false"
                        class="btn btn-outline flex-[2_1_0%]">
                    Đặt lại
                </button>
            </div>
        </div>
    </div>

    <template x-teleport="body">
        <div
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave-end="opacity-0"
            x-show="mapModal"
            class="fixed inset-0 z-10 flex items-center justify-center bg-black/50"
        >
            <div
                @click.away="mapModal = false"
                class="bg-white w-full md:w-[90%] h-fit rounded-2xl shadow-xl overflow-y-auto overscroll-contain relative"
            >
                <div class="absolute top-2 right-2">
                    <button @click="mapModal = false" class="btn rounded-full size-8 p-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center justify-center gap-2 border-b py-4 border-b-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                    <h1 class="text-2xl font-bold capitalize">
                        <span>Xem bản đồ</span>
                    </h1>
                </div>
                <div x-data="{
                        _gmapsLoadedSearchStoreLocation: false,
                        _stores: @entangle('stores_map').live,
                        _markers: [],
                        clearAllMarkers() {
                                this._markers.forEach((marker, index) => {
                                    try {
                                        // Method 1: Use custom destroy method if available
                                        if (typeof marker.destroy === 'function') {
                                            marker.destroy();
                                        }
                                        // Method 2: For AdvancedMarkerElement, set map to null
                                        else if (marker.map) {
                                            marker.map = null;
                                        }
                                        // Method 3: Standard marker removal
                                        else if (typeof marker.setMap === 'function') {
                                            marker.setMap(null);
                                        }
                                    } catch (error) {
                                        console.error(`Error removing marker ${index}:`, error);
                                    }
                                });
                                this._markers.splice(0, this._markers.length);
                            },
                        async _ensureMap() {
                            try {
                                // load SDK (chỉ 1 lần)
                                if (!this._gmapsLoadedSearchStoreLocation) {
                                    await this.$mapPlugin.initLoader();
                                    this._gmapsLoadedSearchStoreLocation = true;
                                }
                                await this.$nextTick();
                                // Create map if not exists
                                if (!window.mapSearch) {
                                    window.mapSearch = this.$mapPlugin.createMap(this.$refs.mapContainer, this.$mapPlugin.defaultCenter, 14);
                                }
                                // Clear existing markers
                                if (this._markers.length > 0) {
                                    this.clearAllMarkers();
                                }
                                // Add new markers if stores exist
                                if (Array.isArray(this._stores) && this._stores.length > 0) {
                                    const first = this._stores[0];
                                    window.mapSearch.setCenter({lat: parseFloat(first.lat), lng: parseFloat(first.lng)});
                                    this._stores.forEach((store, index) => {
                                        const marker = this.$mapPlugin.plugin.createMarkerStore(window.mapSearch, {
                                            lat: parseFloat(store.lat),
                                            lng: parseFloat(store.lng)
                                        }, store, false);
                                        this._markers.push(marker);
                                    });
                                } else {
                                    window.mapSearch.setCenter(this.$mapPlugin.defaultCenter);
                                }
                            } catch (error) {
                                console.error('Error in _ensureMap:', error);
                            }
                        }
                    }"
                     x-init="
                      $nextTick(() => _ensureMap());
                      $watch('_stores', (newStores, oldStores) => {
                            if (JSON.stringify(newStores) !== JSON.stringify(oldStores)) {
                                $nextTick(() => _ensureMap());
                            }
                      });
                     "
                >
                    <div wire:ignore>
                        <div class="w-full h-[520px] border border-gray-300 rounded-md" x-ref="mapContainer"></div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
