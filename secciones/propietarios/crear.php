<?php 
    include("../../model/bd.php");

    if ($_POST) {
        $nombres = (isset($_POST['nombre']) ? $_POST['nombre'] : '');
        $telefono = (isset($_POST['telefono']) ? $_POST['telefono'] : '');

        $sentencia = $conexion -> prepare("INSERT INTO `propietarios` (Idpropietario, Nombre, Telefono)
        VALUES(null, :Nombre, :Telefono)");

        $sentencia -> bindParam(":Nombre", $nombres);
        $sentencia -> bindParam(":Telefono", $telefono);
        $sentencia -> execute();
        $mensaje = "Registro Agregado";
        header("location: index.php?mensaje=".$mensaje);
    }
?>

<?php include("../../templates/header.php"); ?>
    <section style="margin-top: 6%">
        <div class="container mt-5">
            <div class="card mb-5">
                <div class="card-header">
                    Datos del propietario
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="mb-3">
                          <label for="nombre" class="form-label">Nombres del propietario</label>
                          <input type="text"
                            class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="Ejemplo: Juan">
                          <small id="helpId" class="form-text text-muted">Escriba los nombres del propietario</small>
                        </div>
                        <div class="mb-3">
                          <label for="telefono" class="form-label">Telefono</label>
                          <input type="text"
                            class="form-control" name="telefono" id="telefono" aria-describedby="helpId" placeholder="Ejemplo: 75555555">
                          <small id="helpId" class="form-text text-muted">Escriba el nombre del propietario</small>
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