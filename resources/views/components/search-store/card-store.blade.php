@php use App\Utils\HelperFunction; @endphp

<div
    x-data="{
        lat_location: @entangle('lat_location').live,
        lng_location: @entangle('lng_location').live,
        loading: true,
        distance: null,
        distTo(){
            if(this.lat_location && this.lng_location){
                const d = this.$GeoPlugin.util.haversineDistance(this.lat_location, this.lng_location,{{$store->latitude}},{{$store->longitude}});
                this.loading = false;
                this.distance = d < 1 ? Math.round(d*1000)+' m' : d.toFixed(1)+' km';
            }
        }
    }"
    x-init="distTo()"
    x-effect="distTo()"
    class="card card-side bg-white rounded-xl border border-gray-200 shadow-sm relative text-black mb-2 sm:mb-4 p-2 flex sm:items-start gap-3 items-center">
    <figure class="w-[112px] h-[112px] md:w-[270px] md:h-[184px] overflow-hidden rounded-lg flex-shrink-0">
        <img src="{{ HelperFunction::generateURLImagePath($store->logo_path) }}" alt="{{ $store->slug }}"
             srcset="{{ HelperFunction::generateURLImagePath($store->logo_path) }} 320w, {{ HelperFunction::generateURLImagePath($store->logo_path) }} 640w, {{ HelperFunction::generateURLImagePath($store->logo_path) }} 960w"
             sizes="(max-width: 768px) 50vw, 25vw"
             loading="lazy" decoding="async"
             class="object-cover w-full h-full transition-transform duration-300 ease-in-out hover:scale-105"/>
    </figure>

    <livewire:store.save-location :store="$store" absolute/>

    <div class="flex-1 min-w-0 md:flex md:flex-col md:gap-1.5">
        <h2 class="text-base md:text-xl font-semibold leading-snug truncate">
            <a href="{{ route('frontend.store',['slug' => $store->slug]) }}" class="hover:underline text-green-700 capitalize">
                {{ $store->name }}
            </a>
        </h2>
        <div class="flex items-center text-base text-gray-700">
            <div class="rating w-20 h-5 mr-1.5">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="mask mask-star-2 bg-green-500" aria-label="{{ $i }} star"
                         @if ($i <= round($store->overall_rating ?? 0)) aria-current="true" @endif></div>
                @endfor
            </div>
            <span class="truncate">
                @if ((int) $store->reviews_count > 0)
                    - {{ $store->reviews_count }} đánh giá
                @else
                    - Chưa có đánh giá
                @endif
            </span>
        </div>
        <div class="flex items-center gap-1.5 text-base text-gray-700 min-w-0">
            <i class="fa-solid fa-location-dot text-green-500"></i>
            <span class="truncate">{{ $store->address }}</span>
        </div>
        <div x-show="loading">
            <div class="skeleton h-4 w-1/2"></div>
        </div>
        <div x-show="!loading">
            <div class="flex items-center gap-1.5 text-base text-gray-700">
                <i class="fa-solid fa-map text-gray-500"></i>
                <span><span x-text="distance"></span> từ vị trí của bạn</span>
            </div>
        </div>
        @php($statusOpen = \App\Utils\HelperFunction::checkIsStoreOpen(openingTime: $store->opening_time,closingTime: $store->closing_time))
        <div class="flex items-center gap-1.5 text-base">
            <div class="sm:flex">
                <span
                    class="{{ $statusOpen ? 'text-[#00b707]' : 'text-red-500' }} font-semibold">
                    {{ $statusOpen ? 'Mở cửa' : 'Đóng cửa' }}
                </span>
                <span>- {{ $store->opening_time }} - {{ $store->closing_time }}</span>
            </div>
        </div>
    </div>
</div>
