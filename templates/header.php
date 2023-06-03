<?php 
  session_start();
  $url_base="http://localhost/proyecto/";

  // Verificar si el usuario ha iniciado sesión
  $nombresusuario = isset($_SESSION['Nombres']) ? $_SESSION['Nombres'] : '';
  $usuarioVerificado = isset($_SESSION['usuario']) ? $_SESSION['usuario'] : '';
  if (!isset($_SESSION['logueado'])) {
    header("Location:".$url_base."login.php");
  }
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
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/"
    crossorigin="anonymous"></script>
    <link href="https://tresplazas.com/web/img/big_punto_de_venta.png" rel="shortcut icon">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }

      /* GLOBAL STYLES
      --------------------------------------------- */
      /* Padding below the footer and lighter body text */
      
      body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
      }

      main {
        flex-grow: 1;
      }

      /* CUSTOMIZE THE CAROUSEL
      -------------------------------------------- */

      /* Carousel base class */
      .carousel {
        margin-bottom: 4rem;
      }

      /* Since positioning the image, we need to help out the caption */
      .carousel-caption {
        bottom: 3rem;
        z-index: 10;
      }

      /* Declare heights because of positioning of img element */
      .carousel-item {
        height: 35rem;
      }

      .carousel-item>img {
        position: absolute;
        top: 0;
        left: 0;
        min-width: 100%;
        height: 35rem;
      }


        /* MARKETING CONTENT
      -------------------------------------------------- */

      /* Center align the text within the three columns below the carousel */
      .marketing .col-lg-4 {
        margin-bottom: 1.5rem;
        text-align: center;
      }

      .marketing h2 {
        font-weight: 400;
      }

      .marketing .col-lg-4 p {
        margin-right: .75rem;
        margin-left: .75rem;
      }


        /* Featurettes
      ------------------------- */

      .featurette-divider {
        margin: 5rem 0;
        /* Space out the Bootstrap <hr> more */
      }

      /* Thin out the marketing headings */
      .featurette-heading {
        font-weight: 300;
        line-height: 1;
        letter-spacing: -.05rem;
      }


        /* RESPONSIVE CSS
      -------------------------------------------------- */

      @media (min-width: 40em) {

        /* Bump up size of carousel content */
        .carousel-caption p {
          margin-bottom: 1.25rem;
          font-size: 1.25rem;
          line-height: 1.4;
        }

        .featurette-heading {
          font-size: 50px;
        }
      }

      @media (min-width: 62em) {
        .featurette-heading {
          margin-top: 7rem;
        }
      }

      #barra_navegacion {
        display: flex;
        justify-content: space-between;
      }

      .swal2-confirm {
        margin-right: 20px;
      }

      .swal2-cancel {
        margin-left: 20px;
      }
    </style>
</head>

<body>
  <header>
    <nav class="navbar navbar-expand navbar-dark fixed-top bg-dark">
      <div class="container-fluid" id="barra_navegacion">
        <ul class="navbar-nav mr-auto mb-2 mb-md-0">
          <div>
            <a class="nav-link active" href="<?php echo $url_base;?>">
              <img src="https://images.unsplash.com/photo-1641846948845-60e99fa0f072?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1229&q=80"
              alt="Bootstrap" width="30" height="25" class="d-inline-block align-text-top rounded-circle"> Proyecto
            </a>
          </div>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?php echo $url_base;?>">Inicio</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="<?php echo $url_base;?>secciones/conductores/">Conductores</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $url_base;?>secciones/propietarios/">Propietarios</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $url_base;?>secciones/taxis/">Taxis</a>
          </li>
          <?php if ($usuarioVerificado == 'Administrador') { ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo $url_base;?>secciones/usuarios/">Usuarios</a>
            </li>
          <?php } ?>
        </ul>
        <ul class="navbar-nav">
          <div class="text-white p-2">
            Bienvenido: <?php echo $nombresusuario; ?>
          </div>
          <li class="nav-item">
            <a class="nav-link" href="<?php echo $url_base;?>controller/cerrar_sesion.php">Cerrar Sesión</a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <main>
  <?php if (isset($_GET['mensaje'])) { ?>
    <script>
      Swal.fire({icon:"success", title:"<?php echo $_GET['mensaje']; ?>"});
    </script>
  <?php } ?>