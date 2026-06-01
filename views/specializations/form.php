<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card"><div class="card-body">
  <form method="post" action="<?= e(url('specializations', $specialization ? 'update' : 'store')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>">
    <?php if ($specialization): ?><input type="hidden" name="id" value="<?= (int) $specialization['id'] ?>"><?php endif; ?>
    <div class="form-group"><label>Name</label><input class="form-control" name="name" value="<?= e($specialization['name'] ?? '') ?>" maxlength="100" required></div>
    <button class="btn btn-primary">Save</button>
  </form>
</div></div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>

