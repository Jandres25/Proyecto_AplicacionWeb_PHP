<section style="margin-top: 6%">
    <div class="container mt-5">
        <div class="card mb-5">
            <div class="card-header">
                Datos del conductor
            </div>
            <div class="card-body">
                <?php if (!empty($error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo e($error); ?>
                    </div>
                <?php } ?>
                <form action="/Proyecto_AplicacionWeb_PHP/index.php?route=/conductores/editar" method="post">
                    <input type="hidden" name="_token" value="<?php echo e(\App\Core\Csrf::token()); ?>">
                    <input type="hidden" name="id" value="<?php echo e($conductor['ID']); ?>">
                    <div class="mb-3">
                        <label for="txtID" class="form-label">ID</label>
                        <input type="text" value="<?php echo e($conductor['ID']); ?>" class="form-control" readonly name="txtID" id="txtID">
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Conductor</label>
                        <input type="text" value="<?php echo e($conductor['Nombres']); ?>" class="form-control" name="nombre" id="nombre">
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Telefono</label>
                        <input type="text" value="<?php echo e($conductor['Telefono']); ?>" class="form-control" name="telefono" id="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="placa" class="form-label">Placa</label>
                        <select class="form-select form-select-sm" name="placa" id="placa">
                            <option value="">Selecciona una placa</option>
                            <?php foreach ($taxis as $taxi) { ?>
                                <option value="<?php echo e($taxi['Placa']); ?>" <?php echo ((string) $conductor['Placa'] === (string) $taxi['Placa']) ? 'selected' : ''; ?>>
                                    <?php echo e($taxi['Placa']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-outline-success">Actualizar</button>
                    <a class="btn btn-outline-primary" href="/Proyecto_AplicacionWeb_PHP/index.php?route=/conductores" role="button">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
