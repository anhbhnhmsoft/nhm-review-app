<div>
    <div class="relative h-96 bg-gradient-to-r from-blue-600 to-green-600 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative h-full flex items-center justify-center">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">Tin Tức & Cẩm Nang</h1>
                <p class="text-xl md:text-2xl">Khám phá những địa điểm tuyệt vời tại AFY</p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-8 bg-white transform rotate-1"></div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <section class="mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8">Các bài viết mới nhất</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-latest-articles :articles="$latestNews" />
            </div>
        </section>
        @if($newsArticles->count() > 0)
            <x-other-articles :articles="$newsArticles" title="Tin tức" />
        @endif

        @if($pressArticles->count() > 0)
            <x-other-articles :articles="$pressArticles" title="Báo chí" />
        @endif

        @if($handbookArticles->count() > 0)
            <x-other-articles :articles="$handbookArticles" title="Cẩm nang" />
        @endif
    </div>
</div>
