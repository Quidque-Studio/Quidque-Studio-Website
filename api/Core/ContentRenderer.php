<?php

namespace Api\Core;

class ContentRenderer
{
    public static function render(?string $json): string
    {
        if (!$json) return '';
        
        $blocks = json_decode($json, true);
        if (!is_array($blocks)) return '';
        
        $html = '';
        foreach ($blocks as $block) {
            $html .= self::renderBlock($block);
        }
        
        return $html;
    }
    
    private static function renderBlock(array $block): string
    {
        $type = $block['type'] ?? '';
        $value = $block['value'] ?? '';
        
        return match($type) {
            'heading' => '<h2>' . htmlspecialchars($value) . '</h2>',
            'text' => '<p>' . nl2br(htmlspecialchars($value)) . '</p>',
            'image' => self::renderImage($block),
            'code' => '<pre><code>' . htmlspecialchars($value) . '</code></pre>',
            'quote' => '<blockquote>' . nl2br(htmlspecialchars($value)) . '</blockquote>',
            'divider' => '<hr>',
            'list' => self::renderList($block),
            'callout' => '<div class="callout">' . nl2br(htmlspecialchars($value)) . '</div>',
            'video' => self::renderVideo($block),
            default => '',
        };
    }
    
    private static function renderImage(array $block): string
    {
        $src = htmlspecialchars($block['value'] ?? '');
        $caption = htmlspecialchars($block['caption'] ?? '');
        
        $html = '<figure><img src="' . $src . '" alt="" loading="lazy">';
        if ($caption) {
            $html .= '<figcaption>' . $caption . '</figcaption>';
        }
        $html .= '</figure>';
        
        return $html;
    }
    
    private static function renderList(array $block): string
    {
        $items = $block['items'] ?? [];
        $ordered = $block['ordered'] ?? false;
        
        if (empty($items)) return '';
        
        $tag = $ordered ? 'ol' : 'ul';
        $html = "<{$tag}>";
        foreach ($items as $item) {
            $html .= '<li>' . htmlspecialchars($item) . '</li>';
        }
        $html .= "</{$tag}>";
        
        return $html;
    }
    
    private static function renderVideo(array $block): string
    {
        $value = $block['value'] ?? '';
        
        // YouTube
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $value, $matches)) {
            return '<div class="video-embed"><iframe src="https://www.youtube.com/embed/' . htmlspecialchars($matches[1]) . '" frameborder="0" allowfullscreen></iframe></div>';
        }
        
        // Direct video file
        if (preg_match('/\.(mp4|webm)$/i', $value)) {
            return '<video src="' . htmlspecialchars($value) . '" controls></video>';
        }
        
        return '';
    }
    
    public static function excerpt(?string $json, int $length = 150): string
    {
        if (!$json) return '';
        
        $blocks = json_decode($json, true);
        if (!is_array($blocks)) return '';
        
        $text = '';
        foreach ($blocks as $block) {
            if (in_array($block['type'], ['text', 'heading', 'quote'])) {
                $text .= ($block['value'] ?? '') . ' ';
            }
        }
        
        $text = trim($text);
        if (strlen($text) <= $length) return $text;
        
        return substr($text, 0, $length) . '...';
    }
    
    public static function sanitizeEmbed(string $html): string
    {
        $allowed = '<iframe><blockquote><script>';
        $html = strip_tags($html, $allowed);
        $html = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        return $html;
    }
}