<?php

namespace App\Livewire;

use App\Services\ArticleService;
use App\Utils\Constants\ArticleType;
use Livewire\Component;
use Livewire\WithPagination;

class ArticleList extends BaseComponent
{
    use WithPagination;

    public string $type;
    public string $typeName;

    private ArticleService $articleService;

    public function boot(ArticleService $articleService): void
    {
        parent::setupBase();
        $this->articleService = $articleService;
    }

    public function mount(): void
    {
        $routeName = request()->route()->getName();
        
        switch ($routeName) {
            case 'frontend.articles.news':
                $this->type = ArticleType::NEWS->value;
                $this->typeName = 'Tin tức';
                break;
            case 'frontend.articles.press':
                $this->type = ArticleType::PRESS->value;
                $this->typeName = 'Báo chí';
                break;
            case 'frontend.articles.handbook':
                $this->type = ArticleType::HANDBOOK->value;
                $this->typeName = 'Cẩm nang';
                break;
            case 'frontend.articles.promotion':
                $this->type = ArticleType::PROMOTION->value;
                $this->typeName = 'Khuyến mãi hot';
                break;
            default:
                $this->type = ArticleType::NEWS->value;
                $this->typeName = 'Bài viết';
        }
    }

    public function render()
    {
        $articles = $this->articleService->getArticlesByType($this->type, 12);

        return $this->view('livewire.articles.list', [
            'articles' => $articles,
        ])->layout('layouts.app');
    }
}