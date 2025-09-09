@props(['articles'])
@foreach($articles as $article)
    <a href="{{ route('frontend.article-detail', $article->slug) }}" class="block">
        <article class="group bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300">
        <div class="relative overflow-hidden">
            @if($article->image_path)
                <img src="{{ route('public_image', ['file_path' => $article->image_path]) }}" 
                        alt="{{ $article->title }}" 
                        class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">
            @else
                <div class="w-full h-64 bg-gradient-to-br from-blue-500 via-purple-500 to-green-500 flex items-center justify-center relative">
                    <div class="absolute inset-0 bg-black/20"></div>
                    <i class="fa-solid fa-newspaper text-white text-6xl relative z-10"></i>
                </div>
            @endif
            
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6">
                <h3 class="text-white text-xl md:text-2xl font-bold leading-tight mb-2">
                    {{ $article->title }}
                </h3>
                <p class="text-white/90 text-sm line-clamp-2">
                    {{ $article->author }}  •  {{ $article->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
        </article>
    </a>
@endforeach