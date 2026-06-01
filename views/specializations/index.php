<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card">
  <div class="card-header"><a class="btn btn-primary" href="<?= e(url('specializations', 'create')) ?>">Create Specialization</a></div>
  <div class="card-body table-responsive">
    <table class="table table-bordered">
      <thead><tr><th>Name</th><th></th></tr></thead>
      <tbody>
      <?php foreach ($specializations as $row): ?>
        <tr>
          <td><?= e($row['name']) ?></td>
          <td class="actions">
            <a class="btn btn-sm btn-info" href="<?= e(url('specializations', 'edit', ['id' => $row['id']])) ?>">Edit</a>
            <form method="post" action="<?= e(url('specializations', 'delete')) ?>" onsubmit="return confirm('Delete this specialization?')">
              <input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>">
              <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
              <button class="btn btn-sm btn-secondary">Delete</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>

