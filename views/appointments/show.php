<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card"><div class="card-body">
  <dl class="row">
    <dt class="col-sm-3">Patient</dt><dd class="col-sm-9"><?= e($appointment['patient_name']) ?></dd>
    <dt class="col-sm-3">Doctor</dt><dd class="col-sm-9"><?= e($appointment['doctor_name']) ?> (<?= e($appointment['specialization']) ?>)</dd>
    <dt class="col-sm-3">Date</dt><dd class="col-sm-9"><?= e($appointment['appt_date']) ?> <?= e(formatTime($appointment['appt_time'])) ?></dd>
    <dt class="col-sm-3">Reason</dt><dd class="col-sm-9"><?= e($appointment['reason']) ?></dd>
    <dt class="col-sm-3">Status</dt><dd class="col-sm-9"><?= statusBadge($appointment['status']) ?></dd>
    <dt class="col-sm-3">Doctor Notes</dt><dd class="col-sm-9"><?= e($appointment['doctor_notes']) ?></dd>
  </dl>
  <?php if (Auth::role() !== 'patient'): ?>
  <form method="post" action="<?= e(url('appointments', 'updateStatus')) ?>">
    <input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>">
    <input type="hidden" name="id" value="<?= (int) $appointment['id'] ?>">
    <div class="form-group"><label>Status</label><select class="form-control" name="status"><?php foreach (['pending','confirmed','completed','cancelled'] as $s): ?><option value="<?= $s ?>" <?= $appointment['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option><?php endforeach; ?></select></div>
    <div class="form-group"><label>Notes</label><textarea class="form-control" name="doctor_notes"><?= e($appointment['doctor_notes']) ?></textarea></div>
    <button class="btn btn-primary">Update Status</button>
  </form>
  <?php endif; ?>
  <?php if (Auth::role() === 'doctor' && $appointment['status'] === 'completed' && !$prescription): ?>
    <a class="btn btn-success mt-3" href="<?= e(url('prescriptions', 'add', ['appointment_id' => $appointment['id']])) ?>">Add Prescription</a>
  <?php endif; ?>
  <?php if ($prescription && $prescription['file_path']): ?>
    <a class="btn btn-success mt-3" href="<?= e(url('prescriptions', 'download', ['id' => $appointment['id']])) ?>">Download Prescription PDF</a>
  <?php endif; ?>
</div></div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
