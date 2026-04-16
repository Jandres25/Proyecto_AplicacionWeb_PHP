<section style="margin-top: 5%">
    <div class="container mt-5">
        <h1>Lista de propietarios</h1>
        <div class="card mb-5">
            <div class="card-header">
                <a class="btn btn-outline-primary" href="<?php echo e(app_url('/propietarios/crear')); ?>" role="button">Crear registro</a>
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
                                        <a class="btn btn-outline-info" href="<?php echo e(app_url('/propietarios/editar')); ?>?id=<?php echo e($registro['Idpropietario']); ?>" role="button">Editar</a>
                                        <?php $deleteFormId = 'delete-propietario-new-' . $registro['Idpropietario']; ?>
                                        <form id="<?php echo e($deleteFormId); ?>" method="post" action="<?php echo e(app_url('/propietarios/eliminar')); ?>" class="d-inline">
                                            <input type="hidden" name="_token" value="<?php echo e(\App\Core\Csrf::token()); ?>">
                                            <input type="hidden" name="id" value="<?php echo e($registro['Idpropietario']); ?>">
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
