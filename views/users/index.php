<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card">
  <div class="card-header"><a class="btn btn-primary" href="<?= e(url('users', 'create')) ?>">Create User</a></div>
  <div class="card-body">
    <form class="form-inline mb-3"><input type="hidden" name="page" value="users"><input class="form-control mr-2" name="search" value="<?= e($search) ?>" placeholder="Search"><button class="btn btn-secondary">Filter</button></form>
    <table class="table table-bordered table-striped"><thead><tr><th>User</th><th>Email</th><th>Role</th><th>Active</th><th></th></tr></thead><tbody>
    <?php foreach ($users as $row): ?>
      <tr>
        <td><div class="person-cell"><?php if (!empty($row['avatar'])): ?><img class="avatar-sm" src="public/uploads/avatars/<?= e($row['avatar']) ?>" alt=""><?php else: ?><span class="avatar-sm avatar-fallback"><?= e(strtoupper(substr($row['name'], 0, 1))) ?></span><?php endif; ?><span><?= e($row['name']) ?></span></div></td>
        <td><?= e($row['email']) ?></td><td><?= e($row['role']) ?></td><td><span class="badge <?= (int) $row['is_active'] ? 'badge-success' : 'badge-secondary' ?>"><?= (int) $row['is_active'] ? 'Active' : 'Inactive' ?></span></td>
        <td class="actions"><a class="btn btn-sm btn-info" href="<?= e(url('users', 'edit', ['id' => $row['id']])) ?>">Edit</a><?php if ((int) $row['id'] !== Auth::id()): ?><form method="post" action="<?= e(url('users', 'delete')) ?>" onsubmit="return confirm('Delete this user?')"><input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>"><input type="hidden" name="id" value="<?= (int) $row['id'] ?>"><button class="btn btn-sm btn-secondary">Delete</button></form><?php endif; ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody></table>
    <?php require __DIR__ . '/../partials/pagination.php'; ?>
  </div>
</div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
