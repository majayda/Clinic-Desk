<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card"><div class="card-body">
  <form method="post" enctype="multipart/form-data" action="<?= e(url('users', $user ? 'update' : 'store')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>">
    <?php if ($user): ?><input type="hidden" name="id" value="<?= (int) $user['id'] ?>"><?php endif; ?>
    <div class="form-group"><label>Name</label><input class="form-control" name="name" value="<?= e($user['name'] ?? '') ?>" required></div>
    <div class="form-group"><label>Email</label><input class="form-control" type="email" name="email" value="<?= e($user['email'] ?? '') ?>" required></div>
    <div class="form-group"><label>Password <?= $user ? '(leave blank to keep)' : '' ?></label><input class="form-control" type="password" name="password" <?= $user ? '' : 'required' ?>></div>
    <div class="form-group"><label>Role</label><select class="form-control" name="role"><?php foreach (['admin','doctor','patient'] as $role): ?><option value="<?= $role ?>" <?= ($user['role'] ?? '') === $role ? 'selected' : '' ?>><?= ucfirst($role) ?></option><?php endforeach; ?></select></div>
    <div class="form-group"><label>Phone</label><input class="form-control" name="phone" value="<?= e($user['phone'] ?? '') ?>"></div>
    <div class="form-group"><label>Avatar (JPEG/PNG, max 1MB)</label><input class="form-control" type="file" name="avatar" accept="image/jpeg,image/png"></div>
    <?php if (!empty($user['avatar'])): ?><p><img class="thumb" src="public/uploads/avatars/<?= e($user['avatar']) ?>" alt=""></p><?php endif; ?>
    <?php if (!$user): ?>
    <hr>
    <h5>Doctor Details</h5>
    <div class="form-group"><label>Specialization</label><select class="form-control" name="specialization_id"><?php foreach (($specializations ?? []) as $s): ?><option value="<?= (int) $s['id'] ?>"><?= e($s['name']) ?></option><?php endforeach; ?></select></div>
    <div class="form-group"><label>Bio</label><textarea class="form-control" name="bio"></textarea></div>
    <div class="form-group"><label>Doctor Photo (JPEG/PNG, max 1MB)</label><input class="form-control" type="file" name="photo" accept="image/jpeg,image/png"></div>
    <div class="form-group"><label>Consultation Fee</label><input class="form-control" type="number" step="0.01" name="consultation_fee" value="0.00"></div>
    <div class="form-group">
      <label>Available Days</label>
      <div>
        <?php foreach (['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day): ?>
          <label class="mr-3"><input type="checkbox" name="available_days[]" value="<?= $day ?>" <?= in_array($day, ['Sun','Mon','Tue','Wed','Thu'], true) ? 'checked' : '' ?>> <?= $day ?></label>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>
    <div class="form-group"><label>Active</label><select class="form-control" name="is_active"><option value="1">Yes</option><option value="0" <?= isset($user) && !(int) $user['is_active'] ? 'selected' : '' ?>>No</option></select></div>
    <button class="btn btn-primary">Save</button>
  </form>
</div></div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
