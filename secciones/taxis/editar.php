<?php 
    include("../../model/bd.php");

    if (isset($_GET["txtID"])) {
        $txtID = (isset($_GET["txtID"])) ? $_GET["txtID"] : "";

        $sentencia = $conexion -> prepare("SELECT * FROM `taxis` WHERE Placa=:Placa");
        $sentencia -> bindParam(":Placa", $txtID);
        $sentencia -> execute();

        $registro = $sentencia -> fetch(PDO::FETCH_LAZY);

        $modelo = $registro["Modelo"];
        $marca = $registro["Marca"];
        $propietario = $registro["Idpropietario"];

        $sentencia = $conexion -> prepare("SELECT * FROM `propietarios`");
        $sentencia -> execute();
        $lista_propietarios = $sentencia -> fetchAll(PDO::FETCH_ASSOC);
    }

    if ($_POST) {
        $txtID = (isset($_POST['txtID'])) ? $_POST['txtID'] : '';
        $modelo = (isset($_POST['modelo'])) ? $_POST['modelo'] : '';
        $marca = (isset($_POST['marca'])) ? $_POST['marca'] : '';
        $propietario = (isset($_POST['propietario'])) ? $_POST['propietario'] : '';

        $sentencia = $conexion -> prepare("UPDATE `taxis` SET Modelo = :modelo, Marca = :marca, Idpropietario = :propietario WHERE Placa = :Placa");

        $sentencia -> bindParam(':modelo', $modelo);
        $sentencia -> bindParam(':marca', $marca);
        $sentencia -> bindParam(':propietario', $propietario);
        $sentencia -> bindParam(':Placa', $txtID);
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
                    Datos del taxi
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="mb-3">
                          <label for="txtID" class="form-label"><i class="fa fa-id-badge" aria-hidden="true"></i>ID</label>
                          <input type="text" value="<?php echo $txtID;?>" class="form-control" readonly name="txtID" id="txtID">
                        </div>
                        <div class="mb-3">
                          <label for="modelo" class="form-label">Modelo</label>
                          <input type="text" value="<?php echo $modelo;?>" class="form-control" name="modelo" id="modelo">
                        </div>
                        <div class="mb-3">
                          <label for="marca" class="form-label">Marca</label>
                          <input type="text" value="<?php echo $marca;?>" class="form-control" name="marca" id="marca">
                        </div>
                        <div class="mb-3">
                            <label for="propietario" class="form-label">Placa</label>
                            <select class="form-select form-select-sm" name="propietario" id="propietario">
                                <option selected>Selecciona una placa</option>
                                <?php foreach($lista_propietarios as $registro) { ?>
                                    <option <?php echo($propietario == $registro['Idpropietario'])?'selected':'' ?> value="<?php echo $registro['Idpropietario'];?>"><?php echo $registro['Nombre']?></option>
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