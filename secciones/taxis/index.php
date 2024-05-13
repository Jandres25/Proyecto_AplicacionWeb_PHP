<?php
include("../../model/bd.php");

if (isset($_GET['txtID'])) {
    $txtID = (isset($_GET['txtID'])) ? $_GET['txtID'] : "";

    $sentencia = $conexion->prepare("DELETE FROM `taxis` WHERE Placa=:Placa");
    $sentencia->bindParam(":Placa", $txtID);
    $sentencia->execute();
    $mensaje = "Registro Eliminado";
    header("Location:index.php?mensaje=" . $mensaje);
}

$sentencia = $conexion->prepare("SELECT *,(SELECT Nombre FROM `propietarios` WHERE Idpropietario = Idpropietario limit 1) as propietario FROM `taxis`");
$sentencia->execute();
$lista_taxis = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include("../../templates/header.php"); ?>
<section style="margin-top: 5%">
    <div class="container mt-5">
        <h1>Lista de taxis</h1>
        <div class="card mb-5">
            <div class="card-header">
                <a name="" id="" class="btn btn-outline-primary" href="crear.php" role="button">Crear registro</a>
            </div>
            <div class="card-body">
                <div class="table-responsive-sm">
                    <table class="table table-hover" id="tabla_id">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Placa</th>
                                <th scope="col">Modelo</th>
                                <th scope="col">Marca</th>
                                <th scope="col">Propietario</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista_taxis as $registro) { ?>
                                <tr>
                                    <td><?php echo $registro['Placa']; ?></td>
                                    <td scope="row"><?php echo $registro['Modelo']; ?></td>
                                    <td><?php echo $registro['Marca']; ?></td>
                                    <td><?php echo $registro['propietario']; ?></td>
                                    <td>
                                        <a name="btneditar" id="btneditar" class="btn btn-outline-info" href="editar.php?txtID=<?php echo $registro['Placa']; ?>" role="button">Editar</a>
                                        <a class="btn btn-outline-danger" href="javascript:borrar(<?php echo $registro['Placa']; ?>);" role="button">Eliminar</a>
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