<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item"><a class="nav-link page-kicker" href="<?= e(url()) ?>"><?= e($pageTitle ?? APP_NAME) ?></a></li>
  </ul>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <span class="user-chip">
        <?php if (!empty($user['avatar'])): ?>
          <img class="user-avatar-img" src="public/uploads/avatars/<?= e($user['avatar']) ?>" alt="">
        <?php else: ?>
          <span class="user-avatar"><?= e(strtoupper(substr($user['name'] ?? 'U', 0, 1))) ?></span>
        <?php endif; ?>
        <span><?= e($user['name'] ?? '') ?></span><small><?= e($user['role'] ?? '') ?></small>
      </span>
    </li>
    <li class="nav-item">
      <form method="post" action="<?= e(url('auth', 'logout')) ?>" class="mb-0">
        <input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>">
        <button class="btn btn-light" type="submit">Logout</button>
      </form>
    </li>
  </ul>
</nav>
