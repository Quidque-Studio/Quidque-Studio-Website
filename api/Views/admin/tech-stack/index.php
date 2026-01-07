<?php if (isset($_GET['error']) && $_GET['error'] === 'tier_has_items'): ?>
    <div class="alert alert-error">Cannot delete tier that has items.</div>
<?php endif; ?>

<?php if (!empty($flash)): ?>
    <div class="alert alert-<?= $flash['type'] ?>"><?= htmlspecialchars($flash['message']) ?></div>
<?php endif; ?>

<div class="tech-stack-grid">
    <div class="form-section">
        <h2>Tiers</h2>
        <form method="POST" action="/admin/tech-stack/tiers" class="inline-form">
            <input type="text" name="name" placeholder="Tier name (e.g. Framework)" required>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>

        <ul class="tier-list">
            <?php foreach ($tiers as $tier): ?>
                <li>
                    <form method="POST" action="/admin/tech-stack/tiers/<?= $tier['id'] ?>" class="inline-edit-form">
                        <input type="text" name="name" value="<?= htmlspecialchars($tier['name']) ?>" class="inline-input">
                        <input type="number" name="sort_order" value="<?= $tier['sort_order'] ?>" class="inline-input-small">
                        <button type="submit" class="inline-save">Save</button>
                    </form>
                    <form method="POST" action="/admin/tech-stack/tiers/<?= $tier['id'] ?>/delete" style="display:inline">
                        <button type="submit" onclick="return confirm('Delete?')">×</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="form-section">
        <h2>Technologies</h2>
        <form method="POST" action="/admin/tech-stack" class="inline-form">
            <input type="text" name="name" placeholder="Technology name" required>
            <select name="tier_id" required>
                <?php foreach ($tiers as $tier): ?>
                    <option value="<?= $tier['id'] ?>"><?= htmlspecialchars($tier['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Tier</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tech as $t): ?>
                    <tr>
                        <td>
                            <form method="POST" action="/admin/tech-stack/<?= $t['id'] ?>" class="inline-edit-form">
                                <input type="text" name="name" value="<?= htmlspecialchars($t['name']) ?>" class="inline-input">
                                <select name="tier_id" class="inline-select">
                                    <?php foreach ($tiers as $tier): ?>
                                        <option value="<?= $tier['id'] ?>" <?= $tier['id'] == $t['tier_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($tier['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="inline-save">Save</button>
                            </form>
                        </td>
                        <td><?= htmlspecialchars($t['tier_name']) ?></td>
                        <td class="actions">
                            <form method="POST" action="/admin/tech-stack/<?= $t['id'] ?>/delete" style="display:inline">
                                <button type="submit" onclick="return confirm('Delete?')">×</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>