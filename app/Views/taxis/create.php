<section style="margin-top: 6%">
    <div class="container mt-5">
        <div class="card mb-5">
            <div class="card-header">
                Datos del taxi
            </div>
            <div class="card-body">
                <?php if (!empty($error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo e($error); ?>
                    </div>
                <?php } ?>
                <form action="<?php echo e(app_url('/taxis/crear')); ?>" method="post">
                    <input type="hidden" name="_token" value="<?php echo e(\App\Core\Csrf::token()); ?>">
                    <div class="mb-3">
                        <label for="modelo" class="form-label">Modelo</label>
                        <input type="text" class="form-control" name="modelo" id="modelo" value="<?php echo e($old['modelo'] ?? ''); ?>" placeholder="Ejemplo: BMW">
                        <small class="form-text text-muted">Escriba el modelo del taxi</small>
                    </div>
                    <div class="mb-3">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" class="form-control" name="marca" id="marca" value="<?php echo e($old['marca'] ?? ''); ?>" placeholder="Ejemplo: Toyota">
                        <small class="form-text text-muted">Escriba la marca del taxi</small>
                    </div>
                    <div class="mb-3">
                        <label for="propietario" class="form-label">Propietario</label>
                        <select class="form-select form-select-sm" name="propietario" id="propietario">
                            <option value="">Selecciona un propietario</option>
                            <?php foreach ($owners as $owner) { ?>
                                <option value="<?php echo e($owner['Idpropietario']); ?>" <?php echo ((string) ($old['propietario'] ?? '') === (string) $owner['Idpropietario']) ? 'selected' : ''; ?>>
                                    <?php echo e($owner['Nombre']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-success">Agregar</button>
                    <a class="btn btn-outline-primary" href="<?php echo e(app_url('/taxis')); ?>" role="button">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
