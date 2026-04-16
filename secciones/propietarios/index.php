<?php
include("../../model/bd.php");
App\Core\Auth::requireLogin();

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    App\Core\Csrf::validateOrFail((string) ($_POST['_token'] ?? ''));
    $txtID = (string) ($_POST['txtID'] ?? '');

    $sentencia = $conexion->prepare("DELETE FROM `propietarios` WHERE Idpropietario=:Idpropietario");
    $sentencia->bindParam(":Idpropietario", $txtID);
    $sentencia->execute();
    $mensaje = "Registro Eliminado";
    header("Location:index.php?mensaje=" . $mensaje);
    exit;
}

$sentencia = $conexion->prepare("SELECT * FROM `propietarios`");
$sentencia->execute();
$lista_propietarios = $sentencia->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include("../../templates/header.php"); ?>
<section style="margin-top: 5%">
    <div class="container mt-5">
        <h1>Lista de propietarios</h1>
        <div class="card mb-5">
            <div class="card-header">
                <a name="" id="" class="btn btn-outline-primary" href="crear.php" role="button">Crear registro</a>
            </div>
            <div class="card-body">
                <div class="table-responsive-sm">
                    <table class="table table-hover" id="tabla_id">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombres del propietario</th>
                                <th scope="col">Teléfono</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista_propietarios as $registro) { ?>
                                <tr>
                                    <td scope="row"><?php echo e($registro['Idpropietario']); ?></td>
                                    <td><?php echo e($registro['Nombre']); ?></td>
                                    <td><?php echo e($registro['Telefono']); ?></td>
                                    <td>
                                        <a name="btneditar" id="btneditar" class="btn btn-outline-info" href="editar.php?txtID=<?php echo e($registro['Idpropietario']); ?>" role="button">Editar</a>
                                        <?php $deleteFormId = 'delete-propietario-' . $registro['Idpropietario']; ?>
                                        <form id="<?php echo e($deleteFormId); ?>" method="post" action="" class="d-inline">
                                            <input type="hidden" name="_token" value="<?php echo e(App\Core\Csrf::token()); ?>">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="txtID" value="<?php echo e($registro['Idpropietario']); ?>">
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
