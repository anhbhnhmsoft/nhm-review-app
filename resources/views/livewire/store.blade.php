<div class="w-full">
    @section('vite_includes')
        @vite(['resources/js/store.js','resources/css/map.css'])
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
                <div class="flex items-center gap-2 flex-wrap mb-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                    </svg>
                    <span class="capitalize">{{$store->address}}</span>
                    <a class="btn btn-xs btn-primary-blue" target="_blank"
                       href="https://www.google.com/maps?q={{ $store->latitude }},{{ $store->longitude }}">
                        Xem đường đi
                    </a>
                </div>

                {{-- media --}}
                <div class="grid grid-cols-1 lg:grid-cols-6 lg:grid-rows-2 lg:h-96 gap-2">
                    <div class="col-span-2 row-span-2 relative">
                        <livewire:store.media-heading
                                :render="'full-span'"
                                :slug="$store->slug"
                                :path="$store->logo_path"
                                :file_type="'image/png'"
                                first
                        />
                        <div class="absolute bottom-2 right-0 left-0 p-2 flex lg:hidden items-center justify-between">
                            <a class="flex items-center justify-center px-2 py-1 rounded-lg border border-gray-200 bg-black/70 shadow-lg] text-sm font-medium text-white">
                                Xem tất cả
                            </a>
                            <div class="flex items-center justify-center px-2 py-1 rounded-lg border border-gray-200 bg-black/70 shadow-lg] text-sm font-medium text-white">
                                {{$store->store_files_count}} Phương tiện
                            </div>
                        </div>
                    </div>
                    @if($store->storeFiles->count() > 0)
                            @php
                                $firstFile = $store->storeFiles->first();
                            @endphp
                            <div class="hidden lg:block col-span-2 row-span-2">
                                <livewire:store.media-heading
                                        :render="'full-span'"
                                        :slug="$store->slug"
                                        :path="$firstFile->file_path"
                                        :file_type="$firstFile->file_type"
                                        lazy
                                />
                            </div>
                            @foreach($store->storeFiles as $file)
                                @if(!$loop->first)
                                    @if($loop->index == 1)
                                    <div class="hidden lg:block col-span-2">
                                        <livewire:store.media-heading
                                                :render="'half-row'"
                                                :slug="$store->slug"
                                                :path="$file->file_path"
                                                :file_type="$file->file_type"
                                                lazy
                                        />
                                    </div>
                                    @elseif($loop->last && (($store->store_files_count - $store->storeFiles->count()) >= 1))
                                    <div class="hidden lg:block">
                                        <livewire:store.media-heading
                                                :slug="$store->slug"
                                                :path="$file->file_path"
                                                :file_type="$file->file_type"
                                                :total_files="$store->store_files_count - $store->storeFiles->count()"
                                                last
                                                lazy
                                        />
                                    </div>
                                    @else
                                    <div class="hidden lg:block">
                                        <livewire:store.media-heading
                                                :slug="$store->slug"
                                                :path="$file->file_path"
                                                :file_type="$file->file_type"
                                                lazy
                                        />
                                    </div>
                                    @endif
                                @endif
                            @endforeach
                        @endif
                </div>
            </div>
        </div>
    </div>

    <div class="section_inside">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
            <div class="card card-xs bg-white w-full shadow-sm">
                <div class="card-body">
                    <h1 class="card-title capitalize font-bold text-lg mt-2">Đánh giá</h1>
                    <div class="flex items-center justify-center">
                        <b class="block text-2xl text-center min-w-12 px-1 py-2 text-white bg-green-600 rounded-lg me-1">{{$avgRatingTotal}}</b>
                        <div>
                            <h2 class="text-lg font-medium">
                                @if($avgRatingTotal > 0)
                                    Đánh giá
                                @else
                                    Chưa có đánh giá nào
                                @endif
                            </h2>
                            <span class="text-sm"> / 5 ( {{$store->reviews_count}} Đánh giá )</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 mt-4">
                        <div class="flex items-center w-full">
                            <span class="basis-1/3 text-base">Vị trí</span>
                            <progress class="progress progress-success basis-2/3"
                                      value="{{$avgRating->avg_location ?? 0}}" max="5"></progress>
                        </div>
                        <div class="flex items-center w-full">
                            <span class="basis-1/3 text-base">Không gian</span>
                            <progress class="progress progress-success basis-2/3" value="{{$avgRating->avg_space ?? 0}}"
                                      max="5"></progress>
                        </div>
                        <div class="flex items-center w-full">
                            <span class="basis-1/3 text-base">Chất lượng</span>
                            <progress class="progress progress-success basis-2/3"
                                      value="{{$avgRating->avg_quality ?? 0}}" max="5"></progress>
                        </div>
                        <div class="flex items-center w-full">
                            <span class="basis-1/3 text-base">Phục vụ</span>
                            <progress class="progress progress-success basis-2/3" value="{{$avgRating->avg_serve ?? 0}}"
                                      max="5"></progress>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-xs bg-white w-full shadow-sm">
                <div class="card-body">
                    <h1 class="card-title capitalize font-bold text-lg mt-2">Thông tin chi tiết</h1>
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center justify-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                            </svg>
                            <span
                                class="text-base">{{\App\Utils\Constants\StoreStatus::getLabel($store->status)}}</span>
                        </div>
                        <div class="flex items-center justify-start gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            @if($openStore)
                                <span class="text-base font-medium text-green-600">Đang mở cửa</span>
                            @else
                                <span class="text-base font-medium text-red-600">Đóng cửa</span>
                            @endif
                            <span class="text-base">{{$store->opening_time}} - {{$store->closing_time}}</span>
                        </div>
                        @if($store->phone)
                            <div class="flex items-center justify-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z"/>
                                </svg>
                                <span class="text-base">{{$store->phone}}</span>
                            </div>
                        @endif
                        @if($store->email)
                            <div class="flex items-center justify-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                                </svg>
                                <span class="text-base">{{$store->email}}</span>
                            </div>
                        @endif
                        @if($store->website)
                            <div class="flex items-center justify-start gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418"/>
                                </svg>
                                <a target="_blank" href="{{$store->website}}"
                                   class="text-base hover:text-blue-400">{{$store->website}}</a>
                            </div>
                        @endif
                        @if($store->facebook_page || $store->instagram_page || $store->tiktok_page || $store->youtube_page)
                            <div class="collapse p-0 m-0">
                                <input type="checkbox" class="p-0 m-0"/>
                                <div class="collapse-title font-semibold text-center text-base">Xem thêm</div>
                                <div class="collapse-content flex flex-col gap-2">
                                    @if($store->facebook_page)
                                        <div class="flex items-center justify-start gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                                 class="size-4">
                                                <path
                                                    d="M400 32H48A48 48 0 0 0 0 80v352a48 48 0 0 0 48 48h137.25V327.69h-63V256h63v-54.64c0-62.15 37-96.48 93.67-96.48 27.14 0 55.52 4.84 55.52 4.84v61h-31.27c-30.81 0-40.42 19.12-40.42 38.73V256h68.78l-11 71.69h-57.78V480H400a48 48 0 0 0 48-48V80a48 48 0 0 0-48-48z"/>
                                            </svg>
                                            <a target="_blank" href="{{$store->facebook_page}}"
                                               class="text-base hover:text-blue-400">{{$store->facebook_page}}</a>
                                        </div>
                                    @endif
                                    @if($store->instagram_page)
                                        <div class="flex items-center justify-start gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                                 class="size-4">
                                                <path
                                                    d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/>
                                            </svg>
                                            <a target="_blank" href="{{$store->instagram_page}}"
                                               class="text-base hover:text-blue-400">{{$store->instagram_page}}</a>
                                        </div>
                                    @endif
                                    @if($store->youtube_page)
                                        <div class="flex items-center justify-start gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-4"
                                                 viewBox="0 0 576 512">
                                                <path
                                                    d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/>
                                            </svg>
                                            <a target="_blank" href="{{$store->youtube_page}}"
                                               class="text-base hover:text-blue-400">{{$store->youtube_page}}</a>
                                        </div>
                                    @endif
                                    @if($store->youtube_page)
                                        <div class="flex items-center justify-start gap-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                                                 class="size-4">
                                                <path
                                                    d="M448,209.91a210.06,210.06,0,0,1-122.77-39.25V349.38A162.55,162.55,0,1,1,185,188.31V278.2a74.62,74.62,0,1,0,52.23,71.18V0l88,0a121.18,121.18,0,0,0,1.86,22.17h0A122.18,122.18,0,0,0,381,102.39a121.43,121.43,0,0,0,67,20.14Z"/>
                                            </svg>
                                            <a target="_blank" href="{{$store->youtube_page}}"
                                               class="text-base hover:text-blue-400">{{$store->youtube_page}}</a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                        @endif

                    </div>
                </div>
            </div>
            <div class="card card-xs bg-white w-full shadow-sm">
                <div class="card-body">
                    <h1 class="card-title capitalize font-bold text-lg mt-2">Địa điểm cụ thể</h1>
                    <livewire:store.detail-location :store_id="$store->id"/>
                </div>
            </div>
        </div>
    </div>

    <div class="section_inside">
        <div class="card card-xs bg-white w-full shadow-sm">
            <div class="card-body">
                @if($store->utilities->count() > 0)
                    <div class="swiper-container !w-full overflow-x-hidden" id="store_utilities">
                        <div class="swiper-wrapper w-full">
                            @foreach($store->utilities as $utility)
                                <div class="swiper-slide">
                                    <div class="flex items-center justify-center flex-col store_utilities_item">
                                        @if($utility->icon_svg)
                                            {!!$utility->icon_svg!!}
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                                            </svg>
                                        @endif
                                        <p class="text-base font-medium text-center">{{$utility->name}}</p>
                                        @if($utility->description)
                                            <p class="text-xs text-gray-400 text-center hidden lg:inline">
                                                {{$utility->description}}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="flex items-center justify-center gap-2 flex-col">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-6 text-green-600">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15.182 16.318A4.486 4.486 0 0 0 12.016 15a4.486 4.486 0 0 0-3.198 1.318M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Zm5.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75Zm-.375 0h.008v.015h-.008V9.75Z"/>
                        </svg>
                        <h1 class="text-lg font-medium">Hiện địa điểm chưa có tiện ích nào</h1>
                        <p class="text-base">Xin lỗi vì sự bất tiện</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="section_inside">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-2">
            <div class="order-2 lg:order-1 lg:col-span-2">
                <div class="card card-xs bg-white w-full shadow-sm">
                    <div class="card-body">
                        <div class="flex items-center justify-between gap-4 mb-2">
                            <h1 class="card-title capitalize font-bold text-lg mt-2">Đánh giá <span
                                    class="text-gray-400">({{$store->reviews_count}})</span></h1>
                            <button class="btn btn-primary-green btn-sm rounded-xl"
                                    wire:click="$dispatchTo('store.form-review','open-modal', { store_id: '{{ $store->id }}' })"
                            >
                                Viết đánh giá
                            </button>
                        </div>
                        <div
                            class="flex items-center gap-8 h-44 p-4 relative rounded-2xl bg-gradient-to-r from-[#52ab5c] to-[#c8e4cc] ">
                            <div class="w-1/3 h-full flex items-center justify-center relative">
                                <img src="{{asset('images/review-svg.svg')}}" alt="{{$store->slug}}" class="max-h-full">
                            </div>
                            <div class="ms-3 flex items-start justify-center flex-col gap-2">
                                <h1 class="text-2xl font-medium">Bạn đã từng đến đây?</h1>
                                <p class="justify-self-start text-base">Chia sẻ trải nghiệm và cảm nhận của bản thân cho
                                    mọi người cùng biết &#10084;, Những review chất lượng sẽ được xuất hiện ở bảng tin
                                    đấy!</p>
                            </div>
                            <div
                                class="absolute top-[-10px] right-[36px] w-0 h-0 border-l-[10px] border-r-[10px] border-b-[10px] border-transparent border-b-[#c8e4cc]"></div>
                        </div>
                        <div class="py-5 mt-5 border-t border-t-gray-400">
                            <livewire:store.review-section :store_id="$store->id"/>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-1 lg:order-2 lg:col-span-1">
                <div class="card bg-white w-full shadow-sm">
                    <div class="card-body">
                        <h1 class="card-title capitalize font-bold text-lg mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="size-4">
                                <!--!Font Awesome Free v5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                <path
                                    d="M623.2 192c-51.8 3.5-125.7 54.7-163.1 71.5-29.1 13.1-54.2 24.4-76.1 24.4-22.6 0-26-16.2-21.3-51.9 1.1-8 11.7-79.2-42.7-76.1-25.1 1.5-64.3 24.8-169.5 126L192 182.2c30.4-75.9-53.2-151.5-129.7-102.8L7.4 116.3C0 121-2.2 130.9 2.5 138.4l17.2 27c4.7 7.5 14.6 9.7 22.1 4.9l58-38.9c18.4-11.7 40.7 7.2 32.7 27.1L34.3 404.1C27.5 421 37 448 64 448c8.3 0 16.5-3.2 22.6-9.4 42.2-42.2 154.7-150.7 211.2-195.8-2.2 28.5-2.1 58.9 20.6 83.8 15.3 16.8 37.3 25.3 65.5 25.3 35.6 0 68-14.6 102.3-30 33-14.8 99-62.6 138.4-65.8 8.5-.7 15.2-7.3 15.2-15.8v-32.1c.2-9.1-7.5-16.8-16.6-16.2z"/>
                            </svg>
                            Giới thiệu
                        </h1>
                        <div x-data="{ isOpen: false }" class="mt-2">
                            <div x-show="!isOpen">
                                {!! Str::limit($store->description, 600) !!}
                            </div>
                            <div x-show="isOpen" x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave-end="opacity-0">
                                {!! $store->description !!}
                            </div>
                            <button @click="isOpen = !isOpen" class="text-blue-500 cursor-pointer">
                                <span x-show="!isOpen" x-transition>
                                    Xem thêm
                                </span>
                                <span x-show="isOpen" x-transition>
                                    Thu gọn
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="section_inside">
        <div class="card card-xs bg-white w-full shadow-sm">
            <div class="card-body">
                <h1 class="card-title capitalize font-bold text-lg mt-2">Địa điểm lân cận <span class="text-gray-400">(Bán kính 5 km)</span> </h1>
                <div class="mt-4">
                    <livewire:store.nearby-location :store_id="$store->id" :latitude="$store->latitude" :longitude="$store->longitude" />
                </div>
            </div>
        </div>
    </div>

    <livewire:store.form-review/>
</div>
