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
  <meta name="description" content="">
  <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
  <meta name="generator" content="Hugo 0.72.0">
  <title>Pagina Principal</title>

  <link rel="canonical" href="https://v5.getbootstrap.com/docs/5.0/examples/carousel/">

  <!-- Bootstrap core CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
  <link href="<?php echo e(app_url('/img/compras.png')); ?>" rel="shortcut icon">
  <link rel="stylesheet" href="<?php echo e(app_url('/css/layout.css')); ?>">
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand navbar-dark fixed-top bg-dark">
      <div class="container-fluid" id="barra_navegacion">
        <ul class="navbar-nav mr-auto mb-2 mb-md-0 align-items-center">
          <li class="nav-item">
            <a class="nav-link active nav-brand-link" href="<?php echo e($url_base); ?>">
              <img src="https://images.unsplash.com/photo-1641846948845-60e99fa0f072?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1229&q=80" alt="Bootstrap" width="30" height="25" class="rounded-circle">
              <span>Proyecto</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?php echo e($url_base); ?>">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?php echo e($url_base); ?>conductores">Conductores</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e($url_base); ?>propietarios">Propietarios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo e($url_base); ?>taxis">Taxis</a>
          </li>
          <?php if ($usuarioVerificado == 'Administrador') { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo e($url_base); ?>usuarios">Usuarios</a>
            </li>
          <?php } ?>
        </ul>
        <ul class="navbar-nav align-items-center">
          <li class="nav-item text-white p-2 user-greeting">Bienvenido: <?php echo e($nombresusuario); ?></li>
          <li class="nav-item">
            <form method="post" action="<?php echo e($url_base); ?>logout" class="d-inline logout-form">
              <input type="hidden" name="_token" value="<?php echo e(App\Core\Csrf::token()); ?>">
              <button type="submit" class="nav-link btn btn-link p-0">Cerrar Sesión</button>
            </form>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <main>
    <?php $flashSuccess = App\Core\Flash::get('success'); ?>
    <?php $flashError = App\Core\Flash::get('error'); ?>
    <?php if ($flashSuccess !== null) { ?>
      <script>
        Swal.fire({
          icon: "success",
          title: <?php echo json_encode($flashSuccess, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>
        });
      </script>
    <?php } elseif ($flashError !== null) { ?>
      <script>
        Swal.fire({
          icon: "error",
          title: <?php echo json_encode($flashError, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>
        });
      </script>
    <?php } ?>
