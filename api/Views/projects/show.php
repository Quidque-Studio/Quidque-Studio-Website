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

$techByTier = [];
foreach ($techStack as $tech) {
    $tierName = $tech['tier_name'] ?? 'Other';
    $tierOrder = $tech['tier_order'] ?? 999;
    if (!isset($techByTier[$tierOrder])) {
        $techByTier[$tierOrder] = [
            'name' => $tierName,
            'items' => []
        ];
    }
    $techByTier[$tierOrder]['items'][] = $tech;
}
ksort($techByTier);
?>

<article class="project-single">
    <nav class="project-breadcrumb">
        <a href="/projects">Projects</a>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
        <span><?= htmlspecialchars($project['title']) ?></span>
    </nav>

    <header class="project-header">
        <div class="project-title-row">
            <h1 class="project-title"><?= htmlspecialchars($project['title']) ?></h1>
            <span class="status-badge status-<?= $project['status'] ?>">
                <?= ucfirst(str_replace('_', ' ', $project['status'])) ?>
            </span>
        </div>
    </header>

    <section class="project-overview">
        <?php if ($project['description'] || !empty($gallery)): ?>
        <div class="project-overview-grid">
            <?php if ($project['description']): ?>
            <div class="project-description-block">
                <p class="project-description"><?= htmlspecialchars($project['description']) ?></p>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($gallery)): ?>
            <div class="project-gallery-block">
                <div class="gallery-main" id="gallery-main">
                    <?php $first = $gallery[0]; ?>
                    <?php if ($first['type'] === 'video'): ?>
                        <video src="<?= htmlspecialchars($first['path']) ?>" controls></video>
                    <?php else: ?>
                        <img src="<?= htmlspecialchars($first['path']) ?>" alt="" id="gallery-main-img">
                    <?php endif; ?>
                </div>
                <?php if (count($gallery) > 1): ?>
                <div class="gallery-thumbs">
                    <?php foreach ($gallery as $i => $media): ?>
                        <div class="gallery-thumb <?= $i === 0 ? 'active' : '' ?>" data-src="<?= htmlspecialchars($media['path']) ?>" data-type="<?= $media['type'] ?>">
                            <?php if ($media['type'] === 'video'): ?>
                                <video src="<?= htmlspecialchars($media['path']) ?>" muted></video>
                            <?php else: ?>
                                <img src="<?= htmlspecialchars($media['path']) ?>" alt="">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </section>

    <?php if (!empty($authors) || !empty($resources)): ?>
    <section class="project-meta-resources-section">
        <div class="meta-resources-grid">
            <?php if (!empty($authors)): ?>
            <div class="project-meta-card">
                <h3 class="project-meta-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    Team
                </h3>
                <div class="author-list">
                    <?php foreach ($authors as $author): ?>
                    <a href="/team/<?= $author['id'] ?>" class="author-item">
                        <div class="author-avatar">
                            <?php if ($author['avatar']): ?>
                                <img src="<?= htmlspecialchars($author['avatar']) ?>" alt="">
                            <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <?php endif; ?>
                        </div>
                        <div class="author-info">
                            <div class="author-name"><?= htmlspecialchars($author['name']) ?></div>
                            <?php if ($author['role_title']): ?>
                                <div class="author-role"><?= htmlspecialchars($author['role_title']) ?></div>
                            <?php endif; ?>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($resources)): ?>
            <div class="project-resources-card">
                <h3 class="project-meta-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                    Links & Downloads
                </h3>
                <div class="resource-list">
                    <?php foreach ($resources as $res): ?>
                        <?php if ($res['type'] === 'link'): ?>
                            <a href="<?= htmlspecialchars($res['url']) ?>" target="_blank" class="resource-link">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" x2="21" y1="14" y2="3"/></svg>
                                <?= htmlspecialchars($res['label']) ?>
                            </a>
                        <?php elseif ($res['type'] === 'steam'): ?>
                            <a href="https://store.steampowered.com/app/<?= htmlspecialchars($res['app_id']) ?>" target="_blank" class="resource-link resource-steam">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 0 0-9.96 11.04l5.48 2.28a2.85 2.85 0 0 1 1.6-.49c.17 0 .33.01.5.04l2.32-3.37v-.05a3.8 3.8 0 1 1 3.8 3.8h-.09l-3.32 2.37c0 .14.02.28.02.43a2.85 2.85 0 0 1-5.68.28L2.2 16.54A10 10 0 1 0 12 2z"/></svg>
                                Steam
                            </a>
                        <?php elseif ($res['type'] === 'itch'): ?>
                            <a href="<?= htmlspecialchars($res['url']) ?>" target="_blank" class="resource-link resource-itch">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M3.13 1.338C2.08 1.96 1.02 3.15 1.02 4.18v.92c0 1.5.53 2.37 1.63 2.88 1.13.52 2.04.3 2.73-.38.68-.67.96-1.55.96-2.5v-.92c0-.77-.25-1.47-.75-2.1-.5-.64-1.16-1.02-2-1.13-.57-.08-1.1.1-1.46.32zm6.37-.02c-.92.02-1.7.47-2.2 1.13-.5.64-.75 1.34-.75 2.1v.92c0 .95.28 1.83.97 2.5.68.68 1.6.9 2.72.38 1.1-.5 1.63-1.38 1.63-2.88v-.92c0-1.03-1.06-2.22-2.1-2.84-.17-.1-.35-.18-.54-.24a2.3 2.3 0 0 0-.73-.14v-.01zm5.86.02c-.57.08-1.1.3-1.53.65-.5.4-.9.95-1.13 1.63v.92c0 1.5.53 2.37 1.63 2.88 1.12.52 2.04.3 2.72-.38.7-.67.97-1.55.97-2.5v-.92c0-.77-.24-1.47-.74-2.1-.5-.64-1.16-1.02-2-1.13l.08-.05zm-8.84 9.4c-.35 0-.68.07-1 .2l-3.77 1.56a2.33 2.33 0 0 0 .6 4.38c.57.17 1.18.1 1.7-.18l2.04-1.13a.3.3 0 0 1 .3 0l1.74.95c.68.37 1.5.4 2.2.06.7-.34 1.2-.96 1.36-1.7a2.33 2.33 0 0 0-1.12-2.5l-2.1-1.2a2.35 2.35 0 0 0-1.95-.44z"/></svg>
                                Itch.io
                            </a>
                        <?php elseif ($res['type'] === 'youtube'): ?>
                            <a href="https://youtube.com/watch?v=<?= htmlspecialchars($res['video_id']) ?>" target="_blank" class="resource-link resource-youtube">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M23.5 6.2c-.3-1-1-1.8-2-2.1C19.6 3.5 12 3.5 12 3.5s-7.6 0-9.5.5c-1 .3-1.7 1.1-2 2.1C0 8.2 0 12 0 12s0 3.8.5 5.8c.3 1 1 1.8 2 2.1 1.9.5 9.5.5 9.5.5s7.6 0 9.5-.5c1-.3 1.7-1.1 2-2.1.5-2 .5-5.8.5-5.8s0-3.8-.5-5.8zM9.6 15.6V8.4l6.4 3.6-6.4 3.6z"/></svg>
                                YouTube
                            </a>
                        <?php elseif ($res['type'] === 'download'): ?>
                            <a href="<?= htmlspecialchars($res['file_path']) ?>" download class="resource-link resource-download">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                                <?= htmlspecialchars($res['label']) ?>
                                <?php if (!empty($res['file_size'])): ?>
                                    <span class="file-size"><?= formatFileSize($res['file_size']) ?></span>
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
        
        <?php foreach ($resources as $res): ?>
            <?php if ($res['type'] === 'embed'): ?>
            <div class="project-embed">
                <?php if ($res['label']): ?><h3><?= htmlspecialchars($res['label']) ?></h3><?php endif; ?>
                <?= ContentRenderer::sanitizeEmbed($res['html']) ?>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>

    <?php if (!empty($techStack) || !empty($devlogs)): ?>
    <section class="project-tech-devlogs">
        <div class="tech-devlogs-grid">
            <?php if (!empty($techStack)): ?>
            <div class="tech-stack-panel">
                <h2 class="project-section-title">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m18 16 4-4-4-4"/><path d="m6 8-4 4 4 4"/><path d="m14.5 4-5 16"/></svg>
                    Tech Stack
                </h2>
                <div class="tech-stack-tower">
                    <?php 
                    $tiers = array_values($techByTier);
                    $totalTiers = count($tiers);
                    foreach (array_reverse($tiers) as $index => $tier): 
                        $tierLevel = $totalTiers - $index;
                    ?>
                    <div class="tech-stack-block" data-tier="<?= $tierLevel ?>" style="--tier-index: <?= $index ?>; --total-tiers: <?= $totalTiers ?>;">
                        <div class="tech-stack-block-inner">
                            <div class="tech-stack-tier-name"><?= htmlspecialchars($tier['name']) ?></div>
                            <div class="tech-stack-items">
                                <?php foreach ($tier['items'] as $tech): ?>
                                    <span class="tech-tag"><?= htmlspecialchars($tech['name']) ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($devlogs)): ?>
            <div class="devlogs-panel">
                <div class="devlogs-header">
                    <h2 class="project-section-title">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/><polyline points="14 2 14 8 20 8"/><line x1="16" x2="8" y1="13" y2="13"/><line x1="16" x2="8" y1="17" y2="17"/><line x1="10" x2="8" y1="9" y2="9"/></svg>
                        Development Log
                    </h2>
                    <span class="badge"><?= count($devlogs) ?> entries</span>
                </div>
                <div class="devlog-timeline">
                    <?php foreach ($devlogs as $devlog): ?>
                    <div class="devlog-entry">
                        <a href="/projects/<?= htmlspecialchars($project['slug']) ?>/devlogs/<?= htmlspecialchars($devlog['slug']) ?>" class="devlog-card">
                            <div class="devlog-date"><?= Date::short($devlog['created_at']) ?></div>
                            <div class="devlog-card-title"><?= htmlspecialchars($devlog['title']) ?></div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
    <div id="gallery-modal" class="gallery-modal">
        <button class="gallery-modal-close" aria-label="Close">&times;</button>
        <div class="gallery-modal-content">
            <img id="gallery-modal-img" src="" alt="">
        </div>
    </div>
</article>

<script>
(function() {
    const thumbs = document.querySelectorAll('.gallery-thumb');
    const main = document.getElementById('gallery-main');

    const modal = document.getElementById('gallery-modal');
    const modalImg = document.getElementById('gallery-modal-img');
    const closeBtn = document.querySelector('.gallery-modal-close');

    let currentIndex = 0;
    let autoSwapInterval = null;
    const AUTO_SWAP_DELAY = 5000;

    function showSlide(index) {
        if (thumbs.length === 0) return;
        currentIndex = index;
        const thumb = thumbs[index];
        const src = thumb.dataset.src;
        const type = thumb.dataset.type;
        
        thumbs.forEach(t => t.classList.remove('active'));
        thumb.classList.add('active');
        
        if (type === 'video') {
            main.innerHTML = '<video src="' + src + '" controls></video>';
        } else {
            main.innerHTML = '<img src="' + src + '" alt="">';
        }
    }

    function nextSlide() {
        const next = (currentIndex + 1) % thumbs.length;
        showSlide(next);
    }

    function startAutoSwap() {
        if (thumbs.length > 1) {
            autoSwapInterval = setInterval(nextSlide, AUTO_SWAP_DELAY);
        }
    }

    function resetAutoSwap() {
        clearInterval(autoSwapInterval);
        startAutoSwap();
    }

    thumbs.forEach((thumb, index) => {
        thumb.addEventListener('click', function() {
            showSlide(index);
            resetAutoSwap();
        });
    });

    main.addEventListener('click', function(e) {
        if (e.target.tagName === 'IMG') {
            modalImg.src = e.target.src;
            modal.classList.add('active');
            clearInterval(autoSwapInterval);
        }
    });

    function closeModal() {
        modal.classList.remove('active');
        modalImg.src = '';
        startAutoSwap();
    }

    closeBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });

    // --- Init ---
    if (thumbs.length > 0) {
        showSlide(0);
        startAutoSwap();
    }
})();
</script>