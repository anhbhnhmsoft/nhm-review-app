@props(['articles', 'title' => 'Bài viết khác'])

<section class="mb-16">
    @once
        @vite(['resources/js/dashboard.js'])
    @endonce
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl md:text-4xl font-bold text-gray-900">{{ $title }}</h2>
        @php
            $routeName = match($title) {
                'Tin tức' => 'frontend.articles.news',
                'Báo chí' => 'frontend.articles.press',
                'Cẩm nang' => 'frontend.articles.handbook',
                default => '#'
            };
        @endphp
        <a href="{{ $routeName !== '#' ? route($routeName) : '#' }}" class="text-green-600 hover:text-green-800 font-medium">
            Xem thêm >>
        </a>
    </div>
    
    @php
        $swiperId = 'test-' . (string) Str::of($title)->slug('-');
        if (trim($swiperId, '-') === 'test-') {
            $swiperId = 'test-' . uniqid();
        }
    @endphp

    <div class="relative mt-10">
        <div class="swiper-container !w-full !h-fit overflow-hidden" data-swiper-id="{{ $swiperId }}">
            <div class="swiper-wrapper py-4 !w-full !h-fit">
                @foreach($articles as $article)
                    <div class="swiper-slide !h-fit">
                        <a href="{{ route('frontend.article-detail', $article->slug) }}" class="block group">
                            <div class="bg-white rounded-xl overflow-hidden hover:shadow-md transition-shadow duration-300 h-full">
                                <div class="relative">
                                    <div class="aspect-[4/3] overflow-hidden">
                                        @if($article->image_path)
                                            <img src="{{ route('public_image', ['file_path' => $article->image_path]) }}" 
                                                 alt="{{ $article->title }}" 
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                                <i class="fa-solid fa-newspaper text-white text-6xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                        {{ $article->title }}
                                    </h3>
                                    <span class="text-sm text-gray-500">
                                        {{ $article->author }} • {{ $article->created_at->format('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </a>
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

    <script>
        (function() {
            if (!window.swiperInstances) {
                window.swiperInstances = {};
            }

            const componentId = '{{ $swiperId }}';

            const initThisSwiper = () => {
                if (window.swiperInstances[componentId]) {
                    try {
                        window.swiperInstances[componentId].destroy(true, true);
                    } catch (e) {}
                }

                setTimeout(() => {
                    const swiperEl = document.querySelector(`[data-swiper-id="${componentId}"]`);
                    if (swiperEl) {
                        window.swiperInstances[componentId] = new window.Swiper(swiperEl, {
                            slidesPerView: 1,
                            spaceBetween: 30,
                            navigation: {
                                nextEl: swiperEl.querySelector('.swiper-button-next'),
                                prevEl: swiperEl.querySelector('.swiper-button-prev'),
                            },
                            loop: true,
                            speed: 500,
                            breakpoints: {
                                640: { slidesPerView: 2, spaceBetween: 16 },
                                1024: { slidesPerView: 3 },
                            }
                        });
                    }
                }, 100);
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initThisSwiper);
            } else {
                initThisSwiper();
            }
        })();
    </script>
</section>