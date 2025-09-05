@props([
    'image',
    'title',
    'description',
    'href',
    'rating' => 0,
    'reviews_count' => 0,
    'address' => '',
    'status' => '',
    'status_label' => '',
    'opening_time' => '',
    'closing_time' => '',
    'latitude' => null,
    'longitude' => null,
    'distance' => null,
])

<div class="card card-side bg-white rounded-xl border border-gray-200 shadow-sm relative text-black mb-2 sm:mb-4 p-2 flex sm:items-start gap-3 items-center">
    <figure class="w-[112px] h-[112px] md:w-[270px] md:h-[184px] overflow-hidden rounded-lg flex-shrink-0">
        <img src="{{ $image }}" alt="{{ $title }}"
            srcset="{{ $image }} 320w, {{ $image }} 640w, {{ $image }} 960w" 
            sizes="(max-width: 768px) 50vw, 25vw"
            loading="lazy" decoding="async"
            class="object-cover w-full h-full transition-transform duration-300 ease-in-out hover:scale-105" />
    </figure>

    <div
        class="absolute top-2 right-2 z-10 shadow-[0_2px_6px_rgba(0,0,0,0.15)] bg-white rounded-[20px] w-8 h-8 flex items-center justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-4 text-gray-600 hover:text-green-600 transition-colors cursor-pointer">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" />
        </svg>
    </div>
    <div class="flex-1 min-w-0 pt-1">
        <h2 class="text-base md:text-xl font-semibold leading-snug truncate">
            <a href="{{ $href }}" class="hover:underline text-green-700">{{ $title }}</a>
        </h2>
        <div class="mt-1 flex items-center text-sm text-gray-700">
            <div class="rating w-20 h-5 mr-1.5">
                @for ($i = 1; $i <= 5; $i++)
                    <div class="mask mask-star-2 bg-green-500" aria-label="{{ $i }} star"
                        @if ($i <= round($rating ?? 0)) aria-current="true" @endif></div>
                @endfor
            </div>
            <span class="truncate">
                @if ((int) $reviews_count > 0)
                    - {{ $reviews_count }} đánh giá
                @else
                    - Chưa có đánh giá
                @endif
            </span>
        </div>
        <div class="mt-1 flex items-center gap-1.5 text-sm text-gray-700 min-w-0">
            <svg class="w-4 h-4" fill="#000000" viewBox="0 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M16.114-0.011c-6.559 0-12.114 5.587-12.114 12.204 0 6.93 6.439 14.017 10.77 18.998 0.017 0.020 0.717 0.797 1.579 0.797h0.076c0.863 0 1.558-0.777 1.575-0.797 4.064-4.672 10-12.377 10-18.998 0-6.618-4.333-12.204-11.886-12.204zM16.515 29.849c-0.035 0.035-0.086 0.074-0.131 0.107-0.046-0.032-0.096-0.072-0.133-0.107l-0.523-0.602c-4.106-4.71-9.729-11.161-9.729-17.055 0-5.532 4.632-10.205 10.114-10.205 6.829 0 9.886 5.125 9.886 10.205 0 4.474-3.192 10.416-9.485 17.657zM16.035 6.044c-3.313 0-6 2.686-6 6s2.687 6 6 6 6-2.687 6-6-2.686-6-6-6zM16.035 16.044c-2.206 0-4.046-1.838-4.046-4.044s1.794-4 4-4c2.207 0 4 1.794 4 4 0.001 2.206-1.747 4.044-3.954 4.044z">
                </path>
            </svg>
            <span class="truncate">{{ $address }}</span>
        </div>
        @if (!is_null($distance))
        <div class="mt-1 flex items-center gap-1.5 text-sm text-gray-700">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2C8.134 2 5 5.134 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.866-3.134-7-7-7Zm0 9.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5Z" />
            </svg>
            <span>{{ number_format($distance, 1) }} km từ vị trí của bạn</span>
        </div>
    @endif
        <div class="mt-1 flex items-center gap-1.5 text-sm">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                <path
                    d="M528 320C528 434.9 434.9 528 320 528C205.1 528 112 434.9 112 320C112 205.1 205.1 112 320 112C434.9 112 528 205.1 528 320zM64 320C64 461.4 178.6 576 320 576C461.4 576 576 461.4 576 320C576 178.6 461.4 64 320 64C178.6 64 64 178.6 64 320zM296 184L296 320C296 328 300 335.5 306.7 340L402.7 404C413.7 411.4 428.6 408.4 436 397.3C443.4 386.2 440.4 371.4 429.3 364L344 307.2L344 184C344 170.7 333.3 160 320 160C306.7 160 296 170.7 296 184z" />
            </svg>
            <div class="sm:flex">
                <span class="{{ $status_label == 'Đang mở cửa' ? 'text-[#00b707]' : 'text-red-500' }} font-semibold">{{ $status_label }}</span>
                <span>- {{ $opening_time }} - {{ $closing_time }}</span>
            </div>
        </div>
    </div>
</div>