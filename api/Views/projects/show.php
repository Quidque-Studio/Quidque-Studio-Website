<?php
use Api\Core\ContentRenderer;
use Api\Core\Date;

function formatFileSize(?int $bytes): string {
    if (!$bytes) return '';
    if ($bytes < 1024) return $bytes . ' B';
    if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
    if ($bytes < 1073741824) return round($bytes / 1048576, 1) . ' MB';
    return round($bytes / 1073741824, 1) . ' GB';
}
?>

<article class="project-single">
    <header class="project-header">
        <h1><?= htmlspecialchars($project['title']) ?></h1>
        <span class="status-badge status-<?= $project['status'] ?>"><?= ucfirst(str_replace('_', ' ', $project['status'])) ?></span>
        <?php if ($project['description']): ?>
            <p class="project-description"><?= htmlspecialchars($project['description']) ?></p>
        <?php endif; ?>
    </header>

    <?php if (!empty($gallery)): ?>
        <section class="project-gallery">
            <?php foreach ($gallery as $media): ?>
                <?php if ($media['type'] === 'video'): ?>
                    <video src="<?= htmlspecialchars($media['path']) ?>" controls></video>
                <?php else: ?>
                    <img src="<?= htmlspecialchars($media['path']) ?>" alt="" loading="lazy">
                <?php endif; ?>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>

    <aside class="project-meta">
        <?php if (!empty($techStack)): ?>
            <div class="meta-section">
                <h3>Tech Stack</h3>
                <div class="tech-tags">
                    <?php foreach ($techStack as $tech): ?>
                        <span class="tech-tag"><?= htmlspecialchars($tech['name']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($authors)): ?>
            <div class="meta-section">
                <h3>Team</h3>
                <ul class="author-list">
                    <?php foreach ($authors as $author): ?>
                        <li>
                            <a href="/team/<?= $author['id'] ?>">
                                <?php if ($author['avatar']): ?>
                                    <img src="<?= htmlspecialchars($author['avatar']) ?>" alt="" class="author-avatar">
                                <?php endif; ?>
                                <span><?= htmlspecialchars($author['name']) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($resources)): ?>
            <div class="meta-section">
                <h3>Links & Downloads</h3>
                <ul class="resource-list">
                    <?php foreach ($resources as $res): ?>
                        <li>
                            <?php if ($res['type'] === 'link'): ?>
                                <a href="<?= htmlspecialchars($res['url']) ?>" target="_blank"><?= htmlspecialchars($res['label']) ?></a>
                            <?php elseif ($res['type'] === 'steam'): ?>
                                <a href="https://store.steampowered.com/app/<?= htmlspecialchars($res['app_id']) ?>" target="_blank">Steam</a>
                            <?php elseif ($res['type'] === 'itch'): ?>
                                <a href="<?= htmlspecialchars($res['url']) ?>" target="_blank">Itch.io</a>
                            <?php elseif ($res['type'] === 'youtube'): ?>
                                <a href="https://youtube.com/watch?v=<?= htmlspecialchars($res['video_id']) ?>" target="_blank">YouTube</a>
                            <?php elseif ($res['type'] === 'download'): ?>
                                <a href="<?= htmlspecialchars($res['file_path']) ?>" download>
                                    <?= htmlspecialchars($res['label']) ?>
                                    <?php if (!empty($res['file_size'])): ?>
                                        <span class="file-size">(<?= formatFileSize($res['file_size']) ?>)</span>
                                    <?php endif; ?>
                                </a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </aside>

    <?php foreach ($resources as $res): ?>
        <?php if ($res['type'] === 'embed'): ?>
            <div class="project-embed">
                <?php if ($res['label']): ?><h3><?= htmlspecialchars($res['label']) ?></h3><?php endif; ?>
                <?= ContentRenderer::sanitizeEmbed($res['html']) ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if (!empty($devlogs)): ?>
        <section class="project-devlogs">
            <h2>Devlogs</h2>
            <ul class="devlog-stack">
                <?php foreach ($devlogs as $devlog): ?>
                    <li>
                        <a href="/projects/<?= htmlspecialchars($project['slug']) ?>/devlogs/<?= htmlspecialchars($devlog['slug']) ?>">
                            <span class="devlog-date"><?= Date::short($devlog['created_at']) ?></span>
                            <span class="devlog-title"><?= htmlspecialchars($devlog['title']) ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>
</article>