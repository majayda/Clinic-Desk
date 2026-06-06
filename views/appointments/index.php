<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card">
  <div class="card-header"><?php if (Auth::role() === 'patient'): ?><a class="btn btn-primary" href="<?= e(url('appointments', 'book')) ?>">Book Appointment</a><?php endif; ?></div>
  <div class="card-body">
    <?php if (Auth::role() === 'doctor' && !empty($today)): ?>
      <div class="callout callout-info">
        <h5>Today</h5>
        <?php foreach ($today as $row): ?>
          <p class="mb-1"><a href="<?= e(url('appointments', 'show', ['id' => $row['id']])) ?>"><?= e(formatTime($row['appt_time'])) ?> - <?= e($row['patient_name']) ?></a> <?= statusBadge($row['status']) ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
    <form class="form-row mb-3">
      <input type="hidden" name="page" value="appointments">
      <?php if (Auth::role() === 'admin'): ?>
      <div class="col"><select class="form-control" name="doctor_id"><option value="">All doctors</option><?php foreach ($doctors as $d): ?><option value="<?= (int) $d['id'] ?>" <?= (string) $filters['doctor_id'] === (string) $d['id'] ? 'selected' : '' ?>><?= e($d['name']) ?></option><?php endforeach; ?></select></div>
      <div class="col"><input class="form-control" name="patient_name" value="<?= e($filters['patient_name']) ?>" placeholder="Patient"></div>
      <?php endif; ?>
      <div class="col"><input class="form-control" type="date" name="start_date" value="<?= e($filters['start_date']) ?>"></div>
      <div class="col"><input class="form-control" type="date" name="end_date" value="<?= e($filters['end_date']) ?>"></div>
      <div class="col"><select class="form-control" name="status"><option value="">Any status</option><?php foreach (['pending','confirmed','completed','cancelled'] as $s): ?><option value="<?= $s ?>" <?= $filters['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option><?php endforeach; ?></select></div>
      <div class="col"><button class="btn btn-secondary">Filter</button></div>
    </form>
    <table class="table table-bordered table-striped"><thead><tr><th>Patient</th><th>Doctor</th><th>Date</th><th>Time</th><th>Status</th><th>Reason</th><th></th></tr></thead><tbody>
    <?php foreach ($appointments as $row): ?>
      <tr>
        <td><?= e($row['patient_name']) ?></td><td><?= e($row['doctor_name']) ?></td><td><?= e($row['appt_date']) ?></td><td><?= e(formatTime($row['appt_time'])) ?></td>
        <td><?= statusBadge($row['status']) ?></td><td><?= e($row['reason']) ?></td>
        <td class="actions">
          <a class="btn btn-sm btn-info" href="<?= e(url('appointments', 'show', ['id' => $row['id']])) ?>">View</a>
          <?php if (Auth::role() === 'patient' && $row['status'] === 'pending'): ?>
            <form method="post" action="<?= e(url('appointments', 'cancel')) ?>" onsubmit="return confirm('Cancel this appointment?')">
              <input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>">
              <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
              <button class="btn btn-sm btn-warning">Cancel</button>
            </form>
          <?php endif; ?>
          <?php if (Auth::role() === 'patient' && $row['status'] === 'completed' && $row['prescription_id']): ?>
            <a class="btn btn-sm btn-secondary" href="<?= e(url('prescriptions', 'download', ['id' => $row['id']])) ?>">View Prescription</a>
          <?php endif; ?>
          <?php if (Auth::role() === 'doctor'): ?>
            <?php foreach ([['pending', 'confirmed', 'Confirm'], ['confirmed', 'completed', 'Complete']] as $action): ?>
              <?php if ($row['status'] === $action[0]): ?>
                <form method="post" action="<?= e(url('appointments', 'updateStatus')) ?>">
                  <input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>">
                  <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                  <input type="hidden" name="status" value="<?= e($action[1]) ?>">
                  <button class="btn btn-sm btn-primary"><?= e($action[2]) ?></button>
                </form>
              <?php endif; ?>
            <?php endforeach; ?>
            <?php if (in_array($row['status'], ['pending', 'confirmed'], true)): ?>
              <form method="post" action="<?= e(url('appointments', 'updateStatus')) ?>">
                <input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>">
                <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
                <input type="hidden" name="status" value="cancelled">
                <button class="btn btn-sm btn-warning">Cancel</button>
              </form>
            <?php endif; ?>
            <?php if ($row['status'] === 'completed' && !$row['prescription_id']): ?>
              <a class="btn btn-sm btn-success" href="<?= e(url('prescriptions', 'add', ['appointment_id' => $row['id']])) ?>">Add Prescription</a>
            <?php endif; ?>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody></table>
    <?php require __DIR__ . '/../partials/pagination.php'; ?>
  </div>
</div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
