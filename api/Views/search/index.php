<?php use Api\Core\ContentRenderer; ?>

<div class="search-page">
    <form action="/search" method="GET" class="search-form">
        <div class="search-main">
            <input type="text" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Search projects, posts, devlogs..." autofocus>
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
        
        <div class="search-options">
            <div class="search-option">
                <label>Search in:</label>
                <select name="in">
                    <option value="all" <?= $searchIn === 'all' ? 'selected' : '' ?>>All</option>
                    <option value="projects" <?= $searchIn === 'projects' ? 'selected' : '' ?>>Projects</option>
                    <option value="posts" <?= $searchIn === 'posts' ? 'selected' : '' ?>>Blog Posts</option>
                    <option value="devlogs" <?= $searchIn === 'devlogs' ? 'selected' : '' ?>>Devlogs</option>
                </select>
            </div>
            <div class="search-option">
                <label>
                    <input type="checkbox" name="content" <?= $searchContent ? 'checked' : '' ?>>
                    Search in content
                </label>
            </div>
        </div>
    </form>

    <?php if ($query): ?>
        <p class="search-summary">
            <?= $totalResults ?> result<?= $totalResults !== 1 ? 's' : '' ?> for "<?= htmlspecialchars($query) ?>"
            <?php if ($searchIn !== 'all'): ?>
                in <?= htmlspecialchars($searchIn) ?>
            <?php endif; ?>
            <?php if ($searchContent): ?>
                (including content)
            <?php endif; ?>
        </p>

        <?php if (!empty($results['projects'])): ?>
            <section class="search-section">
                <h2>Projects</h2>
                <ul class="search-results">
                    <?php foreach ($results['projects'] as $item): ?>
                        <li>
                            <a href="/projects/<?= htmlspecialchars($item['slug']) ?>">
                                <strong><?= htmlspecialchars($item['title']) ?></strong>
                                <?php if ($item['description']): ?>
                                    <p><?= htmlspecialchars(substr($item['description'], 0, 150)) ?>...</p>
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
                                <?php if ($searchContent && !empty($item['content'])): ?>
                                    <p><?= htmlspecialchars(substr(ContentRenderer::excerpt($item['content'], 150), 0, 150)) ?>...</p>
                                <?php endif; ?>
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
                                <?php if ($searchContent && !empty($item['content'])): ?>
                                    <p><?= htmlspecialchars(substr(ContentRenderer::excerpt($item['content'], 150), 0, 150)) ?>...</p>
                                <?php endif; ?>
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