<?php 
    include("../../model/bd.php");

    if ($_POST) {
        $modelo = (isset($_POST['modelo']) ? $_POST['modelo'] : '');
        $marca = (isset($_POST['marca']) ? $_POST['marca'] : '');
        $propietario = (isset($_POST['propietario']) ? $_POST['propietario'] : '');

        $sentencia = $conexion -> prepare("INSERT INTO `taxis` (Placa, Modelo, Marca, Idpropietario)
        VALUES(null, :Modelo, :Marca, :Idpropietario)");

        $sentencia -> bindParam(":Modelo", $modelo);
        $sentencia -> bindParam(":Marca", $marca);
        $sentencia -> bindParam(":Idpropietario", $propietario);
        $sentencia -> execute();
        $mensaje = "Registro Agregado";
        header("location: index.php?mensaje=".$mensaje);
    }

    $sentencia = $conexion -> prepare("SELECT * FROM `propietarios`");
    $sentencia -> execute();
    $lista_propietarios = $sentencia -> fetchAll(PDO::FETCH_ASSOC);
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
                          <label for="modelo" class="form-label">Modelo</label>
                          <input type="text"
                            class="form-control" name="modelo" id="modelo" aria-describedby="helpId" placeholder="Ejemplo: BMW">
                          <small id="helpId" class="form-text text-muted">Escriba el modelo del taxi</small>
                        </div>
                        <div class="mb-3">
                          <label for="marca" class="form-label">Marca</label>
                          <input type="text"
                            class="form-control" name="marca" id="marca" aria-describedby="helpId" placeholder="Ejemplo: Toyota">
                          <small id="helpId" class="form-text text-muted">Escriba la marca del taxi</small>
                        </div>
                        <div class="mb-3">
                            <label for="propietario" class="form-label">Propietario</label>
                            <select class="form-select form-select-sm" name="propietario" id="propietario">
                                <option selected>Selecciona un propietario</option>
                                <?php foreach($lista_propietarios as $registro) { ?>
                                    <option value="<?php echo $registro['Idpropietario'];?>"><?php echo $registro['Nombre'];?></option>
                                <?php } ?>
                            </select>
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