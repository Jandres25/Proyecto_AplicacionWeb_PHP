<section style="margin-top: 5%">
    <div class="container mt-5">
        <h1>Lista de usuarios</h1>
        <div class="card mb-5">
            <div class="card-header">
                <a class="btn btn-outline-primary" href="<?php echo e(app_url('/usuarios/crear')); ?>" role="button">Crear registro</a>
            </div>
            <div class="card-body">
                <div class="table-responsive-sm">
                    <table class="table table-hover" id="tabla_id">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nombres del usuario</th>
                                <th scope="col">Apellidos del usuario</th>
                                <th scope="col">Usuario</th>
                                <th scope="col">Clave</th>
                                <th scope="col">Correo</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lista_usuarios as $registro) { ?>
                                <tr>
                                    <td><?php echo e($registro['ID']); ?></td>
                                    <td><?php echo e($registro['Nombres']); ?></td>
                                    <td><?php echo e($registro['Apellidos']); ?></td>
                                    <td><?php echo e($registro['Usuario']); ?></td>
                                    <td><?php echo e(str_repeat('*', 8)); ?></td>
                                    <td><?php echo e($registro['Correo']); ?></td>
                                    <td>
                                        <a class="btn btn-outline-info" href="<?php echo e(app_url('/usuarios/editar')); ?>?id=<?php echo e($registro['ID']); ?>" role="button">Editar</a>
                                        <?php $deleteFormId = 'delete-usuario-new-' . $registro['ID']; ?>
                                        <form id="<?php echo e($deleteFormId); ?>" method="post" action="<?php echo e(app_url('/usuarios/eliminar')); ?>" class="d-inline">
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
