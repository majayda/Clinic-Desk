<?php require __DIR__ . '/../partials/page_start.php'; ?>
<?php if (!$doctor): ?><div class="alert alert-warning">No doctor profile is linked to your account yet.</div><?php else: ?>
<div class="profile-summary">
  <?php if (!empty($doctor['photo'])): ?>
    <img class="profile-summary-img" src="public/uploads/doctor_photos/<?= e($doctor['photo']) ?>" alt="">
  <?php else: ?>
    <span class="profile-summary-fallback"><?= e(strtoupper(substr($doctor['name'] ?? 'D', 0, 1))) ?></span>
  <?php endif; ?>
  <div>
    <h2><?= e($doctor['name'] ?? '') ?></h2>
    <p><?= e($doctor['specialization'] ?? 'Doctor') ?></p>
  </div>
</div>
<div class="row">
  <div class="col-md-3"><div class="small-box bg-info"><div class="inner"><h3><?= (int) $stats['month'] ?></h3><p>This Month</p></div></div></div>
  <div class="col-md-3"><div class="small-box bg-warning"><div class="inner"><h3><?= (int) $stats['pending'] ?></h3><p>Pending</p></div></div></div>
  <div class="col-md-3"><div class="small-box bg-success"><div class="inner"><h3><?= (int) $stats['completed'] ?></h3><p>Completed</p></div></div></div>
</div>
<div class="card"><div class="card-header"><h3 class="card-title">Today</h3></div><div class="card-body">
  <?php foreach ($stats['today'] as $row): ?><p><a href="<?= e(url('appointments', 'show', ['id' => $row['id']])) ?>"><?= e(formatTime($row['appt_time'])) ?> - <?= e($row['patient_name']) ?></a></p><?php endforeach; ?>
</div></div>
<div class="card active-appointments-card">
  <div class="card-header">
    <h3 class="card-title">Upcoming</h3>
  </div>
  <div class="card-body">
    <?php if (empty($stats['upcoming'])): ?>
      <div class="empty-state">
        <i class="far fa-calendar"></i>
        <p>No upcoming appointments.</p>
      </div>
    <?php else: ?>
      <div class="appointment-list">
        <?php foreach ($stats['upcoming'] as $row): ?>
          <a class="appointment-item" href="<?= e(url('appointments', 'show', ['id' => $row['id']])) ?>">
            <span class="appointment-date">
              <strong><?= e(date('M d', strtotime($row['appt_date']))) ?></strong>
              <small><?= e(formatTime($row['appt_time'])) ?></small>
            </span>
            <span class="appointment-main">
              <strong><?= e($row['patient_name']) ?></strong>
              <small><?= e($row['reason'] ?: 'No reason provided') ?></small>
            </span>
            <span class="appointment-status"><?= statusBadge($row['status']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php endif; ?>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
