<?php
include("../../model/bd.php");

if ($_POST) {
  $nombres = (isset($_POST['nombres']) ? $_POST['nombres'] : '');
  $apellidos = (isset($_POST['apellidos']) ? $_POST['apellidos'] : '');
  $usuario = (isset($_POST['usuario']) ? $_POST['usuario'] : '');
  $clave = (isset($_POST['clave']) ? $_POST['clave'] : '');
  $correo = (isset($_POST['correo']) ? $_POST['correo'] : '');

  $sentencia = $conexion->prepare("INSERT INTO `usuarios` (ID, Nombres, Apellidos, Usuario, Clave, Correo)
        VALUES(null, :Nombres, :Apellidos, :Usuario, :Clave, :Correo)");

  $sentencia->bindParam(":Nombres", $nombres);
  $sentencia->bindParam(":Apellidos", $apellidos);
  $sentencia->bindParam(":Usuario", $usuario);
  $sentencia->bindParam(":Clave", $clave);
  $sentencia->bindParam(":Correo", $correo);
  $sentencia->execute();
  $mensaje = "Registro Agregado";
  header("location: index.php?mensaje=" . $mensaje);
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
          <div class="mb-3">
            <label for="nombres" class="form-label">Nombres del usuario</label>
            <input type="text" class="form-control" name="nombres" id="nombres" aria-describedby="helpId" placeholder="Ejemplo: Juan Juanito">
            <small id="helpId" class="form-text text-muted">Escriba los nombres del usuario</small>
          </div>
          <div class="mb-3">
            <label for="apellidos" class="form-label">Apellidos del usuario</label>
            <input type="text" class="form-control" name="apellidos" id="apellidos" aria-describedby="helpId" placeholder="Ejemplo: Perez">
            <small id="helpId" class="form-text text-muted">Escriba los apellidos del usuario</small>
          </div>
          <div class="mb-3">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" class="form-control" name="usuario" id="usuario" aria-describedby="helpId" placeholder="Ejemplo: Juan10">
            <small id="helpId" class="form-text text-muted">Escriba el nombre de usuario</small>
          </div>
          <div class="mb-3">
            <label for="clave" class="form-label">Clave</label>
            <input type="text" class="form-control" name="clave" id="clave" aria-describedby="helpId" placeholder="Ejemplo: password">
            <small id="helpId" class="form-text text-muted">Escriba la clave de usuario</small>
          </div>
          <div class="mb-3">
            <label for="correo" class="form-label">Correo</label>
            <input type="text" class="form-control" name="correo" id="correo" aria-describedby="helpId" placeholder="Ejemplo: ejemplo@dominio.com">
            <small id="helpId" class="form-text text-muted">Escriba el correo del usuario</small>
          </div>
          <button type="submit" class="btn btn-outline-success">Agregar</button>
          <a name="" id="" class="btn btn-outline-primary" href="index.php" role="button">Cancelar</a>
        </form>
      </div>
      <div class="card-footer text-muted">
      </div>
    </div>
  </div>
</section>
<?php include("../../templates/footer.php"); ?>