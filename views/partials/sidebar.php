<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <a href="<?= e(url()) ?>" class="brand-link"><span class="brand-mark">CD</span><span class="brand-text font-weight-light"><?= APP_NAME ?></span></a>
  <div class="sidebar">
    <div class="sidebar-label">Workspace</div>
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column">
        <li class="nav-item"><a href="<?= e(url()) ?>" class="nav-link <?= active('dashboard') ?>"><i class="nav-icon fas fa-tachometer-alt"></i><p>Dashboard</p></a></li>
        <?php if (Auth::role() === 'admin'): ?>
          <li class="nav-item"><a href="<?= e(url('users')) ?>" class="nav-link <?= active('users') ?>"><i class="nav-icon fas fa-users"></i><p>Users</p></a></li>
          <li class="nav-item"><a href="<?= e(url('doctors')) ?>" class="nav-link <?= active('doctors') ?>"><i class="nav-icon fas fa-user-md"></i><p>Doctors</p></a></li>
          <li class="nav-item"><a href="<?= e(url('specializations')) ?>" class="nav-link <?= active('specializations') ?>"><i class="nav-icon fas fa-user-md"></i><p>Specializations</p></a></li>
          <li class="nav-item"><a href="<?= e(url('appointments')) ?>" class="nav-link <?= active('appointments') ?>"><i class="nav-icon fas fa-calendar"></i><p>Appointments</p></a></li>
          <li class="nav-item"><a href="<?= e(url('reports')) ?>" class="nav-link <?= active('reports') ?>"><i class="nav-icon fas fa-file-csv"></i><p>Reports</p></a></li>
        <?php elseif (Auth::role() === 'doctor'): ?>
          <li class="nav-item"><a href="<?= e(url('appointments')) ?>" class="nav-link <?= active('appointments') ?>"><i class="nav-icon fas fa-calendar"></i><p>My Schedule</p></a></li>
          <li class="nav-item"><a href="<?= e(url('doctors', 'edit')) ?>" class="nav-link <?= active('doctors') ?>"><i class="nav-icon fas fa-user"></i><p>My Profile</p></a></li>
        <?php elseif (Auth::role() === 'patient'): ?>
          <li class="nav-item"><a href="<?= e(url('appointments', 'book')) ?>" class="nav-link <?= active('appointments', 'book') ?>"><i class="nav-icon fas fa-plus"></i><p>Book Appointment</p></a></li>
          <li class="nav-item"><a href="<?= e(url('appointments')) ?>" class="nav-link <?= active('appointments', 'index') ?>"><i class="nav-icon fas fa-history"></i><p>History</p></a></li>
          <li class="nav-item"><a href="<?= e(url('prescriptions')) ?>" class="nav-link <?= active('prescriptions') ?>"><i class="nav-icon fas fa-prescription"></i><p>Prescriptions</p></a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</aside>
