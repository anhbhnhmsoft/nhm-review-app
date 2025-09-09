<div x-data="{
    modal: false,
    _map:null,
    _gmapsLoadedDetailLocation: false,
    _position: {
       lat: {{$store->latitude}},
       lng: {{$store->longitude}}
    },
    async _ensureMap() {
        // load SDK (chỉ 1 lần)
        if (!this._gmapsLoadedDetailLocation) {
          await this.$mapPlugin.initLoader();
          this._gmapsLoadedDetailLocation = true;
        }
        await this.$nextTick();
        if (!this._map){
            let map = this.$mapPlugin.createMap(this.$refs.mapContainer, this._position, 16);
            this._map = map;
            this.$mapPlugin.plugin.createMarkerStore(map, this._position, {
                name: @js($store->name),
                address: '{{$store->address}}',
                rate: '{{$avgRatingTotal}}',
                reviews_count: '{{$store->reviews_count}}',
                path: '{{\App\Utils\HelperFunction::generateURLImagePath($store->logo_path)}}'
            },true)
        }else{
            this._map.setCenter(this._position)
        }
    }
}"
     x-init="$watch('modal', v => { if (v) _ensureMap() })"
>
    <div class="w-full h-52 relative cursor-pointer" @click="modal = true">
        <img src="{{asset('images/img_map.png')}}" class="w-full h-full object-cover rounded-lg"
             alt="{{\Illuminate\Support\Str::slug($store->address)}}">
        <div class="absolute left-0 right-0 bottom-4 flex items-center gap-2 bg-white shadow-lg rounded-lg p-4 mx-6">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                 stroke="currentColor" class="size-12">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
            </svg>
            <a target="_blank" class="hover:link text-base font-medium capitalize"
               href="https://www.google.com/maps/dir/?api=1&destination={{$store->latitude}},{{$store->longitude}}">
                {{$store->address}}
            </a>
        </div>
    </div>

    <div>
        <template x-teleport="body">
            <div
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave-end="opacity-0"
                x-show="modal"
                class="fixed inset-0 z-10 flex items-center justify-center bg-black/50"
            >
                <div
                    @click.away="modal = false"
                    class="bg-white w-[90%] h-fit rounded-2xl shadow-xl overflow-y-auto overscroll-contain relative"
                >
                    <div class="absolute top-2 right-2">
                        <button @click="modal = false" class="btn rounded-full size-8 p-0">
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
                            <span>{{$store->name}}</span>
                        </h1>
                    </div>
                    <div wire:ignore>
                        <div class="relative">
                            <div class="w-full h-[520px] border border-gray-300 rounded-md" x-ref="mapContainer"></div>
                            <div class="absolute right-0 left-0 bottom-4 flex items-center justify-center ">
                                <a target="_blank" class="py-2 px-4 flex items-center space-x-3 bg-white border border-gray-400 rounded-2xl shadow-lg"
                                   href="https://www.google.com/maps/dir/?api=1&destination={{$store->latitude}},{{$store->longitude}}">
                                    <span class="font-bold">Xem đường đi</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </template>
    </div>
</div>
