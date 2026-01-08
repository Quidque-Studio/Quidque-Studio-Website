<?php if ($paginator->hasPages()): ?>
<nav class="pagination">
    <?php if ($paginator->hasPrev()): ?>
        <a href="<?= $paginator->buildUrl($paginator->prevPage(), $paginationParams ?? []) ?>" class="btn btn-sm">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Previous
        </a>
    <?php endif; ?>

    <span class="pagination-info">Page <?= $paginator->page ?> of <?= $paginator->totalPages ?></span>

    <?php if ($paginator->hasNext()): ?>
        <a href="<?= $paginator->buildUrl($paginator->nextPage(), $paginationParams ?? []) ?>" class="btn btn-sm">
            Next
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        </a>
    <?php endif; ?>
</nav>
<?php endif; ?>