<div class="mx-auto max-w-7xl px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-3">
                <article class="bg-white rounded-lg shadow-lg p-8">
                    <header class="mb-8">
                        <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $article->title }}</h1>
                        <div class="flex items-center text-sm text-gray-600 mb-6">
                            <div class="flex items-center space-x-2 mr-6">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">{{ substr($article->author, 0, 1) }}</span>
                                </div>
                                <span class="font-medium">{{ $article->author }}</span>
                            </div>
                            <span class="mr-6">{{ $article->created_at->format('d M Y') }}</span>
                            <span>{{ $article->view ?? 0 }} lượt xem</span>
                        </div>

                        @if(!empty($article->image_path))
                            <img src="{{ route('public_image', ['file_path' => $article->image_path]) }}"
                                 alt="{{ $article->title }}"
                                 class="w-full h-96 object-cover rounded-lg mb-8 shadow-md">
                        @endif

                        @if(!empty($article->excerpt))
                            <p class="text-lg text-slate-600 mb-6 italic border-l-4 border-blue-500 pl-4">{{ $article->excerpt }}</p>
                        @endif
                    </header>

                    <div class="rich-content prose max-w-none">
                        {!! $processedContent !!}
                    </div>
                </article>
            </div>

            <div class="lg:col-span-1">
                <div class="sticky top-8">
                    @if(count($tableOfContents) > 0)
                        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Nội dung bài viết</h3>
                            <nav class="space-y-2">
                                @foreach($tableOfContents as $item)
                                    <a href="#{{ $item['id'] }}" 
                                       class="block text-sm text-gray-600 hover:text-blue-600 transition-colors {{ $item['level'] == 1 ? 'font-semibold' : 'ml-4' }}">
                                        {{ $item['text'] }}
                                    </a>
                                @endforeach
                            </nav>
                        </div>
                    @endif

                    @if($relatedArticles->count() > 0)
                        <div class="bg-white rounded-lg shadow-lg p-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4">Bài viết liên quan</h3>
                            <div class="space-y-4">
                                @foreach($relatedArticles as $relatedArticle)
                                    <a href="{{ route('frontend.article-detail', $relatedArticle->slug) }}" class="block group">
                                        <div class="flex space-x-3">
                                            <div class="w-16 h-16 bg-gray-200 rounded-lg flex-shrink-0 overflow-hidden">
                                                @if($relatedArticle->image_path)
                                                    <img src="{{ route('public_image', ['file_path' => $relatedArticle->image_path]) }}" 
                                                         alt="{{ $relatedArticle->title }}"
                                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-green-500 flex items-center justify-center">
                                                        <i class="fa-solid fa-newspaper text-white text-lg"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                                    {{ $relatedArticle->title }}
                                                </h4>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    {{ $relatedArticle->author }} • {{ $relatedArticle->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@vite('resources/js/scroll-contents.js')
