<div class="container pt-5 mt-4 pb-4 mb-3">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-dark">
                <i class="bi bi-pencil-square me-2"></i>Editar Usuario
            </h1>
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/')); ?>" class="text-decoration-none"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/usuarios')); ?>" class="text-decoration-none"><i class="bi bi-people-fill"></i> Usuarios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="<?php echo e(app_url('/usuarios/editar')); ?>" method="post">
                <input type="hidden" name="_token" value="<?php echo e(\App\Core\Csrf::token()); ?>">
                <input type="hidden" name="id" value="<?php echo e($usuario['ID']); ?>">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="bi bi-person-gear me-2"></i>Modificar Registro
                        </h5>
                        <span class="badge bg-light text-primary border px-3 py-2">ID: <?= e($usuario['ID']); ?></span>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div><?php echo e($error); ?></div>
                            </div>
                        <?php endif; ?>



                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombres" class="form-label fw-bold text-muted small text-uppercase">Nombres</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" value="<?php echo e($usuario['Nombres']); ?>" class="form-control" name="nombres" id="nombres" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label fw-bold text-muted small text-uppercase">Apellidos</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" value="<?php echo e($usuario['Apellidos']); ?>" class="form-control" name="apellidos" id="apellidos" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label fw-bold text-muted small text-uppercase">Nombre de Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-at"></i></span>
                                    <input type="text" value="<?php echo e($usuario['Usuario']); ?>" class="form-control" name="usuario" id="usuario" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="clave" class="form-label fw-bold text-muted small text-uppercase">Nueva Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control" name="clave" id="clave" placeholder="Dejar vacío para conservar">
                                </div>
                                <div class="form-text small">Solo rellena este campo si deseas cambiar la contraseña actual.</div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="correo" class="form-label fw-bold text-muted small text-uppercase">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" value="<?php echo e($usuario['Correo']); ?>" class="form-control" name="correo" id="correo" required>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 col-sm-auto mb-2 mb-sm-0">
                                <a class="btn btn-light border shadow-sm w-100" href="<?= e(app_url('/usuarios')); ?>" role="button">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                            </div>
                            <div class="col-12 col-sm-auto">
                                <button type="submit" class="btn btn-success shadow-sm w-100 text-white">
                                    <i class="bi bi-save"></i> Actualizar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>