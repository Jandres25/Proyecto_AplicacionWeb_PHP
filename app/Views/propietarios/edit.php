<section style="margin-top: 6%">
    <div class="container mt-5">
        <div class="card mb-5">
            <div class="card-header">
                Datos del propietario
            </div>
            <div class="card-body">
                <?php if (!empty($error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo e($error); ?>
                    </div>
                <?php } ?>
                <form action="/Proyecto_AplicacionWeb_PHP/index.php?route=/propietarios/editar" method="post">
                    <input type="hidden" name="_token" value="<?php echo e(\App\Core\Csrf::token()); ?>">
                    <input type="hidden" name="id" value="<?php echo e($propietario['Idpropietario']); ?>">
                    <div class="mb-3">
                        <label for="txtID" class="form-label">ID</label>
                        <input type="text" value="<?php echo e($propietario['Idpropietario']); ?>" class="form-control" readonly name="txtID" id="txtID">
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Propietario</label>
                        <input type="text" value="<?php echo e($propietario['Nombre']); ?>" class="form-control" name="nombre" id="nombre">
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input type="text" value="<?php echo e($propietario['Telefono']); ?>" class="form-control" name="telefono" id="telefono">
                    </div>
                    <button type="submit" class="btn btn-outline-success">Actualizar</button>
                    <a class="btn btn-outline-primary" href="/Proyecto_AplicacionWeb_PHP/index.php?route=/propietarios" role="button">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
