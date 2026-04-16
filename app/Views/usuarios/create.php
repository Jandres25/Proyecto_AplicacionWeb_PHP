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
                <form action="/Proyecto_AplicacionWeb_PHP/index.php?route=/usuarios/crear" method="post">
                    <input type="hidden" name="_token" value="<?php echo e(\App\Core\Csrf::token()); ?>">
                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombres del usuario</label>
                        <input type="text" class="form-control" name="nombres" id="nombres" value="<?php echo e($old['nombres'] ?? ''); ?>" placeholder="Ejemplo: Juan Juanito">
                    </div>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos del usuario</label>
                        <input type="text" class="form-control" name="apellidos" id="apellidos" value="<?php echo e($old['apellidos'] ?? ''); ?>" placeholder="Ejemplo: Perez">
                    </div>
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" class="form-control" name="usuario" id="usuario" value="<?php echo e($old['usuario'] ?? ''); ?>" placeholder="Ejemplo: Juan10">
                    </div>
                    <div class="mb-3">
                        <label for="clave" class="form-label">Clave</label>
                        <input type="password" class="form-control" name="clave" id="clave" placeholder="Ejemplo: password" required>
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label">Correo</label>
                        <input type="email" class="form-control" name="correo" id="correo" value="<?php echo e($old['correo'] ?? ''); ?>" placeholder="Ejemplo: ejemplo@dominio.com">
                    </div>
                    <button type="submit" class="btn btn-outline-success">Agregar</button>
                    <a class="btn btn-outline-primary" href="/Proyecto_AplicacionWeb_PHP/index.php?route=/usuarios" role="button">Cancelar</a>
                </form>
            </div>
        </div>
    </div>
</section>
