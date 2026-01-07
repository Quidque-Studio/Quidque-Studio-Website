<?php

namespace Api\Core;

class Paginator
{
    public int $page;
    public int $perPage;
    public int $total;
    public int $totalPages;
    public int $offset;

    public function __construct(int $total, int $page = 1, int $perPage = 12)
    {
        $this->total = $total;
        $this->perPage = $perPage;
        $this->totalPages = (int) ceil($total / $perPage);
        $this->page = max(1, min($page, $this->totalPages ?: 1));
        $this->offset = ($this->page - 1) * $perPage;
    }

    public function hasPages(): bool
    {
        return $this->totalPages > 1;
    }

    public function hasPrev(): bool
    {
        return $this->page > 1;
    }

    public function hasNext(): bool
    {
        return $this->page < $this->totalPages;
    }

    public function prevPage(): int
    {
        return $this->page - 1;
    }

    public function nextPage(): int
    {
        return $this->page + 1;
    }

    public function buildUrl(int $page, array $params = []): string
    {
        $params['page'] = $page;
        return '?' . http_build_query($params);
    }
}