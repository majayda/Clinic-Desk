<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Login | <?= APP_NAME ?></title>
  <link rel="stylesheet" href="public/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="public/assets/adminlte/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="public/assets/css/app.css">
</head>
<body class="login-page">
<div class="login-box">
  <div class="login-logo"><span>CD</span><b>Clinic</b>Desk</div>
  <?php require __DIR__ . '/../partials/alerts.php'; ?>
  <div class="card">
    <div class="card-body login-card-body">
      <h1>Sign in</h1>
      <form method="post" action="<?= e(url('auth', 'authenticate')) ?>">
        <input type="hidden" name="csrf_token" value="<?= e(CSRF::generateToken()) ?>">
        <div class="form-group"><label>Email</label><input class="form-control" id="email" type="email" name="email" placeholder="admin@clinic.local" required></div>
        <div class="form-group"><label>Password</label><input class="form-control" id="password" type="password" name="password" placeholder="Admin@1234" required></div>
        <button class="btn btn-primary btn-block">Sign In</button>
      </form>
      <p class="text-muted mt-3 mb-0">Demo password: Admin@1234</p>
      <div class="demo-logins mt-3">
        <button class="btn btn-sm btn-outline-primary" type="button" data-email="admin@clinic.local">Admin</button>
        <button class="btn btn-sm btn-outline-primary" type="button" data-email="omar.nasser@clinic.local">Doctor</button>
        <button class="btn btn-sm btn-outline-primary" type="button" data-email="aya.mansour@clinic.local">Patient</button>
      </div>
    </div>
  </div>
</div>
<script>
  document.querySelectorAll('[data-email]').forEach(function (button) {
    button.addEventListener('click', function () {
      document.getElementById('email').value = button.getAttribute('data-email');
      document.getElementById('password').value = 'Admin@1234';
    });
  });
</script>
</body>
</html>
