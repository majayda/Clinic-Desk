<?php foreach ($_SESSION['flash'] ?? [] as $alert): ?>
  <div class="alert alert-<?= e($alert['type']) ?>"><?= e($alert['message']) ?></div>
<?php endforeach; unset($_SESSION['flash']); ?>

