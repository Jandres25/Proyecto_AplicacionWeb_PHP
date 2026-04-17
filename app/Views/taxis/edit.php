<div class="container pt-5 mt-4 pb-4 mb-3">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-dark">
                <i class="bi bi-pencil-square me-2"></i>Editar Taxi
            </h1>
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/')); ?>" class="text-decoration-none"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/taxis')); ?>" class="text-decoration-none"><i class="bi bi-car-front-fill"></i> Taxis</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="<?= e(app_url('/taxis/editar')); ?>" method="post">
                <input type="hidden" name="_token" value="<?= e(\App\Core\Csrf::token()); ?>">
                <input type="hidden" name="placa" value="<?= e($taxi['Placa']); ?>">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="bi bi-car-front me-2"></i>Modificar Vehículo
                        </h5>
                        <span class="badge bg-light text-primary border px-3 py-2">Placa: <?= e($taxi['Placa']); ?></span>
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
                                <label for="modelo" class="form-label fw-bold text-muted small text-uppercase">Modelo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-tag"></i></span>
                                    <input type="text" value="<?= e($taxi['Modelo']); ?>" class="form-control" name="modelo" id="modelo" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="marca" class="form-label fw-bold text-muted small text-uppercase">Marca</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-briefcase"></i></span>
                                    <input type="text" value="<?= e($taxi['Marca']); ?>" class="form-control" name="marca" id="marca" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="propietario" class="form-label fw-bold text-muted small text-uppercase">Propietario</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-person-badge"></i></span>
                                <select class="form-select" name="propietario" id="propietario" required>
                                    <option value="">Selecciona un propietario</option>
                                    <?php foreach ($owners as $owner) : ?>
                                        <option value="<?= e($owner['Idpropietario']); ?>" <?= ((string) $taxi['Idpropietario'] === (string) $owner['Idpropietario']) ? 'selected' : ''; ?>>
                                            <?= e($owner['Nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 col-sm-auto mb-2 mb-sm-0">
                                <a class="btn btn-light border shadow-sm w-100" href="<?= e(app_url('/taxis')); ?>" role="button">
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
