<?php

namespace App\Utils;

class TableOfContentsHelper
{
    public static function generateTableOfContents(string $content): array
    {
        $toc = [];
        $dom = new \DOMDocument();
        
        // Suppress errors for malformed HTML
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="utf-8" ?>' . $content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        libxml_clear_errors();
        
        $xpath = new \DOMXPath($dom);
        $headings = $xpath->query('//h1 | //h2');
        
        foreach ($headings as $index => $heading) {
            $text = trim($heading->textContent);
            $level = (int) substr($heading->nodeName, 1); // h1 = 1, h2 = 2
            $id = 'heading-' . ($index + 1);
            
            // Add ID to the heading element
            if ($heading instanceof \DOMElement) {
                $heading->setAttribute('id', $id);
            }
            
            $toc[] = [
                'id' => $id,
                'text' => $text,
                'level' => $level,
                'tag' => $heading->nodeName
            ];
        }
        
        return [
            'toc' => $toc,
            'content' => $dom->saveHTML()
        ];
    }
}
