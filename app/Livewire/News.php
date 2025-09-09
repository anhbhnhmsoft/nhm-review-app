<?php

namespace App\Livewire;

use App\Services\ArticleService;
use App\Utils\Constants\ArticleType;

class News extends BaseComponent
{
    public $latestNews;
    public $newsArticles;
    public $pressArticles;
    public $handbookArticles;

    private ArticleService $articleService;

    public function boot(ArticleService $articleService): void
    {
        parent::setupBase();
        $this->articleService = $articleService;
    }

    public function mount(): void
    {
        $this->latestNews = $this->articleService->getLatestNews(4);
        $excludeIds = $this->latestNews->pluck('id')->toArray();
        
        $this->newsArticles = $this->articleService->getNewsArticles(6, $excludeIds);
        $this->pressArticles = $this->articleService->getPressArticles(6, $excludeIds);
        $this->handbookArticles = $this->articleService->getHandbookArticles(6, $excludeIds);
    }

    public function render()
    {
        return $this->view('livewire.news.index');
    }
}
