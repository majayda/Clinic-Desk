<?php if ($paginator->totalPages() > 1): ?>
<nav>
  <ul class="pagination">
    <?php for ($i = 1; $i <= $paginator->totalPages(); $i++): ?>
      <?php $params = array_merge($_GET, ['p' => $i]); ?>
      <li class="page-item <?= $i === $paginator->currentPage() ? 'active' : '' ?>">
        <a class="page-link" href="index.php?<?= e(http_build_query($params)) ?>"><?= $i ?></a>
      </li>
    <?php endfor; ?>
  </ul>
</nav>
<?php endif; ?>

