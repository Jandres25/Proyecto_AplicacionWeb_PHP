<?php
require_once __DIR__ . '/../../Core/Autoloader.php';
App\Core\Autoloader::register();

$url_base = app_url('/');
$nombresusuario = App\Core\Auth::fullName();
$usuarioVerificado = App\Core\Auth::username();
?>

<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema de Gestión</title>

  <!-- Bootstrap 5 Core CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

  <link href="<?= e(app_url('/img/compras.png')); ?>" rel="shortcut icon">
  <link rel="stylesheet" href="<?= e(app_url('/css/layout.css')); ?>">

  <!-- Scripts base -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- DataTables & Plugins -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark shadow">
      <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="<?= e($url_base); ?>">
          <img src="https://images.unsplash.com/photo-1641846948845-60e99fa0f072?auto=format&fit=crop&w=100&q=80" alt="Logo" width="30" height="30" class="rounded-circle me-2 border border-secondary">
          <span class="fw-bold">Proyecto</span>
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain" aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="<?= e($url_base); ?>">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= e($url_base); ?>conductores">Conductores</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= e($url_base); ?>propietarios">Propietarios</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?= e($url_base); ?>taxis">Taxis</a>
            </li>
            <?php if ($usuarioVerificado == 'Administrador') : ?>
              <li class="nav-item">
                <a class="nav-link" href="<?= e($url_base); ?>usuarios">Usuarios</a>
              </li>
            <?php endif; ?>
          </ul>
          <hr class="d-lg-none text-white-50">
          <ul class="navbar-nav align-items-lg-center">
            <li class="nav-item text-white-50 px-lg-3 mb-2 mb-lg-0">
              <i class="bi bi-person-circle me-1"></i><?= e($nombresusuario); ?>

            </li>
            <li class="nav-item">
              <form method="post" action="<?= e($url_base); ?>logout" class="d-inline">
                <input type="hidden" name="_token" value="<?= e(App\Core\Csrf::token()); ?>">
                <button type="submit" class="btn btn-outline-light btn-sm w-100">
                  <i class="bi bi-box-arrow-right me-1"></i> Cerrar Sesión
                </button>
              </form>
            </li>
          </ul>
        </div>
      </div>
    </nav>
  </header>
  <main>
    <?php $flashSuccess = App\Core\Flash::get('success'); ?>
    <?php $flashError = App\Core\Flash::get('error'); ?>
    <?php if ($flashSuccess !== null) : ?>
      <script>
        Swal.fire({
          icon: "success",
          title: <?= json_encode($flashSuccess, JSON_UNESCAPED_UNICODE); ?>
        });
      </script>
    <?php endif; ?>
    <?php if ($flashError !== null) : ?>
      <script>
        Swal.fire({
          icon: "error",
          title: <?= json_encode($flashError, JSON_UNESCAPED_UNICODE); ?>
        });
      </script>
    <?php endif; ?>