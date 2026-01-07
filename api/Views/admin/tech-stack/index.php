<?php if (isset($_GET['error']) && $_GET['error'] === 'tier_has_items'): ?>
    <div class="alert alert-error">Cannot delete tier that has items.</div>
<?php endif; ?>

<div class="tech-stack-grid">
    <div class="form-section">
        <h2>Add Tier</h2>
        <form method="POST" action="/admin/tech-stack/tiers" class="inline-form">
            <input type="text" name="name" placeholder="Tier name (e.g. Framework)" required>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>

        <h3>Existing Tiers</h3>
        <ul class="tier-list">
            <?php foreach ($tiers as $tier): ?>
                <li>
                    <span><?= htmlspecialchars($tier['name']) ?> (<?= $tier['sort_order'] ?>)</span>
                    <form method="POST" action="/admin/tech-stack/tiers/<?= $tier['id'] ?>/delete" style="display:inline">
                        <button type="submit" onclick="return confirm('Delete?')">×</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="form-section">
        <h2>Add Technology</h2>
        <form method="POST" action="/admin/tech-stack" class="inline-form">
            <input type="text" name="name" placeholder="Technology name" required>
            <select name="tier_id" required>
                <?php foreach ($tiers as $tier): ?>
                    <option value="<?= $tier['id'] ?>"><?= htmlspecialchars($tier['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>

        <h3>Existing Technologies</h3>
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
                        <td><?= htmlspecialchars($t['name']) ?></td>
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