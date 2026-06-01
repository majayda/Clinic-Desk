<?php $user = Auth::currentUser(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle ?? APP_NAME) ?> | <?= APP_NAME ?></title>
  <link rel="stylesheet" href="public/assets/adminlte/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="public/assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="public/assets/adminlte/dist/css/adminlte.min.css">
  <link rel="stylesheet" href="public/assets/css/app.css">
</head>
<body class="hold-transition sidebar-mini app-shell">
<div class="wrapper">
