<?php
require __DIR__ . '/header.php';
require __DIR__ . '/navbar.php';
require __DIR__ . '/sidebar.php';
?>
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="page-title-row">
        <div>
          <h1><?= e($pageTitle ?? '') ?></h1>
          <div class="breadcrumb-line">Dashboard / <?= e($pageTitle ?? '') ?></div>
        </div>
      </div>
    </div>
  </section>
  <section class="content">
    <div class="container-fluid">
      <?php require __DIR__ . '/alerts.php'; ?>
