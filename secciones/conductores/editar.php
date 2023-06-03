<?php 
    include("../../model/bd.php");

    if (isset($_GET["txtID"])) {
        $txtID = (isset($_GET["txtID"])) ? $_GET["txtID"] : "";

        $sentencia = $conexion -> prepare("SELECT * FROM `conductores` WHERE ID=:ID");
        $sentencia -> bindParam(":ID", $txtID);
        $sentencia -> execute();

        $registro = $sentencia -> fetch(PDO::FETCH_LAZY);

        $nombres = $registro["Nombres"];
        $telefono = $registro["Telefono"];
        $placa = $registro["Placa"];

        $sentencia = $conexion -> prepare("SELECT * FROM `taxis`");
        $sentencia -> execute();
        $lista_taxis = $sentencia -> fetchAll(PDO::FETCH_ASSOC);
    }

    if ($_POST) {
        $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : '';
        $nombres = (isset($_POST['nombre'])) ? $_POST['nombre'] : '';
        $telefono = (isset($_POST['telefono'])) ? $_POST['telefono'] : '';
        $placa = (isset($_POST['placa'])) ? $_POST['placa'] : '';

        $sentencia = $conexion -> prepare("UPDATE `conductores` SET Nombres = :nombre, Telefono = :telefono, Placa = :placa WHERE ID = :ID");

        $sentencia -> bindParam(':nombre', $nombres);
        $sentencia -> bindParam(':telefono', $telefono);
        $sentencia -> bindParam(':placa', $placa);
        $sentencia -> bindParam(':ID', $txtID);
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
                    Datos del conductor
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
                        <div class="mb-3">
                            <label for="placa" class="form-label">Placa</label>
                            <select class="form-select form-select-sm" name="placa" id="placa">
                                <option selected>Selecciona una placa</option>
                                <?php foreach($lista_taxis as $registro) { ?>
                                    <option <?php echo($placa == $registro['Placa'])?'selected':'' ?> value="<?php echo $registro['Placa'];?>"><?php echo $registro['Placa']?></option>
                                <?php } ?>
                            </select>
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