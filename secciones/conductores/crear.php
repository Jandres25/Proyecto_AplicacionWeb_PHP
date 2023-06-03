<?php 

    include("../../model/bd.php");

    if ($_POST) {
        $nombres = (isset($_POST['nombre']) ? $_POST['nombre'] : '');
        $telefono = (isset($_POST['telefono']) ? $_POST['telefono'] : '');
        $placa = (isset($_POST['placa']) ? $_POST['placa'] : '');

        $sentencia = $conexion -> prepare("INSERT INTO `conductores` (ID, Nombres, Telefono, Placa)
        VALUES(null, :Nombres, :Telefono, :Placa)");

        $sentencia -> bindParam(":Nombres", $nombres);
        $sentencia -> bindParam(":Telefono", $telefono);
        $sentencia -> bindParam(":Placa", $placa);
        $sentencia -> execute();
        $mensaje = "Registro Agregado";
        header("location: index.php?mensaje=".$mensaje);
    }

    $sentencia = $conexion -> prepare("SELECT * FROM `taxis`");
    $sentencia -> execute();
    $lista_taxis = $sentencia -> fetchAll(PDO::FETCH_ASSOC);
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
                          <label for="nombre" class="form-label">Nombres del conductor</label>
                          <input type="text"
                            class="form-control" name="nombre" id="nombre" aria-describedby="helpId" placeholder="Ejemplo: Juan">
                          <small id="helpId" class="form-text text-muted">Escriba los nombres del conductor</small>
                        </div>
                        <div class="mb-3">
                          <label for="telefono" class="form-label">Telefono</label>
                          <input type="text"
                            class="form-control" name="telefono" id="telefono" aria-describedby="helpId" placeholder="Ejemplo: 75555555">
                          <small id="helpId" class="form-text text-muted">Escriba el nombre del conductor</small>
                        </div>
                        <div class="mb-3">
                            <label for="placa" class="form-label">Placa del taxi</label>
                            <select class="form-select form-select-sm" name="placa" id="placa">
                                <option selected>Selecciona una placa</option>
                                <?php foreach($lista_taxis as $registro) { ?>
                                    <option value="<?php echo $registro['Placa'];?>"><?php echo $registro['Placa'];?></option>
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