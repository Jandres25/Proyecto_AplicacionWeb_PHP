<div class="container pt-5 mt-4 pb-4 mb-3">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-dark">
                <i class="bi bi-pencil-square me-2"></i>Editar Conductor
            </h1>
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/')); ?>" class="text-decoration-none"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/conductores')); ?>" class="text-decoration-none"><i class="bi bi-person-vcard-fill"></i> Conductores</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Editar</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="<?= e(app_url('/conductores/editar')); ?>" method="post">
                <input type="hidden" name="_token" value="<?= e(\App\Core\Csrf::token()); ?>">
                <input type="hidden" name="id" value="<?= e($conductor['ID']); ?>">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="bi bi-person-gear me-2"></i>Modificar Registro
                        </h5>
                        <span class="badge bg-light text-primary border px-3 py-2">ID: <?= e($conductor['ID']); ?></span>
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
                                <label for="nombre" class="form-label fw-bold text-muted small text-uppercase">Nombres Completos</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                    <input type="text" value="<?= e($conductor['Nombres']); ?>" class="form-control" name="nombre" id="nombre" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="telefono" class="form-label fw-bold text-muted small text-uppercase">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-telephone"></i></span>
                                    <input type="text" value="<?= e($conductor['Telefono']); ?>" class="form-control" name="telefono" id="telefono" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="placa" class="form-label fw-bold text-muted small text-uppercase">Taxi Asignado (Placa)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-car-front"></i></span>
                                <select class="form-select" name="placa" id="placa" required>
                                    <option value="">Selecciona una placa</option>
                                    <?php foreach ($taxis as $taxi) : ?>
                                        <option value="<?= e($taxi['Placa']); ?>" <?= ((string) $conductor['Placa'] === (string) $taxi['Placa']) ? 'selected' : ''; ?>>
                                            <?= e($taxi['Placa']); ?> - <?= e($taxi['Marca']); ?> <?= e($taxi['Modelo']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 col-sm-auto mb-2 mb-sm-0">
                                <a class="btn btn-light border shadow-sm w-100" href="<?= e(app_url('/conductores')); ?>" role="button">
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
