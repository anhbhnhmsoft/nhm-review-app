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

    <div class="absolute top-2 right-2 z-10 shadow-sm hover:shadow-lg hover:text-green-600 bg-white rounded-[20px] w-8 h-8 flex items-center justify-center">
        <i class="fa-regular fa-bookmark "></i>
    </div>
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
            <svg class="w-4 h-4" fill="#000000" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M16.114-0.011c-6.559 0-12.114 5.587-12.114 12.204 0 6.93 6.439 14.017 10.77 18.998 0.017 0.020 0.717 0.797 1.579 0.797h0.076c0.863 0 1.558-0.777 1.575-0.797 4.064-4.672 10-12.377 10-18.998 0-6.618-4.333-12.204-11.886-12.204zM16.515 29.849c-0.035 0.035-0.086 0.074-0.131 0.107-0.046-0.032-0.096-0.072-0.133-0.107l-0.523-0.602c-4.106-4.71-9.729-11.161-9.729-17.055 0-5.532 4.632-10.205 10.114-10.205 6.829 0 9.886 5.125 9.886 10.205 0 4.474-3.192 10.416-9.485 17.657zM16.035 6.044c-3.313 0-6 2.686-6 6s2.687 6 6 6 6-2.687 6-6-2.686-6-6-6zM16.035 16.044c-2.206 0-4.046-1.838-4.046-4.044s1.794-4 4-4c2.207 0 4 1.794 4 4 0.001 2.206-1.747 4.044-3.954 4.044z">
                </path>
            </svg>
            <span class="truncate">{{ $store->address }}</span>
        </div>
        <div x-show="loading">
            <div class="skeleton h-4 w-1/2"></div>
        </div>
        <div x-show="!loading">
            <div class="flex items-center gap-1.5 text-base text-gray-700">
                <i class="fa-solid fa-map"></i>
                <span><span x-text="distance"></span> từ vị trí của bạn</span>
            </div>
        </div>
        @php($statusOpen = \App\Utils\HelperFunction::checkIsStoreOpen(openingTime: $store->opening_time,closingTime: $store->closing_time))
        <div class="flex items-center gap-1.5 text-base">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                <path
                    d="M528 320C528 434.9 434.9 528 320 528C205.1 528 112 434.9 112 320C112 205.1 205.1 112 320 112C434.9 112 528 205.1 528 320zM64 320C64 461.4 178.6 576 320 576C461.4 576 576 461.4 576 320C576 178.6 461.4 64 320 64C178.6 64 64 178.6 64 320zM296 184L296 320C296 328 300 335.5 306.7 340L402.7 404C413.7 411.4 428.6 408.4 436 397.3C443.4 386.2 440.4 371.4 429.3 364L344 307.2L344 184C344 170.7 333.3 160 320 160C306.7 160 296 170.7 296 184z"/>
            </svg>
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
