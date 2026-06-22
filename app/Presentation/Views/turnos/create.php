<div class="container pt-5 mt-4 pb-4 mb-3">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h1 class="h3 mb-0 text-dark">
                <i class="bi bi-calendar-plus-fill me-2"></i>Registrar Turno
            </h1>
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/')); ?>" class="text-decoration-none"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/turnos')); ?>" class="text-decoration-none"><i class="bi bi-calendar2-week-fill"></i> Turnos</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Crear</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <form action="<?= e(app_url('/turnos/crear')); ?>" method="post">
                <input type="hidden" name="_token" value="<?= e(\Core\Csrf::token()); ?>">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h5 class="card-title mb-0 fw-bold text-primary">
                            <i class="bi bi-card-list me-2"></i>Formulario de Registro
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <?php if (!empty($vm->error)) : ?>
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <div><?= e($vm->error); ?></div>
                            </div>
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="conductor_id" class="form-label fw-bold text-muted small text-uppercase">Conductor</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-person-badge"></i></span>
                                    <select class="form-select" name="conductor_id" id="conductor_id" required>
                                        <option value="">Selecciona un conductor</option>
                                        <?php foreach ($vm->conductores as $conductor) : ?>
                                            <option value="<?= e((string) $conductor->id); ?>" <?= ($vm->old['conductor_id'] === (string) $conductor->id) ? 'selected' : ''; ?>>
                                                <?= e($conductor->nombres); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="placa" class="form-label fw-bold text-muted small text-uppercase">Taxi (Placa)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-car-front"></i></span>
                                    <select class="form-select" name="placa" id="placa" required>
                                        <option value="">Selecciona un taxi</option>
                                        <?php foreach ($vm->taxis as $taxi) : ?>
                                            <option value="<?= e((string) $taxi->placa); ?>" <?= ($vm->old['placa'] === (string) $taxi->placa) ? 'selected' : ''; ?>>
                                                <?= e((string) $taxi->placa); ?> — <?= e($taxi->marca); ?> <?= e($taxi->modelo); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Fecha de Inicio</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-calendar-event"></i></span>
                                    <input type="date" class="form-control" name="fecha_inicio" value="<?= e($vm->old['fecha_inicio'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Hora de Inicio</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-clock"></i></span>
                                    <input type="time" class="form-control" name="hora_inicio" value="<?= e($vm->old['hora_inicio'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold text-muted small text-uppercase">Fecha de Fin</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-calendar-x"></i></span>
                                    <input type="date" class="form-control" name="fecha_fin" value="<?= e($vm->old['fecha_fin'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Hora de Fin</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-clock-history"></i></span>
                                    <input type="time" class="form-control" name="hora_fin" value="<?= e($vm->old['hora_fin'] ?? ''); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row">
                            <div class="col-12 col-sm-auto mb-2 mb-sm-0">
                                <a class="btn btn-light border shadow-sm w-100" href="<?= e(app_url('/turnos')); ?>" role="button">
                                    <i class="bi bi-x-circle"></i> Cancelar
                                </a>
                            </div>
                            <div class="col-12 col-sm-auto">
                                <button type="submit" class="btn btn-primary shadow-sm w-100">
                                    <i class="bi bi-check-circle"></i> Guardar Turno
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
