<?php if ($paginator->hasPages()): ?>
    <nav class="pagination">
        <?php if ($paginator->hasPrev()): ?>
            <a href="<?= $paginator->buildUrl($paginator->prevPage(), $paginationParams ?? []) ?>" class="pagination-prev">← Prev</a>
        <?php endif; ?>

        <span class="pagination-info">Page <?= $paginator->page ?> of <?= $paginator->totalPages ?></span>

        <?php if ($paginator->hasNext()): ?>
            <a href="<?= $paginator->buildUrl($paginator->nextPage(), $paginationParams ?? []) ?>" class="pagination-next">Next →</a>
        <?php endif; ?>
    </nav>
<?php endif; ?>