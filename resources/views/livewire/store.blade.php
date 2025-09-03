<div class="w-full">
    @section('vite_includes')
        @vite(['resources/js/store.js'])
    @endsection

    <div class="section_inside">
        <div class="card card-xs bg-white w-full shadow-sm">
            <div class="card-body">
                {{-- Tên cửa hàng --}}
                <div class="flex items-center justify-between gap-4">
                    <h1 class="card-title capitalize font-medium text-2xl">{{$store->name}}</h1>
                    {{-- btn lưu địa điểm --}}
                    <button class="btn bg-transparent border-none">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z"/>
                        </svg>
                    </button>
                </div>
                {{-- Địa điểm --}}
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                    <p class="inline-flex items-center gap-2">
                        <span class="capitalize">{{$store->address}}</span>
                        <button class="btn btn-xs btn-primary-green">
                            Xem địa điểm
                        </button>
                        <button class="btn btn-xs btn-primary-blue">
                            Xem đường đi
                        </button>
                    </p>
                </div>

                {{-- media --}}
                <div class="grid grid-cols-6 grid-rows-2 h-96 gap-2">
                    <livewire:store.media-heading
                        :render="'full-span'"
                        :slug="$store->slug"
                        :path="$store->logo_path"
                        :file_type="'image/png'"
                    />
                    @if($store->storeFiles->count() > 0)
                        @php
                            $firstFile = $store->storeFiles->first();
                        @endphp
                        <livewire:store.media-heading
                            :render="'full-span'"
                            :slug="$store->slug"
                            :path="$firstFile->file_path"
                            :file_type="$firstFile->file_type"
                        />
                        @foreach($store->storeFiles as $file)
                            @if(!$loop->first)
                                @if($loop->index == 1)
                                    <livewire:store.media-heading
                                        :render="'half-row'"
                                        :slug="$store->slug"
                                        :path="$file->file_path"
                                        :file_type="$file->file_type"
                                    />
                                @elseif($loop->last && (($store->store_files_count - $store->storeFiles->count()) >= 1))
                                    <livewire:store.media-heading
                                        :slug="$store->slug"
                                        :path="$file->file_path"
                                        :file_type="$file->file_type"
                                        :total_files="$store->store_files_count - $store->storeFiles->count()"
                                        last
                                    />
                                @else
                                    <livewire:store.media-heading
                                        :slug="$store->slug"
                                        :path="$file->file_path"
                                        :file_type="$file->file_type"
                                    />
                                @endif
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
