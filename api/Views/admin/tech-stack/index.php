<?php if (isset($_GET['error']) && $_GET['error'] === 'tier_has_items'): ?>
    <div class="alert alert-error">Cannot delete tier that has items assigned to it.</div>
<?php endif; ?>

<div class="tech-stack-manager">
    <div class="tech-tiers">
        <div class="tech-tiers-header">
            <h2 class="tech-tiers-title">Tiers</h2>
        </div>
        <div class="tech-tiers-body">
            <form method="POST" action="/admin/tech-stack/tiers" class="tech-tier-form">
                <?= \Api\Core\View::csrfField() ?>
                <input type="text" name="name" placeholder="New tier name" required>
                <button type="submit" class="btn btn-primary">Add</button>
            </form>

            <?php if (empty($tiers)): ?>
                <p style="color: var(--text-muted); text-align: center; padding: 20px;">No tiers yet</p>
            <?php else: ?>
                <div class="tech-tier-list">
                    <?php foreach ($tiers as $tier): ?>
                        <div class="tech-tier-item">
                            <form method="POST" action="/admin/tech-stack/tiers/<?= $tier['id'] ?>" style="display: contents;">
                                <?= \Api\Core\View::csrfField() ?>
                                <input type="text" name="name" value="<?= htmlspecialchars($tier['name']) ?>">
                                <input type="number" name="sort_order" value="<?= $tier['sort_order'] ?>" title="Sort order">
                                <button type="submit" class="save" title="Save">✓</button>
                            </form>
                            <form method="POST" action="/admin/tech-stack/tiers/<?= $tier['id'] ?>/delete" style="display: contents;">
                                <?= \Api\Core\View::csrfField() ?>
                                <button type="submit" class="delete" title="Delete" onclick="return confirm('Delete this tier?')">×</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="tech-items">
        <div class="tech-items-header">
            <h2 class="tech-items-title">Technologies</h2>
        </div>
        <div class="tech-items-body">
            <?php if (empty($tiers)): ?>
                <p style="color: var(--text-muted); text-align: center; padding: 20px;">Create a tier first to add technologies</p>
            <?php else: ?>
                <form method="POST" action="/admin/tech-stack" class="tech-item-form">
                    <?= \Api\Core\View::csrfField() ?>
                    <input type="text" name="name" placeholder="Technology name" required>
                    <select name="tier_id" required>
                        <?php foreach ($tiers as $tier): ?>
                            <option value="<?= $tier['id'] ?>"><?= htmlspecialchars($tier['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Add</button>
                </form>

                <?php if (empty($tech)): ?>
                    <p style="color: var(--text-muted); text-align: center; padding: 20px;">No technologies yet</p>
                <?php else: ?>
                    <div class="tech-item-list">
                        <?php foreach ($tech as $t): ?>
                            <div class="tech-item-row">
                                <form method="POST" action="/admin/tech-stack/<?= $t['id'] ?>" style="display: contents;">
                                    <?= \Api\Core\View::csrfField() ?>
                                    <input type="text" name="name" value="<?= htmlspecialchars($t['name']) ?>">
                                    <select name="tier_id">
                                        <?php foreach ($tiers as $tier): ?>
                                            <option value="<?= $tier['id'] ?>" <?= $tier['id'] == $t['tier_id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($tier['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" class="save" title="Save">✓</button>
                                </form>
                                <form method="POST" action="/admin/tech-stack/<?= $t['id'] ?>/delete" style="display: contents;">
                                    <?= \Api\Core\View::csrfField() ?>
                                    <button type="submit" class="delete" title="Delete" onclick="return confirm('Delete this technology?')">×</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>