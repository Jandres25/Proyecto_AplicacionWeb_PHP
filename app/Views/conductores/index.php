<section style="margin-top: 5%">
    <div class="container mt-5">
        <h1>Lista de conductores</h1>
        <div class="card mb-5">
            <div class="card-header">
                <a class="btn btn-outline-primary" href="/Proyecto_AplicacionWeb_PHP/index.php?route=/conductores/crear" role="button">Crear registro</a>
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
                            <?php foreach ($lista_conductores as $registro) { ?>
                                <tr>
                                    <td scope="row"><?php echo e($registro['ID']); ?></td>
                                    <td><?php echo e($registro['Nombres']); ?></td>
                                    <td><?php echo e($registro['Telefono']); ?></td>
                                    <td><?php echo e($registro['Placa']); ?></td>
                                    <td>
                                        <a class="btn btn-outline-info" href="/Proyecto_AplicacionWeb_PHP/index.php?route=/conductores/editar&id=<?php echo e($registro['ID']); ?>" role="button">Editar</a>
                                        <?php $deleteFormId = 'delete-conductor-new-' . $registro['ID']; ?>
                                        <form id="<?php echo e($deleteFormId); ?>" method="post" action="/Proyecto_AplicacionWeb_PHP/index.php?route=/conductores/eliminar" class="d-inline">
                                            <input type="hidden" name="_token" value="<?php echo e(\App\Core\Csrf::token()); ?>">
                                            <input type="hidden" name="id" value="<?php echo e($registro['ID']); ?>">
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
