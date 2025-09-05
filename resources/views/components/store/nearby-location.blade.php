<div x-data="{
                _map: null,
                _gmapsLoadedNearbyLocation: false,
                _position_center: {lat: {{$latitude}}, lng: {{$longitude}}},
                _center_store_id: '{{$store_id}}',
                _stores: @js($stores->map(fn($s) => [
                    'id' => $s->id,
                    'name' => $s->name,
                    'address' => $s->address,
                    'rate' => $s->reviews_avg ? round($s->reviews_avg, 2) : 0,
                    'reviews_count' => $s->reviews_count,
                    'path' => \App\Utils\HelperFunction::generateURLImagePath($s->logo_path),
                    'lat' => (float) $s->latitude,
                    'lng' => (float) $s->longitude,
                ])),
                async _ensureMap() {
                    // load SDK (chỉ 1 lần)
                    if (!this._gmapsLoadedNearbyLocation) {
                        await this.$mapPlugin.initLoader();
                        this._gmapsLoadedNearbyLocation = true;
                    }
                    await this.$nextTick();
                        let map = this.$mapPlugin.createMap(this.$refs.mapContainer, this._position_center, 14);
                        this._map = map;
                         if (Array.isArray(this._stores)) {
                             this._stores.forEach(store => {
                                let open = false;
                                if(this._center_store_id == store.id){
                                    open = true;
                                }
                                this.$mapPlugin.plugin.createMarkerStore(map, {
                                     lat: store.lat,
                                     lng: store.lng
                                }, store, open)
                         });
                    }
                }
            }"
     x-init="$nextTick(() => _ensureMap())"
>

    <div wire:ignore class="mb-4">
        <div class="w-full h-[520px] border border-gray-200 rounded-md shadow-md" x-ref="mapContainer"></div>
    </div>
    <div>
        @if($stores->count() > 0)
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2">
                @foreach($stores as $store)
                    @if($store->id != $store_id)
                        <a href="{{route('frontend.store',['slug'=>$store->slug])}}"
                           class="block bg-white border border-gray-200 rounded-md shadow-lg p-2 transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1 "
                           target="_blank">
                            <div class="w-full h-52">
                                <img src="{{\App\Utils\HelperFunction::generateURLImagePath($store->logo_path)}}"
                                     class="object-cover w-full h-full rounded-md" alt="{{$store->slug}}"/>
                            </div>
                            <div class="flex flex-col gap-1">
                                <h1 class="text-lg font-bold truncate max-h-8 capitalize">{{$store->name}}</h1>
                                <p class="text-sm text-gray-500 truncate max-h-8 capitalize">{{$store->address}}</p>
                            </div>
                        </a>
                    @endif
                @endforeach
            </div>
        @else
            <div role="alert" class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                     stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.198 1.318M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z"/>
                </svg>
                <span>Hiện tại không có địa điểm nào xung quanh khu vực này</span>
            </div>
        @endif
    </div>
</div>
