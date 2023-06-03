<?php 
    include("../../model/bd.php");

    if (isset($_GET["txtID"])) {
        $txtID = (isset($_GET["txtID"])) ? $_GET["txtID"] : "";

        $sentencia = $conexion -> prepare("SELECT * FROM `propietarios` WHERE Idpropietario=:Idpropietario");
        $sentencia -> bindParam(":Idpropietario", $txtID);
        $sentencia -> execute();

        $registro = $sentencia -> fetch(PDO::FETCH_LAZY);

        $nombres = $registro["Nombre"];
        $telefono = $registro["Telefono"];
    }

    if ($_POST) {
        $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : '';
        $nombres = (isset($_POST['nombre'])) ? $_POST['nombre'] : '';
        $telefono = (isset($_POST['telefono'])) ? $_POST['telefono'] : '';

        $sentencia = $conexion -> prepare("UPDATE `propietarios` SET Nombre = :nombre, Telefono = :telefono WHERE Idpropietario = :Idpropietario");

        $sentencia -> bindParam(':nombre', $nombres);
        $sentencia -> bindParam(':telefono', $telefono);
        $sentencia -> bindParam(':Idpropietario', $txtID);
        $sentencia -> execute();
        $mensaje = "Registro Actualizado";
        header("Location: index.php?mensaje=".$mensaje);
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
                          <label for="txtID" class="form-label"><i class="fa fa-id-badge" aria-hidden="true"></i>ID</label>
                          <input type="text" value="<?php echo $txtID;?>" class="form-control" readonly name="txtID" id="txtID">
                        </div>
                        <div class="mb-3">
                          <label for="nombre" class="form-label">Conductor</label>
                          <input type="text" value="<?php echo $nombres;?>" class="form-control" name="nombre" id="nombre">
                        </div>
                        <div class="mb-3">
                          <label for="telefono" class="form-label">Telefono</label>
                          <input type="text" value="<?php echo $telefono;?>" class="form-control" name="telefono" id="telefono">
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