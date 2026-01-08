<?php use Api\Core\ContentRenderer; ?>

<div class="search-container">
    <div class="search-box">
        <form action="/search" method="GET">
            <div class="search-input-wrapper">
                <div class="search-field">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input type="text" name="q" value="<?= htmlspecialchars($query) ?>" placeholder="Search projects, posts, devlogs..." autofocus>
                </div>
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            
            <div class="search-options">
                <div class="search-option">
                    <label>Search in:</label>
                    <select name="in">
                        <option value="all" <?= $searchIn === 'all' ? 'selected' : '' ?>>Everything</option>
                        <option value="projects" <?= $searchIn === 'projects' ? 'selected' : '' ?>>Projects</option>
                        <option value="posts" <?= $searchIn === 'posts' ? 'selected' : '' ?>>News</option>
                        <option value="devlogs" <?= $searchIn === 'devlogs' ? 'selected' : '' ?>>Devlogs</option>
                    </select>
                </div>
                <div class="search-option">
                    <label>
                        <input type="checkbox" name="content" <?= $searchContent ? 'checked' : '' ?>>
                        Include content
                    </label>
                </div>
            </div>
        </form>
    </div>

    <?php if ($query): ?>
        <p class="search-summary">
            Found <strong><?= $totalResults ?></strong> result<?= $totalResults !== 1 ? 's' : '' ?> 
            for "<strong><?= htmlspecialchars($query) ?></strong>"
        </p>

        <?php if (!empty($results['projects'])): ?>
        <section class="search-results-section">
            <h2 class="search-results-title">Projects</h2>
            <div class="search-results-list">
                <?php foreach ($results['projects'] as $item): ?>
                <a href="/projects/<?= htmlspecialchars($item['slug']) ?>" class="search-result-item">
                    <div class="search-result-title"><?= htmlspecialchars($item['title']) ?></div>
                    <?php if ($item['description']): ?>
                        <div class="search-result-excerpt"><?= htmlspecialchars(mb_strimwidth($item['description'], 0, 150, '...')) ?></div>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if (!empty($results['posts'])): ?>
        <section class="search-results-section">
            <h2 class="search-results-title">News</h2>
            <div class="search-results-list">
                <?php foreach ($results['posts'] as $item): ?>
                <a href="/blog/<?= htmlspecialchars($item['slug']) ?>" class="search-result-item">
                    <div class="search-result-title"><?= htmlspecialchars($item['title']) ?></div>
                    <?php if ($searchContent && !empty($item['content'])): ?>
                        <div class="search-result-excerpt"><?= htmlspecialchars(mb_strimwidth(ContentRenderer::excerpt($item['content'], 150), 0, 150, '...')) ?></div>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if (!empty($results['devlogs'])): ?>
        <section class="search-results-section">
            <h2 class="search-results-title">Devlogs</h2>
            <div class="search-results-list">
                <?php foreach ($results['devlogs'] as $item): ?>
                <a href="/projects/<?= htmlspecialchars($item['project_slug']) ?>/devlogs/<?= htmlspecialchars($item['slug']) ?>" class="search-result-item">
                    <div class="search-result-title"><?= htmlspecialchars($item['title']) ?></div>
                    <div class="search-result-meta"><?= htmlspecialchars($item['project_title']) ?></div>
                    <?php if ($searchContent && !empty($item['content'])): ?>
                        <div class="search-result-excerpt"><?= htmlspecialchars(mb_strimwidth(ContentRenderer::excerpt($item['content'], 150), 0, 150, '...')) ?></div>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <?php if ($totalResults === 0): ?>
        <div class="search-empty">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <p>No results found. Try different keywords.</p>
        </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="search-empty">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
            <p>Enter a search term to find projects, news, and devlogs.</p>
        </div>
    <?php endif; ?>
</div>