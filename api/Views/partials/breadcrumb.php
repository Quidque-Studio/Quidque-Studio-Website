<nav class="breadcrumb">
    <?php foreach ($crumbs as $i => $crumb): ?>
        <?php if ($i > 0): ?>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        <?php endif; ?>
        <?php if (isset($crumb['url'])): ?>
            <a href="<?= htmlspecialchars($crumb['url']) ?>"><?= htmlspecialchars($crumb['label']) ?></a>
        <?php else: ?>
            <span><?= htmlspecialchars($crumb['label']) ?></span>
        <?php endif; ?>
    <?php endforeach; ?>
</nav>