<div class="admin-toolbar">
    <a href="/admin/users" class="btn">Back to Users</a>
</div>

<div class="form-section" style="max-width: 600px;">
    <form method="POST" action="/admin/users/<?= $editUser['id'] ?>">
        <?= \Api\Core\View::csrfField() ?>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($editUser['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" value="<?= htmlspecialchars($editUser['email']) ?>" disabled>
        </div>

        <div class="form-group">
            <label for="role">Role</label>
            <select id="role" name="role">
                <option value="user" <?= $editUser['role'] === 'user' ? 'selected' : '' ?>>User</option>
                <option value="team_member" <?= $editUser['role'] === 'team_member' ? 'selected' : '' ?>>Team Member</option>
            </select>
        </div>

        <div class="form-group">
            <label>Permissions</label>
            <div class="checkbox-grid">
                <?php foreach ($permissions as $perm): ?>
                    <label>
                        <input type="checkbox" name="permissions[]" value="<?= $perm['slug'] ?>"
                            <?= in_array($perm['slug'], $userPermissions) ? 'checked' : '' ?>>
                        <?= htmlspecialchars($perm['label']) ?>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update User</button>
            <a href="/admin/users" class="btn">Cancel</a>
        </div>
    </form>
</div>