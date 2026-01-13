<?php

namespace Api\Core;

class Seo
{
    private string $title;
    private string $siteName = 'Quidque Studio';
    private string $defaultDescription = 'Building tools, software and digital experiments from the ground up. No shortcuts, just focused development.';
    private string $description;
    private string $canonicalUrl;
    private bool $index = true;
    private bool $follow = true;
    private ?string $ogImage = null;
    private string $ogType = 'website';
    private ?string $ogImageAlt = null;

    public function __construct(string $title, array $options = [])
    {
        $this->title = $title;
        $this->description = $options['description'] ?? $this->defaultDescription;
        $this->canonicalUrl = $options['canonical'] ?? $this->buildCanonicalUrl();
        
        if (isset($options['index'])) {
            $this->index = $options['index'];
        }
        if (isset($options['follow'])) {
            $this->follow = $options['follow'];
        }
        if (isset($options['image'])) {
            $this->ogImage = $options['image'];
            $this->ogImageAlt = $options['imageAlt'] ?? $title;
        }
        if (isset($options['type'])) {
            $this->ogType = $options['type'];
        }
    }

    private function buildCanonicalUrl(): string
    {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $path = rtrim($path, '/') ?: '/';
        return "{$scheme}://{$host}{$path}";
    }

    private function getFullImageUrl(?string $image): ?string
    {
        if (!$image) {
            return null;
        }
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return "{$scheme}://{$host}{$image}";
    }

    private function truncate(string $text, int $length = 160): string
    {
        $text = strip_tags($text);
        $text = preg_replace('/\s+/', ' ', trim($text));
        if (strlen($text) <= $length) {
            return $text;
        }
        return substr($text, 0, $length - 3) . '...';
    }

    public function render(): string
    {
        $html = [];
        
        $fullTitle = $this->title === $this->siteName 
            ? $this->siteName 
            : "{$this->title} | {$this->siteName}";
        $html[] = '<title>' . htmlspecialchars($fullTitle) . '</title>';
        
        $desc = $this->truncate($this->description);
        $html[] = '<meta name="description" content="' . htmlspecialchars($desc) . '">';
        
        $robots = [];
        $robots[] = $this->index ? 'index' : 'noindex';
        $robots[] = $this->follow ? 'follow' : 'nofollow';
        $html[] = '<meta name="robots" content="' . implode(', ', $robots) . '">';
        
        $html[] = '<link rel="canonical" href="' . htmlspecialchars($this->canonicalUrl) . '">';
        
        $html[] = '<meta property="og:title" content="' . htmlspecialchars($this->title) . '">';
        $html[] = '<meta property="og:description" content="' . htmlspecialchars($desc) . '">';
        $html[] = '<meta property="og:url" content="' . htmlspecialchars($this->canonicalUrl) . '">';
        $html[] = '<meta property="og:site_name" content="' . htmlspecialchars($this->siteName) . '">';
        $html[] = '<meta property="og:type" content="' . htmlspecialchars($this->ogType) . '">';
        
        $imageUrl = $this->getFullImageUrl($this->ogImage);
        if ($imageUrl) {
            $html[] = '<meta property="og:image" content="' . htmlspecialchars($imageUrl) . '">';
            if ($this->ogImageAlt) {
                $html[] = '<meta property="og:image:alt" content="' . htmlspecialchars($this->ogImageAlt) . '">';
            }
        }
        
        $html[] = '<meta name="twitter:card" content="' . ($imageUrl ? 'summary_large_image' : 'summary') . '">';
        $html[] = '<meta name="twitter:title" content="' . htmlspecialchars($this->title) . '">';
        $html[] = '<meta name="twitter:description" content="' . htmlspecialchars($desc) . '">';
        if ($imageUrl) {
            $html[] = '<meta name="twitter:image" content="' . htmlspecialchars($imageUrl) . '">';
        }
        
        return implode("\n    ", $html);
    }

    public static function make(string $title, array $options = []): self
    {
        return new self($title, $options);
    }

    public static function noIndex(string $title, array $options = []): self
    {
        $options['index'] = false;
        $options['follow'] = false;
        return new self($title, $options);
    }
}