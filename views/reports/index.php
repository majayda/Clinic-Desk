<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card"><div class="card-body">
  <form class="form-row mb-3" method="get" action="index.php">
    <input type="hidden" name="page" value="reports">
    <input type="hidden" name="action" value="index">
    <div class="col"><label>Start Date</label><input class="form-control" type="date" name="start_date" value="<?= e($filters['start_date']) ?>" required></div>
    <div class="col"><label>End Date</label><input class="form-control" type="date" name="end_date" value="<?= e($filters['end_date']) ?>" required></div>
    <div class="col"><label>Doctor</label><select class="form-control" name="doctor_id"><option value="">All doctors</option><?php foreach ($doctors as $d): ?><option value="<?= (int) $d['id'] ?>" <?= (string) $filters['doctor_id'] === (string) $d['id'] ? 'selected' : '' ?>><?= e($d['name']) ?></option><?php endforeach; ?></select></div>
    <div class="col"><label>Status</label><select class="form-control" name="status"><option value="">Any status</option><?php foreach (['pending','confirmed','completed','cancelled'] as $s): ?><option value="<?= $s ?>" <?= $filters['status'] === $s ? 'selected' : '' ?>><?= ucfirst($s) ?></option><?php endforeach; ?></select></div>
    <div class="col"><label>&nbsp;</label><button class="btn btn-primary" type="submit">Run</button> <button class="btn btn-secondary" type="submit" name="export" value="csv">CSV</button></div>
  </form>
  <table class="table table-bordered"><thead><tr><th>Patient</th><th>Doctor</th><th>Specialization</th><th>Date</th><th>Time</th><th>Status</th><th>Reason</th></tr></thead><tbody>
  <?php $counts = []; foreach ($rows as $row): $counts[$row['status']] = ($counts[$row['status']] ?? 0) + 1; ?>
    <tr><td><?= e($row['patient_name']) ?></td><td><?= e($row['doctor_name']) ?></td><td><?= e($row['specialization']) ?></td><td><?= e($row['appt_date']) ?></td><td><?= e(formatTime($row['appt_time'])) ?></td><td><?= statusBadge($row['status']) ?></td><td><?= e($row['reason']) ?></td></tr>
  <?php endforeach; ?>
  </tbody></table>
  <p><strong>Total:</strong> <?= count($rows) ?> <?php foreach ($counts as $status => $count): ?><span class="badge badge-info"><?= e($status) ?>: <?= (int) $count ?></span><?php endforeach; ?></p>
</div></div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
