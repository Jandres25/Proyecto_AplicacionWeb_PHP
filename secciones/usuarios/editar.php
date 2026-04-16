<?php
include("../../model/bd.php");
App\Core\Auth::requireAdmin();

if (isset($_GET["txtID"])) {
  $txtID = (isset($_GET["txtID"])) ? $_GET["txtID"] : "";

  $sentencia = $conexion->prepare("SELECT * FROM `usuarios` WHERE ID=:ID");
  $sentencia->bindParam(":ID", $txtID);
  $sentencia->execute();

  $registro = $sentencia->fetch(PDO::FETCH_LAZY);

  $nombres = $registro["Nombres"];
  $apellidos = $registro["Apellidos"];
  $usuario = $registro["Usuario"];
  $clave = '';
  $correo = $registro["Correo"];
}

if ($_POST) {
  App\Core\Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));
  $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : '';
  $nombres = (isset($_POST['nombres'])) ? $_POST['nombres'] : '';
  $apellidos = (isset($_POST['apellidos'])) ? $_POST['apellidos'] : '';
  $usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
  $clave = (isset($_POST['clave'])) ? $_POST['clave'] : '';
  $correo = (isset($_POST['correo'])) ? $_POST['correo'] : '';

  if ($clave !== '') {
    $hashedPassword = password_hash($clave, PASSWORD_DEFAULT);
    $sentencia = $conexion->prepare("UPDATE `usuarios` SET Nombres = :nombres, Apellidos = :apellidos, Usuario = :usuario, Clave = :clave, Correo = :correo WHERE ID = :ID");
    $sentencia->bindParam(':clave', $hashedPassword);
  } else {
    $sentencia = $conexion->prepare("UPDATE `usuarios` SET Nombres = :nombres, Apellidos = :apellidos, Usuario = :usuario, Correo = :correo WHERE ID = :ID");
  }

  $sentencia->bindParam(':nombres', $nombres);
  $sentencia->bindParam(':apellidos', $apellidos);
  $sentencia->bindParam(':usuario', $usuario);
  $sentencia->bindParam(':correo', $correo);
  $sentencia->bindParam(':ID', $txtID);
  $sentencia->execute();
  $mensaje = "Registro Actualizado";
  header("Location: index.php?mensaje=" . $mensaje);
  exit;
}
?>

<?php include("../../templates/header.php"); ?>
<section style="margin-top: 6%">
  <div class="container mt-5">
    <div class="card mb-5">
      <div class="card-header">
        Datos del usuario
      </div>
      <div class="card-body">
        <form action="" method="post">
          <input type="hidden" name="_token" value="<?php echo e(App\Core\Csrf::token()); ?>">
          <div class="mb-3">
            <label for="txtID" class="form-label"><i class="fa fa-id-badge" aria-hidden="true"></i>ID</label>
            <input type="text" value="<?php echo e($txtID); ?>" class="form-control" readonly name="txtID" id="txtID">
          </div>
          <div class="mb-3">
            <label for="nombres" class="form-label">Nombres</label>
            <input type="text" value="<?php echo e($nombres); ?>" class="form-control" name="nombres" id="nombres">
          </div>
          <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos</label>
            <input type="text" value="<?php echo e($apellidos); ?>" class="form-control" name="apellidos" id="apellidos">
          </div>
          <div class="mb-3">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" value="<?php echo e($usuario); ?>" class="form-control" name="usuario" id="usuario">
          </div>
          <div class="mb-3">
            <label for="clave" class="form-label">Clave (dejar vacío para conservar)</label>
            <input type="password" value="" class="form-control" name="clave" id="clave">
          </div>
          <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="text" value="<?php echo e($correo); ?>" class="form-control" name="correo" id="correo">
          </div>
          <button type="submit" class="btn btn-outline-success">Actualizar</button>
          <a name="" id="" class="btn btn-outline-primary" href="index.php" role="button">Cancelar</a>
        </form>
      </div>
      <div class="card-footer text-muted">
      </div>
    </div>
  </div>
</section>
<?php include("../../templates/footer.php"); ?>
