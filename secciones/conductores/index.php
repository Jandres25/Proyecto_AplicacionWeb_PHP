<?php 
    include("../../model/bd.php");

    if (isset($_GET['txtID'])) {
        $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

        $sentencia = $conexion -> prepare("DELETE FROM `conductores` WHERE ID=:ID");
        $sentencia -> bindParam(":ID", $txtID);
        $sentencia -> execute();
        $mensaje = "Registro Eliminado";
        header("Location:index.php?mensaje=".$mensaje);
    }

    $sentencia = $conexion -> prepare("SELECT * FROM `conductores`");
    $sentencia -> execute();
    $lista_conductores = $sentencia -> fetchAll(PDO::FETCH_ASSOC);
?>

<?php include("../../templates/header.php"); ?>    
    <section style="margin-top: 5%">
        <div class="container mt-5">
            <h1>Lista de conductores</h1>
            <div class="card mb-5">
                <div class="card-header">
                    <a name="" id="" class="btn btn-primary" href="crear.php" role="button">Crear registro</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive-sm">
                        <table class="table table-hover" id="tabla_id">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Nombres del conductor</th>
                                    <th scope="col">Teléfono</th>
                                    <th scope="col">Placa</th>
                                    <th scope="col">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($lista_conductores as $registro) { ?>
                                    <tr>
                                        <td scope="row"><?php echo $registro['ID']; ?></td>
                                        <td><?php echo $registro['Nombres']; ?></td>
                                        <td><?php echo $registro['Telefono']; ?></td>
                                        <td><?php echo $registro['Placa']; ?></td>
                                        <td>
                                            <a name="btneditar" id="btneditar" class="btn btn-outline-info" href="editar.php?txtID=<?php echo $registro['ID']; ?>" role="button">Editar</a>
                                            <a class="btn btn-outline-danger" href="javascript:borrar(<?php echo $registro['ID'];?>);" role="button">Eliminar</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php include("../../templates/footer.php"); ?>