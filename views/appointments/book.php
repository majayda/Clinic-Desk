<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card"><div class="card-body">
  <form method="post" action="<?= e(url('appointments', 'store')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>">
    <div class="form-group"><label>Doctor</label><select class="form-control" name="doctor_id" required><?php foreach ($doctors as $d): ?><option value="<?= (int) $d['id'] ?>"><?= e($d['name']) ?> - <?= e($d['specialization']) ?> (<?= e($d['available_days']) ?>)</option><?php endforeach; ?></select></div>
    <div class="form-group"><label>Date</label><input class="form-control" type="date" name="appt_date" required></div>
    <div class="form-group"><label>Time</label><input class="form-control" type="time" name="appt_time" required></div>
    <div class="form-group"><label>Reason</label><input class="form-control" name="reason" maxlength="255"></div>
    <button class="btn btn-primary">Book</button>
  </form>
</div></div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
