<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="row">
  <div class="col-md-4"><div class="small-box bg-info"><div class="inner"><h3><?= count($stats['active']) ?></h3><p>Active Appointments</p></div></div></div>
  <div class="col-md-4"><div class="small-box bg-success"><div class="inner"><h3><?= (int) $stats['completed'] ?></h3><p>Completed</p></div></div></div>
  <div class="col-md-4"><div class="small-box bg-primary"><div class="inner"><h3><?= (int) $stats['prescriptions'] ?></h3><p>Prescriptions</p></div></div></div>
</div>
<?php if (!empty($stats['active'][0])): $next = $stats['active'][0]; ?>
<div class="callout callout-info"><h5>Next Appointment</h5><p><?= e($next['doctor_name']) ?> on <?= e($next['appt_date']) ?> at <?= e(formatTime($next['appt_time'])) ?></p></div>
<?php endif; ?>
<div class="card"><div class="card-header"><h3 class="card-title">Active Appointments</h3></div><div class="card-body">
  <?php foreach ($stats['active'] as $row): ?><p><a href="<?= e(url('appointments', 'show', ['id' => $row['id']])) ?>"><?= e($row['doctor_name']) ?> - <?= e($row['appt_date']) ?> <?= e(formatTime($row['appt_time'])) ?></a></p><?php endforeach; ?>
</div></div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>

