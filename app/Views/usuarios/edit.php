<section style="margin-top: 6%">
    <div class="container mt-5">
        <div class="card mb-5">
            <div class="card-header">
                Datos del usuario
            </div>
            <div class="card-body">
                <?php if (!empty($error)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo e($error); ?>
                    </div>
                <?php } ?>
                <form action="/Proyecto_AplicacionWeb_PHP/index.php?route=/usuarios/editar" method="post">
                    <input type="hidden" name="_token" value="<?php echo e(\App\Core\Csrf::token()); ?>">
                    <input type="hidden" name="id" value="<?php echo e($usuario['ID']); ?>">
                    <div class="mb-3">
                        <label for="txtID" class="form-label">ID</label>
                        <input type="text" value="<?php echo e($usuario['ID']); ?>" class="form-control" readonly name="txtID" id="txtID">
                    </div>
                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" value="<?php echo e($usuario['Nombres']); ?>" class="form-control" name="nombres" id="nombres">
                    </div>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" value="<?php echo e($usuario['Apellidos']); ?>" class="form-control" name="apellidos" id="apellidos">
                    </div>
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" value="<?php echo e($usuario['Usuario']); ?>" class="form-control" name="usuario" id="usuario">
                    </div>
                    <div class="mb-3">
                        <label for="clave" class="form-label">Clave (dejar vacío para conservar)</label>
                        <input type="password" class="form-control" name="clave" id="clave">
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" value="<?php echo e($usuario['Correo']); ?>" class="form-control" name="correo" id="correo">
                    </div>
                    <button type="submit" class="btn btn-outline-success">Actualizar</button>
                    <a class="btn btn-outline-primary" href="/Proyecto_AplicacionWeb_PHP/index.php?route=/usuarios" role="button">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
