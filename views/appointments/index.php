<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card">
  <div class="card-header"><?php if (Auth::role() === 'patient'): ?><a class="btn btn-primary" href="<?= e(url('appointments', 'book')) ?>">Book Appointment</a><?php endif; ?></div>
  <div class="card-body">
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
    <table class="table table-bordered table-striped"><thead><tr><th>Patient</th><th>Doctor</th><th>Date</th><th>Time</th><th>Status</th><th></th></tr></thead><tbody>
    <?php foreach ($appointments as $row): ?>
      <tr>
        <td><?= e($row['patient_name']) ?></td><td><?= e($row['doctor_name']) ?></td><td><?= e($row['appt_date']) ?></td><td><?= e(formatTime($row['appt_time'])) ?></td>
        <td><?= statusBadge($row['status']) ?></td>
        <td><a class="btn btn-sm btn-info" href="<?= e(url('appointments', 'show', ['id' => $row['id']])) ?>">View</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody></table>
    <?php require __DIR__ . '/../partials/pagination.php'; ?>
  </div>
</div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
