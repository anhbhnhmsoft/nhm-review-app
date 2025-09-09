<?php

namespace App\Services;

use App\Models\Article;
use App\Utils\Constants\ArticleType;
use App\Utils\Constants\ArticleStatus;
use Illuminate\Support\Str;

class ArticleService
{
    public function getStaticPages()
    {
        return Article::query()
            ->where('type', ArticleType::FIXED->value)
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->orderBy('title')
            ->get();
    }

    public function getStaticPage(string $slug)
    {
        return Article::query()
            ->where('type', ArticleType::FIXED->value)
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function getLatestNews($limit = 4)
    {
        return Article::query()
            ->whereIn('type', [ArticleType::NEWS->value, ArticleType::HANDBOOK->value, ArticleType::PRESS->value])
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->orderBy('sort', 'asc')
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getNewsByType($type, $limit = 6)
    {
        return Article::query()
            ->where('type', $type)
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->orderBy('sort', 'asc')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getNewsArticles($limit = 6, $excludeIds = [])
    {
        return Article::query()
            ->where('type', ArticleType::NEWS->value)
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->whereNotIn('id', $excludeIds)
            ->orderBy('sort', 'asc')
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getPressArticles($limit = 6, $excludeIds = [])
    {
        return Article::query()
            ->where('type', ArticleType::PRESS->value)
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->whereNotIn('id', $excludeIds)
            ->orderBy('sort', 'asc')
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getHandbookArticles($limit = 6, $excludeIds = [])
    {
        return Article::query()
            ->where('type', ArticleType::HANDBOOK->value)
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->whereNotIn('id', $excludeIds)
            ->orderBy('sort', 'asc')
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getArticleBySlug(string $slug): Article
    {
        return Article::query()
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->where('slug', $slug)
            ->firstOrFail();
    }

    public function incrementViewCount(string $slug): void
    {
        Article::query()
            ->where('slug', $slug)
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->increment('view');
    }

    public function getArticlesByType($type, $perPage = 12)
    {
        return Article::query()
            ->where('type', $type)
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->orderBy('sort', 'asc')
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);
    }

    public function getRelatedArticles(string $slug, int $limit = 3)
    {
        $currentArticle = $this->getArticleBySlug($slug);
        
        return Article::query()
            ->where('type', $currentArticle->type)
            ->where('status', ArticleStatus::PUBLISHED->value)
            ->where('slug', '!=', $slug)
            ->orderBy('sort', 'asc')
            ->orderBy('created_at', 'desc')
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }

    public function extractFirstHeading(string $content): string
    {
        $content = $content ?? '';
        try {
            libxml_use_internal_errors(true);
            $dom = new \DOMDocument();
            $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            $xpath = new \DOMXPath($dom);
            $firstNode = $xpath->query('//h1 | //h2 | //p')->item(0);
            if ($firstNode) {
                return trim($firstNode->textContent);
            }
            libxml_clear_errors();
        } catch (\Throwable $e) {
            return $e->getMessage();
        }

        $first = strip_tags(Str::before($content, '</p>'));
        if (trim($first) !== '') {
            return trim($first);
        }
        $plain = strip_tags($content);
        return trim(Str::before($plain, "\n"));
    }
}
