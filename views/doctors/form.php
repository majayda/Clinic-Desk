<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card"><div class="card-body">
  <form method="post" enctype="multipart/form-data" action="<?= e(url('doctors', $doctor ? 'update' : 'store')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>">
    <?php if ($doctor): ?><input type="hidden" name="id" value="<?= (int) $doctor['id'] ?>"><?php endif; ?>
    <?php if (!$doctor): ?><div class="form-group"><label>Doctor User</label><select class="form-control" name="user_id"><?php foreach ($doctorUsers as $u): ?><option value="<?= (int) $u['id'] ?>"><?= e($u['name']) ?></option><?php endforeach; ?></select></div><?php endif; ?>
    <div class="form-group"><label>Specialization</label><select class="form-control" name="specialization_id"><?php foreach ($specializations as $s): ?><option value="<?= (int) $s['id'] ?>" <?= (int) ($doctor['specialization_id'] ?? 0) === (int) $s['id'] ? 'selected' : '' ?>><?= e($s['name']) ?></option><?php endforeach; ?></select></div>
    <div class="form-group"><label>Bio</label><textarea class="form-control" name="bio"><?= e($doctor['bio'] ?? '') ?></textarea></div>
    <div class="form-group"><label>Doctor Photo (JPEG/PNG, max 1MB)</label><input class="form-control" type="file" name="photo" accept="image/jpeg,image/png"></div>
    <?php if (!empty($doctor['photo'])): ?><p><img class="thumb" src="public/uploads/doctor_photos/<?= e($doctor['photo']) ?>" alt=""></p><?php endif; ?>
    <div class="form-group"><label>Consultation Fee</label><input class="form-control" type="number" step="0.01" name="consultation_fee" value="<?= e((string) ($doctor['consultation_fee'] ?? '0.00')) ?>"></div>
    <div class="form-group"><label>Available Days</label><input class="form-control" name="available_days" value="<?= e($doctor['available_days'] ?? 'Sun,Mon,Tue,Wed,Thu') ?>"></div>
    <button class="btn btn-primary">Save</button>
  </form>
</div></div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
