<div class="container pt-5 mt-4 pb-4 mb-3">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-dark">
                <i class="bi bi-person-plus-fill me-2"></i>Crear Usuario
            </h1>
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/')); ?>" class="text-decoration-none"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/usuarios')); ?>" class="text-decoration-none"><i class="bi bi-people-fill"></i> Usuarios</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Crear</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="<?= e(app_url('/usuarios/crear')); ?>" method="post">
                <input type="hidden" name="_token" value="<?= e(\App\Core\Csrf::token()); ?>">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="bi bi-card-list me-2"></i>Formulario de Registro
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($error)) : ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div><?= e($error); ?></div>
                            </div>
                        <?php endif; ?>



                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombres" class="form-label fw-bold text-muted small text-uppercase">Nombres</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" name="nombres" id="nombres" value="<?= e($old['nombres'] ?? ''); ?>" placeholder="Ej: Juan Juanito" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="apellidos" class="form-label fw-bold text-muted small text-uppercase">Apellidos</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" class="form-control" name="apellidos" id="apellidos" value="<?= e($old['apellidos'] ?? ''); ?>" placeholder="Ej: Perez" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="usuario" class="form-label fw-bold text-muted small text-uppercase">Nombre de Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-at"></i></span>
                                    <input type="text" class="form-control" name="usuario" id="usuario" value="<?= e($old['usuario'] ?? ''); ?>" placeholder="Ej: juan10" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="clave" class="form-label fw-bold text-muted small text-uppercase">Contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" name="clave" id="clave" placeholder="Ingresa una clave segura" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="correo" class="form-label fw-bold text-muted small text-uppercase">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" name="correo" id="correo" value="<?= e($old['correo'] ?? ''); ?>" placeholder="ejemplo@dominio.com" required>
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
                                <button type="submit" class="btn btn-primary shadow-sm w-100">
                                    <i class="bi bi-check-circle"></i> Guardar Usuario
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>