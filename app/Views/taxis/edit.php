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
                <form action="/Proyecto_AplicacionWeb_PHP/index.php?route=/taxis/editar" method="post">
                    <input type="hidden" name="_token" value="<?php echo e(\App\Core\Csrf::token()); ?>">
                    <input type="hidden" name="placa" value="<?php echo e($taxi['Placa']); ?>">
                    <div class="mb-3">
                        <label for="txtID" class="form-label">Placa</label>
                        <input type="text" value="<?php echo e($taxi['Placa']); ?>" class="form-control" readonly name="txtID" id="txtID">
                    </div>
                    <div class="mb-3">
                        <label for="modelo" class="form-label">Modelo</label>
                        <input type="text" value="<?php echo e($taxi['Modelo']); ?>" class="form-control" name="modelo" id="modelo">
                    </div>
                    <div class="mb-3">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" value="<?php echo e($taxi['Marca']); ?>" class="form-control" name="marca" id="marca">
                    </div>
                    <div class="mb-3">
                        <label for="propietario" class="form-label">Propietario</label>
                        <select class="form-select form-select-sm" name="propietario" id="propietario">
                            <option value="">Selecciona un propietario</option>
                            <?php foreach ($owners as $owner) { ?>
                                <option value="<?php echo e($owner['Idpropietario']); ?>" <?php echo ((string) $taxi['Idpropietario'] === (string) $owner['Idpropietario']) ? 'selected' : ''; ?>>
                                    <?php echo e($owner['Nombre']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-success">Actualizar</button>
                    <a class="btn btn-outline-primary" href="/Proyecto_AplicacionWeb_PHP/index.php?route=/taxis" role="button">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
