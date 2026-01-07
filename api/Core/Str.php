<?php

namespace Api\Core;

class Str
{
    public static function slug(string $text): string
    {
        $slug = strtolower(trim($text));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
    
    public static function excerpt(string $text, int $length = 150): string
    {
        $text = trim($text);
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length) . '...';
    }
    
    public static function parseTags(?string $input): ?string
    {
        if (empty($input)) {
            return null;
        }
        $tags = array_map('trim', explode(',', $input));
        $tags = array_filter($tags);
        return json_encode(array_values($tags));
    }
    
    public static function formatTags(?string $json): array
    {
        if (!$json) {
            return [];
        }
        return json_decode($json, true) ?? [];
    }
}