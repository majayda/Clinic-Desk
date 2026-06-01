<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card"><div class="card-body table-responsive">
  <table class="table table-bordered"><thead><tr><th>Doctor</th><th>Date</th><th>Diagnosis</th><th>PDF</th></tr></thead><tbody>
  <?php foreach ($prescriptions as $row): ?>
    <tr><td><?= e($row['doctor_name']) ?></td><td><?= e($row['appt_date']) ?></td><td><?= e(substr($row['diagnosis'], 0, 90)) ?></td><td><?php if ($row['file_path']): ?><a class="btn btn-sm btn-secondary" href="<?= e(url('prescriptions', 'download', ['id' => $row['appointment_id']])) ?>">Download</a><?php endif; ?></td></tr>
  <?php endforeach; ?>
  </tbody></table>
</div></div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>

