<div class="admin-toolbar">
    <a href="/admin/users" class="btn">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
        Back to Users
    </a>
</div>

<form method="POST" action="/admin/users/<?= $editUser['id'] ?>">
    <?= \Api\Core\View::csrfField() ?>
    
    <div class="user-edit-grid">
        <div class="form-section">
            <h2>Basic Information</h2>
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($editUser['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" value="<?= htmlspecialchars($editUser['email']) ?>" disabled>
                <small style="color: var(--text-muted); font-size: var(--text-xs);">Email cannot be changed</small>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" onchange="togglePermissions(this.value)">
                    <option value="user" <?= $editUser['role'] === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="team_member" <?= $editUser['role'] === 'team_member' ? 'selected' : '' ?>>Team Member</option>
                </select>
            </div>
        </div>

        <div class="form-section" id="permissions-section" style="<?= $editUser['role'] !== 'team_member' ? 'opacity: 0.5; pointer-events: none;' : '' ?>">
            <h2>Team Member Permissions</h2>
            <p style="font-size: var(--text-sm); color: var(--text-muted); margin-bottom: var(--space-md);">
                These permissions only apply when the user is a Team Member.
            </p>
            <div class="permissions-grid">
                <?php foreach ($permissions as $perm): ?>
                    <label class="permission-item">
                        <input type="checkbox" name="permissions[]" value="<?= $perm['slug'] ?>"
                            <?= in_array($perm['slug'], $userPermissions) ? 'checked' : '' ?>>
                        <div class="permission-content">
                            <span class="permission-label"><?= htmlspecialchars($perm['label']) ?></span>
                            <span class="permission-slug"><?= htmlspecialchars($perm['slug']) ?></span>
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="form-actions" style="margin-top: var(--space-lg);">
        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="/admin/users" class="btn">Cancel</a>
    </div>
</form>

<script>
function togglePermissions(role) {
    const section = document.getElementById('permissions-section');
    if (role === 'team_member') {
        section.style.opacity = '1';
        section.style.pointerEvents = 'auto';
    } else {
        section.style.opacity = '0.5';
        section.style.pointerEvents = 'none';
    }
}
</script>