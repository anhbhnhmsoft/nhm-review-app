@php
    use App\Utils\HelperFunction;

@endphp
<main>
    @section('vite_includes')
        @vite(['resources/js/dashboard.js'])
    @endsection
    <nav class="h-[50px] w-full bg-green-500 inline-flex items-center justify-around gap-4 px-[325px]">
        <a href="#" class="text-base font-bold text-white">
            Mới nhất
        </a>
        <a href="#" class="text-base font-bold text-white">
            Địa điểm uy tín
        </a>
        <a href="#" class="text-base font-bold text-white">
            Khuyến mãi hot
        </a>
        <a href="#" class="text-base font-bold text-white">
            Video
        </a>
        <a href="#" class="text-base font-bold text-white">
            Cẩm nang
        </a>
        <a href="#" class="text-base font-bold text-white">
            Tin tức
        </a>
    </nav>

    {{-- banner index --}}
    <section class="swiper-container w-full overflow-x-hidden" id="banner__header">
        <div class="swiper-wrapper">
            @if($banner_index && $banner_index->count() > 0)
                @foreach($banner_index as $bannerIndexValue)
                    <a href="{{ $bannerIndexValue->link ?: "#" }}" class="swiper-slide">
                        <img src="{{HelperFunction::generateURLImagePath($bannerIndexValue->image_path)}}"
                             alt="{{$bannerIndexValue->alt_banner ?: "AFY - App review số 1"}}"
                             class="w-full h-[800px] object-cover"
                             loading="lazy"
                        >
                    </a>
                @endforeach
            @else
                <a href="#" class="swiper-slide">
                    <img src="{{asset('images/no-image.jpg')}}" class="w-full h-[800px] object-cover">
                </a>
            @endif
        </div>
    </section>

    {{--  badge category + search   --}}
    <section class="section bg-white !pt-[121px]">
        {{--  search  --}}
        <div class="section_absolute w-full flex top-[-60px]  left-0 right-0 z-10">
            <div class="grid grid-cols-4 w-full items-center px-[40px] py-[20px] gap-4 bg-white rounded-lg shadow-sm">
                <input type="text" placeholder="Bạn muốn tìm kiếm gì và ở đâu? Hãy tìm trên Afy ngay nhé"
                       class="input !border-none !outline-none px-[25px] !py-[23px] w-full col-span-2"/>
                <button class="btn text-white bg-blue-600 hover:bg-blue-400 rounded-lg px-[25px] py-[23px]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                         class="size-8 text-white">
                        <path fill-rule="evenodd"
                              d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                              clip-rule="evenodd"/>
                    </svg>
                    <span class="text-base">Khu vực</span>
                </button>
                <a href="{{ route('search-store') }}" class="btn text-white bg-green-600 hover:bg-green-400 rounded-lg px-[25px] py-[23px]">
                    <span class="text-base">Tìm địa điểm</span>
                </a>
            </div>
        </div>
        {{--  category   --}}
        <div class="grid lg:grid-cols-7 gap-[40px]">
            @if($categories && $categories->count() > 0)
                @foreach($categories as $category)
                    @if($category->show_header_home_page)
                        <div class="flex items-center justify-center">
                            <div class="group flex flex-col items-center justify-center gap-4 w-fit h-fit">
                                <div
                                    class="p-3 rounded-full shadow-[0_0_10px_#ccc] cursor-pointer transform transition-transform duration-300 group-hover:scale-110">
                                    <img src="{{HelperFunction::generateURLImagePath($category->logo)}}"
                                         alt="{{$category->slug}}"
                                         class="w-14 h-14 object-contain"/>
                                </div>
                                <p class="font-bold uppercase text-lg">
                                    {{$category->name}}
                                </p>
                            </div>
                        </div>
                    @endif

                    @if($loop->last)
                        <div class="flex items-center justify-center">
                            <div class="group flex flex-col items-center justify-center gap-4 w-fit h-fit">
                                <div
                                    class="p-3 rounded-full shadow-[0_0_10px_#ccc] cursor-pointer transform transition-transform duration-300 group-hover:scale-110">
                                    <img src="{{asset('images/logo/them.svg')}}" alt="xem-them"
                                         class="w-14 h-14 object-contain"/>
                                </div>
                                <p class="font-bold uppercase text-lg">
                                    Thêm
                                </p>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
    </section>

    {{--  nổi bật --}}
    <section class="section bg-slate-100">
        <h1 class="uppercase font-bold text-4xl text-black text-center">Địa điểm nổi bật</h1>
        <div class="grid grid-cols-4 gap-y-8 pt-16">
            @foreach(range(0, 7) as $number)
                <x-card-store/>
            @endforeach
        </div>
    </section>

    <section class="section bg-white">
        {{--  Banner   --}}
        <div class="relative">
            <div class="swiper-container !w-full overflow-x-hidden" id="banner__ads">
                <div class="swiper-wrapper w-full">
                    @if($banners && $banners->count() > 0)
                        @foreach($banners as $bannerValue)
                            <a href="{{ $bannerValue->link ?: "#" }}" class="swiper-slide">
                                <img src="{{HelperFunction::generateURLImagePath($bannerValue->image_path)}}"
                                     alt="{{$bannerValue->alt_banner ?: "AFY - App review số 1"}}"
                                     class="object-cover w-full h-[220px] rounded-lg"
                                     loading="lazy"
                                >
                            </a>
                        @endforeach
                    @else
                        @foreach(range(0, 4) as $number)
                            <a href="#" class="swiper-slide">
                                <img src="{{asset('images/no-image.jpg')}}"
                                     class="object-cover w-full h-[220px] rounded-lg" alt="AFY - App review số 1">
                            </a>
                        @endforeach
                    @endif
                </div>
                <div
                    class="swiper-button-prev !w-[40px] !h-[40px] bg-white border rounded-full !border-gray-600 !left-[-23px] shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="!size-5 text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                    </svg>
                </div>
                <div
                    class="swiper-button-next !w-[40px] !h-[40px] bg-white border rounded-full !border-gray-600 !right-[-23px] shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="!size-5 text-gray-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </div>
                <div class="swiper-pagination !top-auto !bottom-[-40px]"></div>
            </div>
        </div>

        <div class="mt-24">
            <x-dashboard.category-slide/>
        </div>

        <div class="mt-24">
            <x-dashboard.category-slide/>
        </div>
    </section>
    <section class="section bg-slate-100">
        <div class="grid grid-cols-2">
            <div class="flex flex-col">
                <h2 class="font-medium text-2xl text-black mb-4">Báo chí nói gì về chúng tôi</h2>
                <h1 class="font-medium text-4xl text-black mb-4">
                    AFY - Website review các địa điểm hàng đầu việt nam
                </h1>
                <div class="inline-flex items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/>
                    </svg>
                    <span class="font-medium text-gray-500">25/08/2025</span>
                </div>
                <div class="my-4"
                     x-data="{ text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam tincidunt magna non malesuada.malesuadam alesuadam alesuadamale suadamale suadamalesuadamal esuadamalesuadama lesuadamalesuadamal es uadamalesuada' }">
                    <p class="text-gray-500" x-text="text.length > 200 ? text.slice(0, 200) + '...' : text"></p>
                </div>
                <div class="">
                    <a class="btn btn-lg bg-blue-600 hover:bg-blue-400 text-white rounded-xl">Xem thêm</a>
                </div>
            </div>
            <img src="{{asset('images/baochi-removebg.png')}}"/>
        </div>
    </section>

    <section class="section bg-white">
        <div class="mt-24">
            <x-dashboard.category-slide/>
        </div>

        <div class="mt-24">
            <x-dashboard.category-slide/>
        </div>

        <div class="mt-24">
            <x-dashboard.category-slide/>
        </div>
    </section>

    <section class="section bg-white">
        <h1 class="font-bold text-black text-4xl mb-4">Tin tức nổi bật</h1>
        <div class="mt-4 grid grid-cols-12 grid-rows-2 auto-rows-fr gap-4">
            <!-- Nội dung của phần tử đầu tiên -->
            <div
                class="col-span-6 row-span-2 bg-transparent border-0 rounded-md transition-all duration-300 ease-in-out relative flex flex-col group">
                <a class="rounded-md h-full w-full block overflow-hidden !shrink-0 ">
                    <img src="{{asset('images/banner3.png')}}" alt="alt"
                         class="group-hover:scale-105 transform transition-all duration-300 object-cover aspect-none h-full w-full"/>
                </a>
                <div
                    class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-b from-transparent to-black/80 flex flex-col grow">
                    <h3 class="text-2xl font-bold text-white mb-4 truncate max-h-16">
                        <a class="">Top 9 khách sạn giá rẻ, chất lượng tốt tại Phú Quốc</a>
                    </h3>
                    <div class="inline-flex items-center gap-x-4">
                        <div class="inline-flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z"/>
                            </svg>
                            <p class="text-white">16/5/2025</p>
                        </div>
                        <div class="inline-flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            <p class="text-white">17725</p>
                        </div>
                    </div>
                </div>
            </div>

            @foreach(range(0, 3) as $number)
                <div class="col-span-3 row-span-1 bg-transparent border-0 group flex flex-col gap-2">
                    <a class="rounded-md w-full block overflow-hidden !shrink-0 relative pb-[100%] h-auto">
                        <img src="{{asset('images/banner3.png')}}" alt="alt"
                             class="group-hover:scale-105 transform transition-all duration-300 object-cover aspect-[282/186] h-full w-full absolute top-0 left-0"/>
                    </a>
                    <div class="flex flex-col grow">
                        <h3 class="text-lg font-bold text-slate-800 mb-2 max-h-16 truncate">
                            <a class="">Top 9 khách sạn giá rẻ, chất lượng tốt tại Phú Quốc</a>
                        </h3>
                        <div class="inline-flex items-center gap-x-4">
                            <div class="inline-flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="size-5 text-slate-800">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z"/>
                                </svg>
                                <p class="text-slate-800">16/5/2025</p>
                            </div>
                            <div class="inline-flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5 text-slate-800">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>

                                <p class="text-slate-800">17725</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex mt-12 items-center justify-center">
            <button class="btn btn-lg bg-blue-600 hover:bg-blue-400 text-white rounded-lg">Xem thêm</button>
        </div>
    </section>

    <section class="section bg-slate-100">
        <h1 class="font-bold text-black text-4xl mb-2">Video nổi bật</h1>
        <h2 class="text-black text-lg mb-4">Các Video nổi bật hàng đầu của chúng tôi</h2>
        <div class="grid grid-cols-12 grid-rows-2 gap-2.5 auto-cols-auto">
            <div class="col-span-3 row-span-2 relative h-full overflow-hidden rounded-md group">
                <div class="pb-[70%] h-full relative overflow-hidden">
                    <img src="{{asset('images/banner2.png')}}"
                         class="block absolute top-0 left-0 h-full w-full object-cover"/>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-b from-transparent to-black/40 w-full flex flex-col justify-end">
                    <div class="absolute inset-0 flex items-center justify-center cursor-pointer z-10">
                        <div
                            class="bg-red-500 opacity-90 h-[40px] w-[40px] rounded-full flex items-center justify-center transition-all duration-300 ease-in-out transform group-hover:scale-[2000%]">
                        </div>
                    </div>
                    <div
                        class="absolute inset-0 flex flex-col items-center justify-center gap-2 z-20 group cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="size-6 text-white">
                            <path fill-rule="evenodd"
                                  d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <p class="hidden opacity-0 group-hover:opacity-100 group-hover:block transition-all duration-300 ease-in-out text-white font-medium">
                            Xem video</p>
                    </div>
                    <div class="p-2">
                        <h3 class="text-lg font-bold text-white max-h-16 truncate">
                            Tiệm trà thái phạt
                        </h3>
                        <div class="flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                 class="size-4 text-white">
                                <path fill-rule="evenodd"
                                      d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-white max-h-16 truncate">Phường Đồng Nai</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-3 row-span-1 relative h-full overflow-hidden rounded-md group">
                <div class="pb-[70%] h-full relative overflow-hidden">
                    <img src="{{asset('images/banner2.png')}}"
                         class="block absolute top-0 left-0 h-full w-full object-cover"/>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-b from-transparent to-black/40 w-full flex flex-col justify-end">
                    <div class="absolute inset-0 flex items-center justify-center cursor-pointer z-10">
                        <div
                            class="bg-red-500 opacity-90 h-[40px] w-[40px] rounded-full flex items-center justify-center transition-all duration-300 ease-in-out transform group-hover:scale-[2000%]">
                        </div>
                    </div>
                    <div
                        class="absolute inset-0 flex flex-col items-center justify-center gap-2 z-20 group cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="size-6 text-white">
                            <path fill-rule="evenodd"
                                  d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <p class="hidden opacity-0 group-hover:opacity-100 group-hover:block transition-all duration-300 ease-in-out text-white font-medium">
                            Xem video</p>
                    </div>
                    <div class="p-2">
                        <h3 class="text-lg font-bold text-white max-h-16 truncate">
                            Tiệm trà thái phạt
                        </h3>
                        <div class="flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                 class="size-4 text-white">
                                <path fill-rule="evenodd"
                                      d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-white max-h-16 truncate">Phường Đồng Nai</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-3 row-span-2 relative h-full overflow-hidden rounded-md group">
                <div class="pb-[70%] h-full relative overflow-hidden">
                    <img src="{{asset('images/banner2.png')}}"
                         class="block absolute top-0 left-0 h-full w-full object-cover"/>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-b from-transparent to-black/40 w-full flex flex-col justify-end">
                    <div class="absolute inset-0 flex items-center justify-center cursor-pointer z-10">
                        <div
                            class="bg-red-500 opacity-90 h-[40px] w-[40px] rounded-full flex items-center justify-center transition-all duration-300 ease-in-out transform group-hover:scale-[2000%]">
                        </div>
                    </div>
                    <div
                        class="absolute inset-0 flex flex-col items-center justify-center gap-2 z-20 group cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="size-6 text-white">
                            <path fill-rule="evenodd"
                                  d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <p class="hidden opacity-0 group-hover:opacity-100 group-hover:block transition-all duration-300 ease-in-out text-white font-medium">
                            Xem video</p>
                    </div>
                    <div class="p-2">
                        <h3 class="text-lg font-bold text-white max-h-16 truncate">
                            Tiệm trà thái phạt
                        </h3>
                        <div class="flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                 class="size-4 text-white">
                                <path fill-rule="evenodd"
                                      d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-white max-h-16 truncate">Phường Đồng Nai</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-3 row-span-1 relative h-full overflow-hidden rounded-md group">
                <div class="pb-[70%] h-full relative overflow-hidden">
                    <img src="{{asset('images/banner2.png')}}"
                         class="block absolute top-0 left-0 h-full w-full object-cover"/>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-b from-transparent to-black/40 w-full flex flex-col justify-end">
                    <div class="absolute inset-0 flex items-center justify-center cursor-pointer z-10">
                        <div
                            class="bg-red-500 opacity-90 h-[40px] w-[40px] rounded-full flex items-center justify-center transition-all duration-300 ease-in-out transform group-hover:scale-[2000%]">
                        </div>
                    </div>
                    <div
                        class="absolute inset-0 flex flex-col items-center justify-center gap-2 z-20 group cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="size-6 text-white">
                            <path fill-rule="evenodd"
                                  d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <p class="hidden opacity-0 group-hover:opacity-100 group-hover:block transition-all duration-300 ease-in-out text-white font-medium">
                            Xem video</p>
                    </div>
                    <div class="p-2">
                        <h3 class="text-lg font-bold text-white max-h-16 truncate">
                            Tiệm trà thái phạt
                        </h3>
                        <div class="flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                 class="size-4 text-white">
                                <path fill-rule="evenodd"
                                      d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-white max-h-16 truncate">Phường Đồng Nai</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-3 row-span-1 relative h-full overflow-hidden rounded-md group">
                <div class="pb-[70%] h-full relative overflow-hidden">
                    <img src="{{asset('images/banner2.png')}}"
                         class="block absolute top-0 left-0 h-full w-full object-cover"/>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-b from-transparent to-black/40 w-full flex flex-col justify-end">
                    <div class="absolute inset-0 flex items-center justify-center cursor-pointer z-10">
                        <div
                            class="bg-red-500 opacity-90 h-[40px] w-[40px] rounded-full flex items-center justify-center transition-all duration-300 ease-in-out transform group-hover:scale-[2000%]">
                        </div>
                    </div>
                    <div
                        class="absolute inset-0 flex flex-col items-center justify-center gap-2 z-20 group cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="size-6 text-white">
                            <path fill-rule="evenodd"
                                  d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <p class="hidden opacity-0 group-hover:opacity-100 group-hover:block transition-all duration-300 ease-in-out text-white font-medium">
                            Xem video</p>
                    </div>
                    <div class="p-2">
                        <h3 class="text-lg font-bold text-white max-h-16 truncate">
                            Tiệm trà thái phạt
                        </h3>
                        <div class="flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                 class="size-4 text-white">
                                <path fill-rule="evenodd"
                                      d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-white max-h-16 truncate">Phường Đồng Nai</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-span-3 row-span-1 relative h-full overflow-hidden rounded-md group">
                <div class="pb-[70%] h-full relative overflow-hidden">
                    <img src="{{asset('images/banner2.png')}}"
                         class="block absolute top-0 left-0 h-full w-full object-cover"/>
                </div>
                <div
                    class="absolute inset-0 bg-gradient-to-b from-transparent to-black/40 w-full flex flex-col justify-end">
                    <div class="absolute inset-0 flex items-center justify-center cursor-pointer z-10">
                        <div
                            class="bg-red-500 opacity-90 h-[40px] w-[40px] rounded-full flex items-center justify-center transition-all duration-300 ease-in-out transform group-hover:scale-[2000%]">
                        </div>
                    </div>
                    <div
                        class="absolute inset-0 flex flex-col items-center justify-center gap-2 z-20 group cursor-pointer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="size-6 text-white">
                            <path fill-rule="evenodd"
                                  d="M4.5 5.653c0-1.427 1.529-2.33 2.779-1.643l11.54 6.347c1.295.712 1.295 2.573 0 3.286L7.28 19.99c-1.25.687-2.779-.217-2.779-1.643V5.653Z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <p class="hidden opacity-0 group-hover:opacity-100 group-hover:block transition-all duration-300 ease-in-out text-white font-medium">
                            Xem video</p>
                    </div>
                    <div class="p-2">
                        <h3 class="text-lg font-bold text-white max-h-16 truncate">
                            Tiệm trà thái phạt
                        </h3>
                        <div class="flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                 class="size-4 text-white">
                                <path fill-rule="evenodd"
                                      d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm text-white max-h-16 truncate">Phường Đồng Nai</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section bg-white">
        <h1 class="font-bold text-black text-4xl mb-4">Tin tức cẩm nang</h1>
        <div class="mt-4 grid grid-cols-12 grid-rows-2 auto-rows-fr gap-4">
            <!-- Nội dung của phần tử đầu tiên -->
            <div
                class="col-span-6 row-span-2 bg-transparent border-0 rounded-md transition-all duration-300 ease-in-out relative flex flex-col group">
                <a class="rounded-md h-full w-full block overflow-hidden !shrink-0 ">
                    <img src="{{asset('images/banner3.png')}}" alt="alt"
                         class="group-hover:scale-105 transform transition-all duration-300 object-cover aspect-none h-full w-full"/>
                </a>
                <div
                    class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-b from-transparent to-black/80 flex flex-col grow">
                    <h3 class="text-2xl font-bold text-white mb-4 truncate max-h-16">
                        <a class="">Top 9 khách sạn giá rẻ, chất lượng tốt tại Phú Quốc</a>
                    </h3>
                    <div class="inline-flex items-center gap-x-4">
                        <div class="inline-flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z"/>
                            </svg>
                            <p class="text-white">16/5/2025</p>
                        </div>
                        <div class="inline-flex items-center space-x-1">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-5 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                            </svg>
                            <p class="text-white">17725</p>
                        </div>
                    </div>
                </div>
            </div>

            @foreach(range(0, 3) as $number)
                <div class="col-span-3 row-span-1 bg-transparent border-0 group flex flex-col gap-2">
                    <a class="rounded-md w-full block overflow-hidden !shrink-0 relative pb-[100%] h-auto">
                        <img src="{{asset('images/banner3.png')}}" alt="alt"
                             class="group-hover:scale-105 transform transition-all duration-300 object-cover aspect-[282/186] h-full w-full absolute top-0 left-0"/>
                    </a>
                    <div class="flex flex-col grow">
                        <h3 class="text-lg font-bold text-slate-800 mb-2 max-h-16 truncate">
                            <a class="">Top 9 khách sạn giá rẻ, chất lượng tốt tại Phú Quốc</a>
                        </h3>
                        <div class="inline-flex items-center gap-x-4">
                            <div class="inline-flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="size-5 text-slate-800">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z"/>
                                </svg>
                                <p class="text-slate-800">16/5/2025</p>
                            </div>
                            <div class="inline-flex items-center space-x-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5 text-slate-800">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                </svg>

                                <p class="text-slate-800">17725</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex mt-12 items-center justify-center">
            <button class="btn btn-lg bg-blue-600 hover:bg-blue-400 text-white rounded-lg">Xem thêm</button>
        </div>
    </section>
</main>
