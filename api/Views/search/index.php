<div class="search-page">
    <form action="/search" method="GET" class="search-form">
        <?= \Api\Core\View::csrfField() ?>
        <input type="text" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Search projects, posts, devlogs..." autofocus>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <?php if ($query): ?>
        <p class="search-summary"><?= $totalResults ?> result<?= $totalResults !== 1 ? 's' : '' ?> for "<?= htmlspecialchars($query) ?>"</p>

        <?php if (!empty($results['projects'])): ?>
            <section class="search-section">
                <h2>Projects</h2>
                <ul class="search-results">
                    <?php foreach ($results['projects'] as $item): ?>
                        <li>
                            <a href="/projects/<?= htmlspecialchars($item['slug']) ?>">
                                <strong><?= htmlspecialchars($item['title']) ?></strong>
                                <?php if ($item['description']): ?>
                                    <p><?= htmlspecialchars(substr($item['description'], 0, 100)) ?>...</p>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php if (!empty($results['posts'])): ?>
            <section class="search-section">
                <h2>Blog Posts</h2>
                <ul class="search-results">
                    <?php foreach ($results['posts'] as $item): ?>
                        <li>
                            <a href="/blog/<?= htmlspecialchars($item['slug']) ?>">
                                <strong><?= htmlspecialchars($item['title']) ?></strong>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php if (!empty($results['devlogs'])): ?>
            <section class="search-section">
                <h2>Devlogs</h2>
                <ul class="search-results">
                    <?php foreach ($results['devlogs'] as $item): ?>
                        <li>
                            <a href="/projects/<?= htmlspecialchars($item['project_slug']) ?>/devlogs/<?= htmlspecialchars($item['slug']) ?>">
                                <strong><?= htmlspecialchars($item['title']) ?></strong>
                                <span class="search-meta"><?= htmlspecialchars($item['project_title']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </section>
        <?php endif; ?>

        <?php if ($totalResults === 0): ?>
            <p>No results found.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>