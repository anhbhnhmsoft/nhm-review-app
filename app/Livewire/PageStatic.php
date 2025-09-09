<?php

namespace App\Livewire;

use App\Services\ArticleService;

class PageStatic extends BaseComponent
{
    public string $slug;
    public $page;

    private ArticleService $articleService;

    public function boot(ArticleService $articleService): void
    {
        parent::setupBase();
        $this->articleService = $articleService;
    }

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->page = $this->articleService->getStaticPage($slug);
    }

    public function render()
    {
        return $this->view('livewire.page-statics.static', [
            'page' => $this->page,
        ]);
    }
}


