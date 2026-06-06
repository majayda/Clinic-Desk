<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="row">
  <?php $roleColors = ['admin' => 'bg-primary', 'doctor' => 'bg-info', 'patient' => 'bg-success']; ?>
  <?php foreach ($stats['roles'] as $role): ?>
    <div class="col-md-3"><div class="small-box <?= e($roleColors[$role['role']] ?? 'bg-info') ?>"><div class="inner"><h3><?= (int) $role['total'] ?></h3><p><?= e(ucfirst($role['role'])) ?> Users</p></div></div></div>
  <?php endforeach; ?>
  <div class="col-md-3"><div class="small-box bg-warning"><div class="inner"><h3><?= (int) $stats['today'] ?></h3><p>Appointments Today</p></div></div></div>
</div>
<div class="row">
  <?php $statusColors = ['pending' => 'bg-warning', 'confirmed' => 'bg-info', 'completed' => 'bg-success', 'cancelled' => 'bg-danger']; ?>
  <?php foreach ($stats['week'] as $status): ?>
    <div class="col-md-3"><div class="small-box <?= e($statusColors[$status['status']] ?? 'bg-secondary') ?>"><div class="inner"><h3><?= (int) $status['total'] ?></h3><p><?= e(ucfirst($status['status'])) ?> This Week</p></div></div></div>
  <?php endforeach; ?>
</div>
<div class="card">
  <div class="card-header"><h3 class="card-title">Recent Appointments</h3></div>
  <div class="card-body table-responsive">
    <table class="table table-bordered"><thead><tr><th>Patient</th><th>Doctor</th><th>Date</th><th>Status</th></tr></thead><tbody>
    <?php foreach ($stats['recent'] as $row): ?>
      <tr><td><?= e($row['patient_name']) ?></td><td><?= e($row['doctor_name']) ?></td><td><?= e($row['appt_date']) ?> <?= e(formatTime($row['appt_time'])) ?></td><td><?= statusBadge($row['status']) ?></td></tr>
    <?php endforeach; ?>
    </tbody></table>
  </div>
</div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
