<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item"><a class="nav-link page-kicker" href="<?= e(url()) ?>"><?= e($pageTitle ?? APP_NAME) ?></a></li>
  </ul>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item"><span class="user-chip"><span class="user-avatar"><?= e(strtoupper(substr($user['name'] ?? 'U', 0, 1))) ?></span><span><?= e($user['name'] ?? '') ?></span><small><?= e($user['role'] ?? '') ?></small></span></li>
    <li class="nav-item"><a class="btn btn-light" href="<?= e(url('auth', 'logout')) ?>">Logout</a></li>
  </ul>
</nav>
