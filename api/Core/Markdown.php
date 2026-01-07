<?php

namespace Api\Core;

class Markdown
{
    public static function toHtml(string $text): string
    {
        $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        
        // Headers: # Heading
        $text = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $text);
        $text = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $text);
        $text = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $text);
        
        // Bold: **text**
        $text = preg_replace('/\*\*(.+?)\*\*/s', '<strong>$1</strong>', $text);
        
        // Italic: *text*
        $text = preg_replace('/\*(.+?)\*/s', '<em>$1</em>', $text);
        
        // Links: [text](url)
        $text = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '<a href="$2">$1</a>', $text);
        
        // Horizontal rule: ---
        $text = preg_replace('/^---$/m', '<hr>', $text);
        
        // Line breaks: double newline = paragraph, single = <br>
        $paragraphs = preg_split('/\n\n+/', $text);
        $paragraphs = array_map(function($p) {
            $p = trim($p);
            if (empty($p)) return '';
            if (preg_match('/^<(h[1-3]|hr)/', $p)) return $p;
            $p = nl2br($p);
            return "<p>{$p}</p>";
        }, $paragraphs);
        
        return implode("\n", array_filter($paragraphs));
    }

    public static function toPlainText(string $text): string
    {
        // Strip markdown syntax for plain text version
        $text = preg_replace('/^#{1,3} /m', '', $text);
        $text = preg_replace('/\*\*(.+?)\*\*/s', '$1', $text);
        $text = preg_replace('/\*(.+?)\*/s', '$1', $text);
        $text = preg_replace('/\[([^\]]+)\]\(([^)]+)\)/', '$1 ($2)', $text);
        $text = preg_replace('/^---$/m', '---', $text);
        
        return $text;
    }
}