<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="card prescriptions-table-card">
  <div class="card-header">
    <h3 class="card-title">My Prescriptions</h3>
  </div>
  <div class="card-body table-responsive">
    <?php if (empty($prescriptions)): ?>
      <div class="empty-state">
        <i class="fas fa-prescription"></i>
        <p>No prescriptions are available yet.</p>
      </div>
    <?php else: ?>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Doctor</th>
            <th>Date</th>
            <th>Diagnosis</th>
            <th>Medications</th>
            <th>Notes</th>
            <th>PDF</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($prescriptions as $row): ?>
          <tr>
            <td><?= e($row['doctor_name']) ?></td>
            <td><?= e($row['appt_date']) ?></td>
            <td><?= e(substr($row['diagnosis'], 0, 90)) ?></td>
            <td><?= e(substr($row['medications'], 0, 120)) ?></td>
            <td><?= e(substr($row['notes'] ?? '', 0, 100)) ?></td>
            <td><?php if ($row['file_path']): ?><a class="btn btn-sm btn-success" href="<?= e(url('prescriptions', 'download', ['id' => $row['appointment_id']])) ?>">Download</a><?php endif; ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
