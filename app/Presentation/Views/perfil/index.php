<div class="container pt-5 mt-4 pb-4 mb-3">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-dark">
                <i class="bi bi-person-circle me-2"></i>Mi Perfil
            </h1>
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/')); ?>" class="text-decoration-none"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Mi Perfil</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <ul class="nav nav-tabs mb-0" id="perfilTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $vm->activeTab === 'perfil' ? 'active' : ''; ?>"
                        id="tab-perfil"
                        data-bs-toggle="tab"
                        data-bs-target="#pane-perfil"
                        type="button" role="tab">
                        <i class="bi bi-person me-1"></i>Información
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $vm->activeTab === 'clave' ? 'active' : ''; ?>"
                        id="tab-clave"
                        data-bs-toggle="tab"
                        data-bs-target="#pane-clave"
                        type="button" role="tab">
                        <i class="bi bi-key me-1"></i>Contraseña
                    </button>
                </li>
            </ul>

            <div class="tab-content border border-top-0 rounded-bottom shadow-sm bg-white">

                <div class="tab-pane fade <?= $vm->activeTab === 'perfil' ? 'show active' : ''; ?>"
                    id="pane-perfil" role="tabpanel">
                    <form action="<?= e(app_url('/perfil/informacion')); ?>" method="post">
                        <input type="hidden" name="_token" value="<?= e(\Core\Csrf::token()); ?>">
                        <div class="p-4">
                            <?php if (!empty($vm->profileError)) : ?>
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div><?= e($vm->profileError); ?></div>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="usuario" class="form-label fw-bold text-muted small text-uppercase">Usuario</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-at"></i></span>
                                    <input type="text" class="form-control bg-light" value="<?= e($vm->usuario); ?>" readonly>
                                </div>
                                <div class="form-text small">El nombre de usuario no se puede modificar.</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombres" class="form-label fw-bold text-muted small text-uppercase">Nombres</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="nombres" name="nombres"
                                            value="<?= e($vm->nombres); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="apellidos" class="form-label fw-bold text-muted small text-uppercase">Apellidos</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control" id="apellidos" name="apellidos"
                                            value="<?= e($vm->apellidos); ?>" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="correo" class="form-label fw-bold text-muted small text-uppercase">Correo Electrónico</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control" id="correo" name="correo"
                                        value="<?= e($vm->correo); ?>" required>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 pb-4">
                            <button type="submit" class="btn btn-success shadow-sm text-white">
                                <i class="bi bi-save me-1"></i>Guardar cambios
                            </button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade <?= $vm->activeTab === 'clave' ? 'show active' : ''; ?>"
                    id="pane-clave" role="tabpanel">
                    <form action="<?= e(app_url('/perfil/clave')); ?>" method="post">
                        <input type="hidden" name="_token" value="<?= e(\Core\Csrf::token()); ?>">
                        <div class="p-4">
                            <?php if (!empty($vm->passwordError)) : ?>
                                <div class="alert alert-danger d-flex align-items-center" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    <div><?= e($vm->passwordError); ?></div>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label for="clave_actual" class="form-label fw-bold text-muted small text-uppercase">Contraseña actual</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                    <input type="password" class="form-control" id="clave_actual" name="clave_actual" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="clave_nueva" class="form-label fw-bold text-muted small text-uppercase">Nueva contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control" id="clave_nueva" name="clave_nueva" required>
                                </div>
                                <div class="form-text small">Mínimo 8 caracteres.</div>
                            </div>

                            <div class="mb-3">
                                <label for="clave_confirmacion" class="form-label fw-bold text-muted small text-uppercase">Confirmar nueva contraseña</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control" id="clave_confirmacion" name="clave_confirmacion" required>
                                </div>
                            </div>
                        </div>
                        <div class="px-4 pb-4">
                            <button type="submit" class="btn btn-success shadow-sm text-white">
                                <i class="bi bi-shield-lock me-1"></i>Cambiar contraseña
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>