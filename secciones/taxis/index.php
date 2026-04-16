<?php
include("../../model/bd.php");
App\Core\Auth::requireLogin();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    App\Core\Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));
    $txtID = (string) ($_POST['txtID'] ?? '');

    $sentencia = $conexion->prepare("DELETE FROM `taxis` WHERE Placa=:Placa");
    $sentencia->bindParam(":Placa", $txtID);
    $sentencia->execute();
    $mensaje = "Registro Eliminado";
    header("Location:index.php?mensaje=" . $mensaje);
    exit;
}

$sentencia = $conexion->prepare("SELECT t.*, p.Nombre as propietario FROM `taxis` t INNER JOIN `propietarios` p ON p.Idpropietario = t.Idpropietario");
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
                                    <td><?php echo e($registro['Placa']); ?></td>
                                    <td scope="row"><?php echo e($registro['Modelo']); ?></td>
                                    <td><?php echo e($registro['Marca']); ?></td>
                                    <td><?php echo e($registro['propietario']); ?></td>
                                    <td>
                                        <a name="btneditar" id="btneditar" class="btn btn-outline-info" href="editar.php?txtID=<?php echo e($registro['Placa']); ?>" role="button">Editar</a>
                                        <?php $deleteFormId = 'delete-taxi-' . $registro['Placa']; ?>
                                        <form id="<?php echo e($deleteFormId); ?>" method="post" action="" class="d-inline">
                                            <input type="hidden" name="_token" value="<?php echo e(App\Core\Csrf::token()); ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="txtID" value="<?php echo e($registro['Placa']); ?>">
                                            <button type="button" class="btn btn-outline-danger" onclick="borrar('<?php echo e($deleteFormId); ?>')">Eliminar</button>
                                        </form>
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
