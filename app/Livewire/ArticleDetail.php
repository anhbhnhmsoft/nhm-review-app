<?php

namespace App\Livewire;

use App\Models\Article;
use App\Services\ArticleService;
use App\Utils\TableOfContentsHelper;
use Livewire\Component;

class ArticleDetail extends BaseComponent
{
    public Article $article;
    public array $tableOfContents = [];
    public string $processedContent = '';
    public $relatedArticles;

    public function mount(ArticleService $articleService, string $slug)
    {
        parent::setupBase();
        $this->article = $articleService->getArticleBySlug($slug);
        
        $articleService->incrementViewCount($slug);
        
        $this->article->refresh();
        
        $this->relatedArticles = $articleService->getRelatedArticles($slug, 3);
        
        $result = TableOfContentsHelper::generateTableOfContents($this->article->content);
        $this->tableOfContents = $result['toc'];
        $this->processedContent = $result['content'];
    }

    public function render()
    {
        return $this->view('livewire.articles.detail', [
            'article' => $this->article,
        ])->layout('layouts.app');
    }
}
