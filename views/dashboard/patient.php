<?php require __DIR__ . '/../partials/page_start.php'; ?>
<div class="profile-summary">
  <?php if (!empty($patient['avatar'])): ?>
    <img class="profile-summary-img" src="public/uploads/avatars/<?= e($patient['avatar']) ?>" alt="">
  <?php else: ?>
    <span class="profile-summary-fallback"><?= e(strtoupper(substr($patient['name'] ?? 'P', 0, 1))) ?></span>
  <?php endif; ?>
  <div>
    <h2><?= e($patient['name'] ?? 'Patient') ?></h2>
    <p><?= e($patient['email'] ?? '') ?></p>
  </div>
</div>
<div class="row">
  <div class="col-md-3"><div class="small-box bg-info"><div class="inner"><h3><?= count($stats['active']) ?></h3><p>Active Appointments</p></div></div></div>
  <div class="col-md-3"><div class="small-box bg-warning"><div class="inner"><h3><?= (int) $stats['pending'] ?></h3><p>Pending Appointments</p></div></div></div>
  <div class="col-md-3"><div class="small-box bg-success"><div class="inner"><h3><?= (int) $stats['completed'] ?></h3><p>Completed</p></div></div></div>
  <div class="col-md-3"><div class="small-box bg-primary"><div class="inner"><h3><?= (int) $stats['prescriptions'] ?></h3><p>Prescriptions</p></div></div></div>
</div>
<?php if (!empty($stats['active'][0])): $next = $stats['active'][0]; ?>
<div class="callout callout-info"><h5>Next Appointment</h5><p><?= e($next['doctor_name']) ?> on <?= e($next['appt_date']) ?> at <?= e(formatTime($next['appt_time'])) ?></p></div>
<?php endif; ?>
<div class="card active-appointments-card">
  <div class="card-header">
    <h3 class="card-title">Active Appointments</h3>
  </div>
  <div class="card-body">
    <?php if (empty($stats['active'])): ?>
      <div class="empty-state">
        <i class="far fa-calendar-check"></i>
        <p>No active appointments yet.</p>
      </div>
    <?php else: ?>
      <div class="appointment-list">
        <?php foreach ($stats['active'] as $row): ?>
          <a class="appointment-item" href="<?= e(url('appointments', 'show', ['id' => $row['id']])) ?>">
            <span class="appointment-date">
              <strong><?= e(date('M d', strtotime($row['appt_date']))) ?></strong>
              <small><?= e(formatTime($row['appt_time'])) ?></small>
            </span>
            <span class="appointment-main">
              <strong><?= e($row['doctor_name']) ?></strong>
              <small><?= e($row['specialization']) ?></small>
            </span>
            <span class="appointment-status"><?= statusBadge($row['status']) ?></span>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
<?php require __DIR__ . '/../partials/page_end.php'; ?>
