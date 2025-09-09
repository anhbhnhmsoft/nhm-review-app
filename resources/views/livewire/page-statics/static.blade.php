<div class="mx-auto max-w-6xl px-4 py-8">
    <h1 class="text-2xl md:text-3xl font-bold mb-4">{{ $page->title }}</h1>
    @if(!empty($page->excerpt))
        <p class="text-slate-600 mb-6">{{ $page->excerpt }}</p>
    @endif
    <div class="rich-content">{!! $page->content !!}</div>
</div>


