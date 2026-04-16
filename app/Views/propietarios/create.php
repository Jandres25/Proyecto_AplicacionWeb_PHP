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
                <form action="<?php echo e(app_url('/propietarios/crear')); ?>" method="post">
                    <input type="hidden" name="_token" value="<?php echo e(\App\Core\Csrf::token()); ?>">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombres del propietario</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo e($old['nombre'] ?? ''); ?>" placeholder="Ejemplo: Juan">
                        <small class="form-text text-muted">Escriba los nombres del propietario</small>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input type="text" class="form-control" name="telefono" id="telefono" value="<?php echo e($old['telefono'] ?? ''); ?>" placeholder="Ejemplo: 75555555">
                        <small class="form-text text-muted">Escriba el teléfono del propietario</small>
                    </div>
                    <button type="submit" class="btn btn-outline-success">Agregar</button>
                    <a class="btn btn-outline-primary" href="<?php echo e(app_url('/propietarios')); ?>" role="button">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
