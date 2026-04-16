<section style="margin-top: 5%">
    <div class="container mt-5">
        <h1>Lista de taxis</h1>
        <div class="card mb-5">
            <div class="card-header">
                <a class="btn btn-outline-primary" href="/Proyecto_AplicacionWeb_PHP/index.php?route=/taxis/crear" role="button">Crear registro</a>
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
                                    <td><?php echo e($registro['Modelo']); ?></td>
                                    <td><?php echo e($registro['Marca']); ?></td>
                                    <td><?php echo e($registro['propietario']); ?></td>
                                    <td>
                                        <a class="btn btn-outline-info" href="/Proyecto_AplicacionWeb_PHP/index.php?route=/taxis/editar&placa=<?php echo e($registro['Placa']); ?>" role="button">Editar</a>
                                        <?php $deleteFormId = 'delete-taxi-new-' . $registro['Placa']; ?>
                                        <form id="<?php echo e($deleteFormId); ?>" method="post" action="/Proyecto_AplicacionWeb_PHP/index.php?route=/taxis/eliminar" class="d-inline">
                                            <input type="hidden" name="_token" value="<?php echo e(\App\Core\Csrf::token()); ?>">
                                            <input type="hidden" name="placa" value="<?php echo e($registro['Placa']); ?>">
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
