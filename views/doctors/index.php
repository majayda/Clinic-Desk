<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card">
  <div class="card-header"><?php if (Auth::role() === 'admin'): ?><a class="btn btn-primary" href="<?= e(url('doctors', 'create')) ?>">Create Doctor Profile</a><?php endif; ?></div>
  <div class="card-body table-responsive">
    <table class="table table-bordered"><thead><tr><th>Doctor</th><th>Specialization</th><th>Fee</th><th>Days</th><th></th></tr></thead><tbody>
    <?php foreach ($doctors as $row): ?>
      <tr>
        <td><div class="person-cell"><?php if (!empty($row['photo'])): ?><img class="avatar-sm" src="public/uploads/doctor_photos/<?= e($row['photo']) ?>" alt=""><?php else: ?><span class="avatar-sm avatar-fallback"><?= e(strtoupper(substr($row['name'], 0, 1))) ?></span><?php endif; ?><span><?= e($row['name']) ?></span></div></td>
        <td><?= e($row['specialization']) ?></td><td><?= e((string) $row['consultation_fee']) ?></td><td><?= e($row['available_days']) ?></td>
        <td class="actions"><?php if (Auth::role() === 'admin'): ?><a class="btn btn-sm btn-info" href="<?= e(url('doctors', 'edit', ['id' => $row['id']])) ?>">Edit</a><form method="post" action="<?= e(url('doctors', 'delete')) ?>" onsubmit="return confirm('Delete this doctor profile?')"><input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>"><input type="hidden" name="id" value="<?= (int) $row['id'] ?>"><button class="btn btn-sm btn-secondary">Delete</button></form><?php endif; ?></td>
      </tr>
    <?php endforeach; ?>
    </tbody></table>
  </div>
</div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
