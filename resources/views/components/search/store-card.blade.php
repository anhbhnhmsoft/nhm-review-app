@props(['store'])

@php
    use App\Utils\HelperFunction;
    use Carbon\Carbon;
@endphp

<div class="card group hover:shadow-lg bg-white shadow-md transition-all duration-200">
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
        <div class="sm:col-span-1">
            <a href="" class="p-2 block group overflow-hidden ">
                @if ($store->logo_path)
                    <img src="{{ HelperFunction::generateURLImagePath($store->logo_path) }}" alt="{{ $store->name }}"
                        class="w-full h-40 sm:h-full rounded-lg object-cover transform transition-transform duration-300 ease-in-out group-hover:scale-105">
                @else
                    <div class="w-full h-40 sm:h-full bg-gray-100 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                        </svg>
                    </div>
                @endif
            </a>
        </div>

        <div class=" sm:col-span-2 flex flex-col justify-between">
            <div>
                <h3 class="text-2xl font-semibold text-gray-800">
                    <a href="" class="hover:underline">
                        {{ $store->name }}
                    </a>
                </h3>
                <div class="flex items-start justify-start gap-3">
                    <div>
                        @if ($store->category)
                            <p class="text-sm text-base-content/60 mt-1">{{ $store->category->name }}</p>
                        @endif
                    </div>

                    <div class="text-sm text-right flex items-center gap-2">
                        @if (count($store->reviews) > 0)
                            @php

                                $stars = 0;

                                foreach ($store->reviews as $rv) {
                                    $starts += $rv->rating;
                                }
                                $stars = floor($starts / count($store->reviews));
                            @endphp
                            <div class="rating">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i == $stars)
                                        <div class="mask mask-star" aria-label="{{ $i }} star"
                                            aria-current="true"></div>
                                    @else
                                        <div class="mask mask-star" aria-label="{{ $i }} star"></div>
                                    @endif
                                @endfor
                            </div>
                        @else
                            <div class="rating">
                                <div class="mask mask-star" aria-label="1 star"></div>
                                <div class="mask mask-star" aria-label="2 star"></div>
                                <div class="mask mask-star" aria-label="3 star"></div>
                                <div class="mask mask-star" aria-label="4 star"></div>
                                <div class="mask mask-star" aria-label="5 star"></div>

                            </div>
                            <p> - Chưa có đánh giá nào</p>
                        @endif
                    </div>
                </div>

                @if ($store->address)
                    <p class="mt-3 text-sm text-base-content/70 line-clamp-2">{{ $store->address }}</p>
                @endif

                @if (isset($store->opening_time) && isset($store->closing_time))
                    <div class="mt-3 text-sm text-base-content/65 flex items-center gap-2">

                        <x-heroicon-o-clock class="max-h-5" />

                        @php
                            $now = Carbon::now();
                            $open = Carbon::createFromFormat('H:i', $store->opening_time);
                            $close = Carbon::createFromFormat('H:i', $store->closing_time);
                        @endphp

                        @if ($now->between($open, $close))
                            <span class="text-emerald-500">Đang mở cửa</span>
                        @else
                            <span class="text-red-400">Đóng cửa</span>
                        @endif

                        @if ($store->opening_time === '00:01' && $store->closing_time === '23:59')
                            <span>Mở cả ngày</span>
                        @else
                            <span>{{ $store->opening_time }} - {{ $store->closing_time }}</span>
                        @endif

                    </div>
                @endif

                @php

                @endphp
                <div class="mt-3 text-sm text-base-content/65 flex items-center gap-2">
                    Khoảng cách tới địa điểm: {{ $distance }}
                </div>
            </div>
        </div>
    </div>
</div>
