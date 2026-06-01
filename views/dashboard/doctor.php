<?php require __DIR__ . '/../partials/page_start.php'; ?>
<?php if (!$doctor): ?><div class="alert alert-warning">No doctor profile is linked to your account yet.</div><?php else: ?>
<div class="row">
  <div class="col-md-3"><div class="small-box bg-info"><div class="inner"><h3><?= (int) $stats['month'] ?></h3><p>This Month</p></div></div></div>
  <div class="col-md-3"><div class="small-box bg-warning"><div class="inner"><h3><?= (int) $stats['pending'] ?></h3><p>Pending</p></div></div></div>
  <div class="col-md-3"><div class="small-box bg-success"><div class="inner"><h3><?= (int) $stats['completed'] ?></h3><p>Completed</p></div></div></div>
</div>
<div class="card"><div class="card-header"><h3 class="card-title">Today</h3></div><div class="card-body">
  <?php foreach ($stats['today'] as $row): ?><p><a href="<?= e(url('appointments', 'show', ['id' => $row['id']])) ?>"><?= e(formatTime($row['appt_time'])) ?> - <?= e($row['patient_name']) ?></a></p><?php endforeach; ?>
</div></div>
<div class="card"><div class="card-header"><h3 class="card-title">Upcoming</h3></div><div class="card-body">
  <?php foreach ($stats['upcoming'] as $row): ?><p><?= e($row['appt_date']) ?> <?= e(formatTime($row['appt_time'])) ?> - <?= e($row['patient_name']) ?></p><?php endforeach; ?>
</div></div>
<?php endif; ?>
<?php require __DIR__ . '/../partials/page_end.php'; ?>

