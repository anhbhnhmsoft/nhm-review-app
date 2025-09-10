@php
    use App\Utils\Constants\ConfigName;use App\Utils\HelperFunction;
    use Illuminate\Support\Str;

    $categoryHadShow = new \Illuminate\Support\Collection();
@endphp
<main>
    @section('vite_includes')
        @vite(['resources/js/dashboard.js'])
    @endsection
    {{-- banner index --}}
    <section class="swiper-container w-full overflow-x-hidden h-[18rem] lg:h-[38rem]" id="banner__header">
        <div class="swiper-wrapper">
            @if($banner_index && $banner_index->count() > 0)
                @foreach($banner_index as $bannerIndexValue)
                    <a
                        @if($bannerIndexValue->link)
                            href="{{ $bannerIndexValue->link }}" target="_blank"
                        @endif
                        class="swiper-slide">
                        <img src="{{HelperFunction::generateURLImagePath($bannerIndexValue->image_path)}}"
                             alt="{{$bannerIndexValue->alt_banner ?: "AFY - App review số 1"}}"
                             class="w-full h-full object-cover"
                             loading="lazy">
                    </a>
                @endforeach
            @else
                <a href="#" class="swiper-slide">
                    <img src="{{asset('images/no-image.jpg')}}" class="w-full h-[800px] object-cover"
                         alt="AFY - App review số 1">
                </a>
            @endif
        </div>
    </section>

    {{--  badge category + search   --}}
    <section class="d_section bg-white">
        <div class="container pad_container">
            {{--  search   --}}
            <livewire:dashboard.search-box/>
            {{--  category   --}}
            <div class="grid grid-cols-3 lg:grid-cols-7 gap-[40px]">
                @if($categories && $categories->count() > 0)
                    @foreach($categories as $category)
                        @if($category->show_header_home_page)
                            <a href="{{route('frontend.search-store',['filters' => ['category_ids' => [$category->id]]])}}"
                               class="flex items-center justify-center">
                                <div class="group flex flex-col items-center justify-center gap-4 w-fit h-fit">
                                    <div
                                        class="p-3 rounded-full shadow-[0_0_10px_#ccc] cursor-pointer transform transition-transform duration-300 group-hover:scale-110">
                                        <img src="{{HelperFunction::generateURLImagePath($category->logo)}}"
                                             alt="{{$category->slug}}"
                                             class="w-14 h-14 object-contain"/>
                                    </div>
                                    <p class="font-bold uppercase text-base text-center">
                                        {{$category->name}}
                                    </p>
                                </div>
                            </a>
                        @endif

                        @if($loop->last)
                            <a href="{{route('frontend.search-store')}}"
                               class="flex items-center justify-center">
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
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    {{--  nổi bật --}}
    <section class="d_section bg-slate-100">
        <div class="container pad_container">
            <h1 class="uppercase font-bold text-xl lg:text-4xl text-black text-center">Địa điểm nổi bật</h1>

            @if($storesFeatured->count() > 0)
                {{-- desktop --}}
                <div class="hidden lg:grid grid-cols-3 xl:grid-cols-4 gap-4 lg:mt-16">
                    @foreach($storesFeatured as $store)
                        <livewire:dashboard.card-store :store="$store"/>
                    @endforeach
                </div>

                {{-- mobile --}}
                <div class="relative block lg:hidden lg:mt-16">
                    <div class="swiper-container !w-full !h-fit overflow-hidden store__category">
                        <div class="swiper-wrapper py-4 !w-full !h-fit">
                            @foreach($storesFeatured as $store)
                                <div class="swiper-slide !h-fit">
                                    <livewire:dashboard.card-store :store="$store"/>
                                </div>
                            @endforeach
                        </div>
                        <div
                            class="swiper-button-prev">
                            <i class="fa-solid fa-chevron-left text-gray-600"></i>
                        </div>
                        <div
                            class="swiper-button-next">
                            <i class="fa-solid fa-chevron-right text-gray-600"></i>
                        </div>
                    </div>
                </div>
            @else
                <div role="alert" class="alert alert-warning lg:mt-16">
                    <i class="fa-solid fa-triangle-exclamation text-4xl"></i>
                    <span>Không có cửa hàng nổi bật nào</span>
                </div>
            @endif
        </div>
    </section>

    <section class="d_section bg-white">
        {{--  Banner   --}}
        <div class="container pad_container">
            <div class="relative">
                <div class="swiper-container !w-full overflow-x-hidden" id="banner__ads">
                    <div class="swiper-wrapper w-full">
                        @if($banners && $banners->count() > 0)
                            @foreach($banners as $bannerValue)
                                <a
                                    @if(!empty($bannerValue->link))
                                        href="{{$bannerValue->link}}" target="_blank"
                                    @endif
                                    class="swiper-slide">
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
                        class="swiper-button-prev">
                        <i class="fa-solid fa-chevron-left text-gray-600"></i>
                    </div>
                    <div
                        class="swiper-button-next">
                        <i class="fa-solid fa-chevron-right text-gray-600"></i>
                    </div>
                    <div class="swiper-pagination !top-auto !bottom-[-40px]"></div>
                </div>
            </div>
        </div>
        {{--  Category 1 + 2 --}}
        @if($categories && $categories->count() > 0)
            @php $shown = 0; @endphp
            @foreach($categories as $category)
                @if($category->show_index_home_page && empty($category->parent_id))
                    @if(!$categoryHadShow->contains($category->id))
                        @php
                            $categoryHadShow->push($category->id);
                            $shown++;
                        @endphp
                        <div class="container pad_container">
                            <livewire:dashboard.store-slide :category="$category"/>
                        </div>
                        @if($shown >= 2)
                            @break
                        @endif
                    @endif
                @endif
            @endforeach
        @endif
    </section>

    <section class="d_section bg-slate-100">
        <div class="container pad_container">
            <div class="grid grid-cols-1 md:grid-cols-2">
                <div class="flex flex-col">
                    @if($pressArticle)
                        <h2 class="font-medium text-2xl text-black mb-4">{{ $pressArticle->title }}</h2>
                        <h1 class="font-medium text-4xl text-black mb-4">
                            {{ $pressFirstLine }}
                        </h1>
                        <div class="inline-flex items-center gap-2 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-6 text-gray-500">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z"/>
                            </svg>
                            <span
                                class="font-medium text-gray-500">{{ $pressArticle->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="my-4">
                            <p class="text-gray-500">{{ Str::limit(strip_tags($pressArticle->content), 200) }}</p>
                        </div>
                        <div class="">
                            <a href="{{ route('frontend.article-detail', $pressArticle->slug) }}"
                               class="btn btn-lg bg-blue-600 hover:bg-blue-700 text-white rounded-xl">Xem thêm</a>
                        </div>
                    @else
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
                             x-data="{ text: 'AFY - Website review các địa điểm hàng đầu việt nam trong bối cảnh kỷ nguyên số hóa, nhiều doanh nghiệp Việt Nam đang đối mặt với thách thức lớn về cách tiếp cận khách hàng mới và xây dựng thương hiệu...' }">
                            <p class="text-gray-500" x-text="text.length > 200 ? text.slice(0, 200) + '...' : text"></p>
                        </div>
                        <div class="">
                            <a class="btn btn-lg bg-blue-600 hover:bg-blue-700 text-white rounded-xl">Xem thêm</a>
                        </div>
                    @endif
                </div>
                @if($pressArticle && $pressArticle->image_path)
                    <img class="hidden lg:block"
                         src="{{ route('public_image', ['file_path' => $pressArticle->image_path]) }}"
                         alt="{{ $pressArticle->title }}"/>
                @else
                    <img class="hidden lg:block" src="{{asset('images/baochi-removebg.png')}}"/>
                @endif
            </div>
        </div>
    </section>
    <section class="d_section bg-white">
        {{--  Category 3 4 5 --}}
        @if($categories && $categories->count() > 0)
            @php $shown = 0; @endphp
            @foreach($categories as $category)
                @if($category->show_index_home_page && empty($category->parent_id))
                    @if(!$categoryHadShow->contains($category->id))
                        @php
                            $categoryHadShow->push($category->id);
                            $shown++;
                        @endphp
                        <div class="container pad_container">
                            <livewire:dashboard.store-slide :category="$category"/>
                        </div>
                        @if($shown >= 3)
                            @break
                        @endif
                    @endif
                @endif
            @endforeach
        @endif
    </section>
    <section class="d_section bg-white">
        <div class="container pad_container">
            <h1 class="font-bold text-black text-4xl mb-4">Tin tức nổi bật</h1>

            @if($newsArticles->count() > 0)
                {{-- Desktop Grid Layout --}}
                <div class="hidden lg:grid mt-4 grid-cols-12 grid-rows-2 auto-rows-fr gap-4">
                    @php $firstArticle = $newsArticles->first(); @endphp
                    <div
                        class="col-span-6 row-span-2 bg-transparent border-0 rounded-md transition-all duration-300 ease-in-out relative flex flex-col group">
                        <a href="{{ route('frontend.article-detail', $firstArticle->slug) }}"
                           class="rounded-md h-full w-full block overflow-hidden !shrink-0 ">
                            @if($firstArticle->image_path)
                                <img src="{{ route('public_image', ['file_path' => $firstArticle->image_path]) }}"
                                     alt="{{ $firstArticle->title }}"
                                     class="group-hover:scale-105 transform transition-all duration-300 object-cover aspect-none h-full w-full"/>
                            @else
                                <img src="{{asset('images/banner3.png')}}" alt="{{ $firstArticle->title }}"
                                     class="group-hover:scale-105 transform transition-all duration-300 object-cover aspect-none h-full w-full"/>
                            @endif
                        </a>
                        <div
                            class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-b from-transparent to-black/80 flex flex-col grow">
                            <h3 class="text-2xl font-bold text-white mb-4 truncate max-h-16">
                                <a href="{{ route('frontend.article-detail', $firstArticle->slug) }}">{{ $firstArticle->title }}</a>
                            </h3>
                            <div class="inline-flex items-center gap-x-4">
                                <div class="inline-flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5"
                                         stroke="currentColor" class="size-5 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z"/>
                                    </svg>
                                    <p class="text-white">{{ $firstArticle->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="inline-flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5"
                                         stroke="currentColor" class="size-5 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                    <p class="text-white">{{ $firstArticle->view ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach($newsArticles->skip(1)->take(4) as $article)
                        <div class="col-span-3 row-span-1 bg-transparent border-0 group flex flex-col gap-2">
                            <a href="{{ route('frontend.article-detail', $article->slug) }}"
                               class="rounded-md w-full block overflow-hidden !shrink-0 relative pb-[100%] h-auto">
                                @if($article->image_path)
                                    <img src="{{ route('public_image', ['file_path' => $article->image_path]) }}"
                                         alt="{{ $article->title }}"
                                         class="group-hover:scale-105 transform transition-all duration-300 object-cover aspect-[282/186] h-full w-full absolute top-0 left-0"/>
                                @else
                                    <img src="{{asset('images/banner3.png')}}" alt="{{ $article->title }}"
                                         class="group-hover:scale-105 transform transition-all duration-300 object-cover aspect-[282/186] h-full w-full absolute top-0 left-0"/>
                                @endif
                            </a>
                            <div class="flex flex-col grow">
                                <h3 class="text-lg font-bold text-slate-800 mb-2 max-h-16 truncate">
                                    <a href="{{ route('frontend.article-detail', $article->slug) }}">{{ $article->title }}</a>
                                </h3>
                                <div class="inline-flex items-center gap-x-4">
                                    <div class="inline-flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5"
                                             stroke="currentColor" class="size-5 text-slate-800">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z"/>
                                        </svg>
                                        <p class="text-slate-800">{{ $article->created_at->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="inline-flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="size-5 text-slate-800">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                        <p class="text-slate-800">{{ $article->view ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Mobile Swiper Layout --}}
                <div class="block lg:hidden mt-4">
                    <div class="swiper-container news-swiper !w-full !h-fit overflow-hidden">
                        <div class="swiper-wrapper py-4 !w-full !h-fit">
                            @foreach($newsArticles as $article)
                                <div class="swiper-slide !h-fit">
                                    <div
                                        class="bg-transparent border border-[#e8e8e8] md:border-0 rounded-md transition-all duration-300 ease-in-out relative flex flex-col group min-h-[300px] shadow-sm md:shadow-none">
                                        <a href="{{ route('frontend.article-detail', $article->slug) }}"
                                           class="rounded-md h-full w-full block overflow-hidden !shrink-0 relative pb-[60%]">
                                            @if($article->image_path)
                                                <img
                                                    src="{{ route('public_image', ['file_path' => $article->image_path]) }}"
                                                    alt="{{ $article->title }}"
                                                    class="group-hover:scale-105 transform transition-all duration-300 object-cover h-full w-full absolute top-0 left-0"/>
                                            @else
                                                <img src="{{asset('images/banner3.png')}}" alt="{{ $article->title }}"
                                                     class="group-hover:scale-105 transform transition-all duration-300 object-cover h-full w-full absolute top-0 left-0"/>
                                            @endif
                                        </a>
                                        <div class="p-4 flex flex-col grow">
                                            <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-2">
                                                <a href="{{ route('frontend.article-detail', $article->slug) }}">{{ $article->title }}</a>
                                            </h3>
                                            <div class="inline-flex items-center gap-x-4 mt-auto">
                                                <div class="inline-flex items-center space-x-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke-width="1.5"
                                                         stroke="currentColor" class="size-4 text-slate-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z"/>
                                                    </svg>
                                                    <p class="text-slate-600 text-sm">{{ $article->created_at->format('d/m/Y') }}</p>
                                                </div>
                                                <div class="inline-flex items-center space-x-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke-width="1.5" stroke="currentColor"
                                                         class="size-4 text-slate-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                    </svg>
                                                    <p class="text-slate-600 text-sm">{{ $article->view ?? 0 }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Navigation buttons -->
                        <div class="swiper-button-prev news-prev">
                            <i class="fa-solid fa-chevron-left text-gray-600"></i>
                        </div>
                        <div class="swiper-button-next news-next">
                            <i class="fa-solid fa-chevron-right text-gray-600"></i>
                        </div>
                    </div>
                </div>
            @else
                <div role="alert" class="alert alert-warning mt-4">
                    <i class="fa-solid fa-triangle-exclamation text-4xl"></i>
                    <span>Không có tin tức nào</span>
                </div>
            @endif

            <div class="flex mt-1 md:mt-12 items-center justify-center">
                <a href="{{ route('frontend.articles.news') }}"
                   class="btn btn-lg bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Xem thêm</a>
            </div>
        </div>
    </section>
    <section class="d_section bg-slate-100">
        <div class="container pad_container">
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
        </div>
    </section>

    <section class="d_section bg-white">
        <div class="container pad_container">
            <h1 class="font-bold text-black text-4xl mb-4">Cẩm nang</h1>

            @if($handbookArticles->count() > 0)
                <div class="hidden lg:grid mt-4 grid-cols-12 grid-rows-2 auto-rows-fr gap-4">
                    @php $firstArticle = $handbookArticles->first(); @endphp
                    <div
                        class="col-span-6 row-span-2 bg-transparent border-0 rounded-md transition-all duration-300 ease-in-out relative flex flex-col group">
                        <a href="{{ route('frontend.article-detail', $firstArticle->slug) }}"
                           class="rounded-md h-full w-full block overflow-hidden !shrink-0 ">
                            @if($firstArticle->image_path)
                                <img src="{{ route('public_image', ['file_path' => $firstArticle->image_path]) }}"
                                     alt="{{ $firstArticle->title }}"
                                     class="group-hover:scale-105 transform transition-all duration-300 object-cover aspect-none h-full w-full"/>
                            @else
                                <img src="{{asset('images/banner3.png')}}" alt="{{ $firstArticle->title }}"
                                     class="group-hover:scale-105 transform transition-all duration-300 object-cover aspect-none h-full w-full"/>
                            @endif
                        </a>
                        <div
                            class="absolute bottom-0 left-0 right-0 p-6 bg-gradient-to-b from-transparent to-black/80 flex flex-col grow">
                            <h3 class="text-2xl font-bold text-white mb-4 truncate max-h-16">
                                <a href="{{ route('frontend.article-detail', $firstArticle->slug) }}">{{ $firstArticle->title }}</a>
                            </h3>
                            <div class="inline-flex items-center gap-x-4">
                                <div class="inline-flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5"
                                         stroke="currentColor" class="size-5 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z"/>
                                    </svg>
                                    <p class="text-white">{{ $firstArticle->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="inline-flex items-center space-x-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5"
                                         stroke="currentColor" class="size-5 text-white">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                    <p class="text-white">{{ $firstArticle->view ?? 0 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @foreach($handbookArticles->skip(1)->take(4) as $article)
                        <div class="col-span-3 row-span-1 bg-transparent border-0 group flex flex-col gap-2">
                            <a href="{{ route('frontend.article-detail', $article->slug) }}"
                               class="rounded-md w-full block overflow-hidden !shrink-0 relative pb-[100%] h-auto">
                                @if($article->image_path)
                                    <img src="{{ route('public_image', ['file_path' => $article->image_path]) }}"
                                         alt="{{ $article->title }}"
                                         class="group-hover:scale-105 transform transition-all duration-300 object-cover aspect-[282/186] h-full w-full absolute top-0 left-0"/>
                                @else
                                    <img src="{{asset('images/banner3.png')}}" alt="{{ $article->title }}"
                                         class="group-hover:scale-105 transform transition-all duration-300 object-cover aspect-[282/186] h-full w-full absolute top-0 left-0"/>
                                @endif
                            </a>
                            <div class="flex flex-col grow">
                                <h3 class="text-lg font-bold text-slate-800 mb-2 max-h-16 truncate">
                                    <a href="{{ route('frontend.article-detail', $article->slug) }}">{{ $article->title }}</a>
                                </h3>
                                <div class="inline-flex items-center gap-x-4">
                                    <div class="inline-flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5"
                                             stroke="currentColor" class="size-5 text-slate-800">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z"/>
                                        </svg>
                                        <p class="text-slate-800">{{ $article->created_at->format('d/m/Y') }}</p>
                                    </div>
                                    <div class="inline-flex items-center space-x-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="size-5 text-slate-800">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                        </svg>
                                        <p class="text-slate-800">{{ $article->view ?? 0 }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="block lg:hidden mt-4">
                    <div class="swiper-container handbook-swiper !w-full !h-fit overflow-hidden">
                        <div class="swiper-wrapper py-4 !w-full !h-fit">
                            @foreach($handbookArticles as $article)
                                <div class="swiper-slide !h-fit">
                                    <div
                                        class="bg-transparent border border-[#e8e8e8] md:border-0 rounded-md transition-all duration-300 ease-in-out relative flex flex-col group min-h-[300px] shadow-sm md:shadow-none">
                                        <a href="{{ route('frontend.article-detail', $article->slug) }}"
                                           class="rounded-md h-full w-full block overflow-hidden !shrink-0 relative pb-[60%]">
                                            @if($article->image_path)
                                                <img
                                                    src="{{ route('public_image', ['file_path' => $article->image_path]) }}"
                                                    alt="{{ $article->title }}"
                                                    class="group-hover:scale-105 transform transition-all duration-300 object-cover h-full w-full absolute top-0 left-0"/>
                                            @else
                                                <img src="{{asset('images/banner3.png')}}" alt="{{ $article->title }}"
                                                     class="group-hover:scale-105 transform transition-all duration-300 object-cover h-full w-full absolute top-0 left-0"/>
                                            @endif
                                        </a>
                                        <div class="p-4 flex flex-col grow">
                                            <h3 class="text-lg font-bold text-slate-800 mb-2 line-clamp-2">
                                                <a href="{{ route('frontend.article-detail', $article->slug) }}">{{ $article->title }}</a>
                                            </h3>
                                            <div class="inline-flex items-center gap-x-4 mt-auto">
                                                <div class="inline-flex items-center space-x-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke-width="1.5"
                                                         stroke="currentColor" class="size-4 text-slate-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M6.75 2.994v2.25m10.5-2.25v2.25m-14.252 13.5V7.491a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v11.251m-18 0a2.25 2.25 0 0 0 2.25 2.25h13.5a2.25 2.25 0 0 0 2.25-2.25m-18 0v-7.5a2.25 2.25 0 0 1 2.25-2.25h13.5a2.25 2.25 0 0 1 2.25 2.25v7.5m-6.75-6h2.25m-9 2.25h4.5m.002-2.25h.005v.006H12v-.006Zm-.001 4.5h.006v.006h-.006v-.005Zm-2.25.001h.005v.006H9.75v-.006Zm-2.25 0h.005v.005h-.006v-.005Zm6.75-2.247h.005v.005h-.005v-.005Zm0 2.247h.006v.006h-.006v-.006Zm2.25-2.248h.006V15H16.5v-.005Z"/>
                                                    </svg>
                                                    <p class="text-slate-600 text-sm">{{ $article->created_at->format('d/m/Y') }}</p>
                                                </div>
                                                <div class="inline-flex items-center space-x-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                         viewBox="0 0 24 24"
                                                         stroke-width="1.5" stroke="currentColor"
                                                         class="size-4 text-slate-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                    </svg>
                                                    <p class="text-slate-600 text-sm">{{ $article->view ?? 0 }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="swiper-button-prev handbook-prev">
                            <i class="fa-solid fa-chevron-left text-gray-600"></i>
                        </div>
                        <div class="swiper-button-next handbook-next">
                            <i class="fa-solid fa-chevron-right text-gray-600"></i>
                        </div>
                    </div>
                </div>
            @else
                <div role="alert" class="alert alert-warning mt-4">
                    <i class="fa-solid fa-triangle-exclamation text-4xl"></i>
                    <span>Không có cẩm nang nào</span>
                </div>
            @endif

            <div class="flex mt-1 md:mt-12 items-center justify-center">
                <a href="{{ route('frontend.articles.handbook') }}"
                   class="btn btn-lg bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Xem thêm</a>
            </div>
        </div>
    </section>

    <section class="d_section relative pb-[200px]">
        <div class="absolute inset-0 z-1 w-full h-full">
            <img src="{{asset('images/bg_booking.jpg')}}" class=" w-full h-full object-cover" alt="Backgroud booking">
        </div>
        <div class="absolute inset-0 w-full h-full z-2 bg-gradient-to-b from-[#1F1302]/0 to-[#1F1302]">
        </div>
        <div class="container pad_container z-3">
            <div class="flex flex-col items-center justify-center gap-2">
                <h1 class="text-white font-bold text-[3rem] uppercase">{{config('app.name')}}</h1>
                <h2 class="text-white mt-4 text-lg lg:text-2xl uppercase tracking-[8px] text-center">LIÊN HỆ VỚI CHÚNG TÔI</h2>
                <div class="flex items-center justify-center mt-2 gap-4">
                    <span class="text-base text-white me-2">Follow us</span>
                    @if(isset($configs[ConfigName::FACEBOOK->value]) && !empty($configs[ConfigName::FACEBOOK->value]))
                        <a href="{{$configs[ConfigName::FACEBOOK->value]}}" target="_blank"
                           class="flex items-center justify-center">
                                        <span
                                            class="transform text-xl text-white transition-all duration-200 hover:translate-y-[-5px] md:text-3xl lg:text-2xl 2xl:text-3xl">
                                            <i class="fa-brands fa-facebook"></i>
                                        </span>
                        </a>
                    @endif
                    @if(isset($configs[ConfigName::YOUTUBE->value]) && !empty($configs[ConfigName::YOUTUBE->value]))
                        <a href="{{$configs[ConfigName::YOUTUBE->value]}}" target="_blank"
                           class="flex items-center justify-center">
                                            <span
                                                class="transform text-xl text-white transition-all duration-200 hover:translate-y-[-5px] md:text-3xl lg:text-2xl 2xl:text-3xl">
                                                <i class="fa-brands fa-youtube"></i>
                                            </span>
                        </a>
                    @endif
                    @if(isset($configs[ConfigName::INSTAGRAM->value]) && !empty($configs[ConfigName::INSTAGRAM->value]))
                        <a href="{{$configs[ConfigName::INSTAGRAM->value]}}" target="_blank"
                           class="flex items-center justify-center">
                                            <span
                                                class="transform text-3xl text-white transition-all duration-200 hover:translate-y-[-5px] md:text-3xl lg:text-2xl 2xl:text-3xl">
                                                <i class="fa-brands fa-instagram"></i>
                                            </span>
                        </a>
                    @endif
                    @if(isset($configs[ConfigName::TIKTOK->value]) && !empty($configs[ConfigName::TIKTOK->value]))
                        <a href="{{$configs[ConfigName::TIKTOK->value]}}" target="_blank"
                           class="flex items-center justify-center">
                                            <span
                                                class="transform text-xl text-white transition-all duration-200 hover:translate-y-[-5px] md:text-3xl lg:text-2xl 2xl:text-3xl">
                                                <i class="fa-brands fa-tiktok"></i>
                                            </span>
                        </a>
                    @endif
                </div>
                <livewire:dashboard.booking-form />
                <h2 class="text-lg lg:text-[2rem] text-white tracking-[2px] uppercase mt-16 text-center">Chất lượng <span class="text-red-500">tạo</span> thành công</h2>
            </div>
        </div>
    </section>
</main>
