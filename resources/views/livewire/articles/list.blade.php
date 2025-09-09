<div>
    <div class="relative h-64 bg-gradient-to-r from-blue-600 to-green-600 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="relative h-full flex items-center justify-center">
            <div class="text-center text-white">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">{{ $typeName }}</h1>
                <p class="text-xl md:text-2xl">Khám phá những bài viết {{ strtolower($typeName) }} mới nhất</p>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 h-8 bg-white transform rotate-1"></div>
    </div>

    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <x-latest-articles :articles="$articles" />
        </div>

        <div class="mt-12">
            {{ $articles->links() }}
        </div>
    </div>
</div>
