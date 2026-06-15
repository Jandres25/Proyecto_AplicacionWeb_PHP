<div class="container pt-5 mt-4 pb-4 mb-3">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <h1 class="h3 mb-0 text-dark">
                <i class="bi bi-bar-chart-fill me-2 text-primary"></i>Reportes de Flota
            </h1>
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 justify-content-center justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/')); ?>" class="text-decoration-none"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Reportes</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-car-front-fill fs-4 text-primary"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold"><?= e($vm->estadisticas->totalTaxis); ?></div>
                        <div class="text-muted small">Taxis registrados</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-person-badge-fill fs-4 text-success"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold"><?= e($vm->estadisticas->totalPropietarios); ?></div>
                        <div class="text-muted small">Propietarios</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-people-fill fs-4 text-info"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold"><?= e($vm->estadisticas->totalConductores); ?></div>
                        <div class="text-muted small">Conductores</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="bg-warning bg-opacity-10 rounded-3 p-3">
                        <i class="bi bi-exclamation-triangle-fill fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="fs-4 fw-bold"><?= e($vm->estadisticas->taxisSinConductor); ?></div>
                        <div class="text-muted small">Sin conductor asignado</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 border-bottom">
            <h6 class="mb-0 fw-bold text-secondary">
                <i class="bi bi-funnel-fill me-2"></i>Filtros
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="<?= e(app_url('/reportes')); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-sm-6 col-md-4">
                        <label for="filtro_marca" class="form-label small fw-semibold">Marca</label>
                        <select id="filtro_marca" name="marca" class="form-select form-select-sm">
                            <option value="">Todas las marcas</option>
                            <?php foreach ($vm->marcas as $marca) : ?>
                                <option value="<?= e($marca); ?>" <?= $vm->filtros->marca === $marca ? 'selected' : ''; ?>>
                                    <?= e($marca); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label for="filtro_propietario" class="form-label small fw-semibold">Propietario</label>
                        <select id="filtro_propietario" name="propietario" class="form-select form-select-sm">
                            <option value="">Todos los propietarios</option>
                            <?php foreach ($vm->propietarios as $propietario) : ?>
                                <option value="<?= e($propietario->id); ?>" <?= $vm->filtros->idPropietario === $propietario->id ? 'selected' : ''; ?>>
                                    <?= e($propietario->nombre); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 d-flex">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary btn-sm px-4">
                                <i class="bi bi-search me-1"></i>Filtrar
                            </button>
                            <a href="<?= e(app_url('/reportes')); ?>" class="btn btn-outline-secondary btn-sm px-4">
                                <i class="bi bi-x-circle me-1"></i>Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de flota -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="card-title mb-0 fw-bold text-primary">
                <i class="bi bi-list-ul me-2"></i>Reporte de Flota
            </h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-sm" id="tabla_reportes" style="visibility: hidden;">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center" style="width: 50px;">#</th>
                        <th scope="col" class="text-center" style="width: 80px;">Placa</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">Propietario</th>
                        <th scope="col">Conductor asignado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1;
                    foreach ($vm->flota as $fila) : ?>
                        <tr>
                            <td class="text-center text-muted fw-bold"><?= $counter++; ?></td>
                            <td class="text-center fw-bold text-primary"><?= e($fila->placa); ?></td>
                            <td><?= e($fila->marca); ?></td>
                            <td><?= e($fila->modelo); ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-person-badge me-2 text-secondary"></i>
                                    <?= e($fila->nombrePropietario); ?>
                                </div>
                            </td>
                            <td>
                                <?php if ($fila->nombreConductor !== null) : ?>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-check-fill me-2 text-success"></i>
                                        <?= e($fila->nombreConductor); ?>
                                    </div>
                                <?php else : ?>
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">
                                        <i class="bi bi-exclamation-triangle me-1"></i>Sin asignar
                                    </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= e(app_url('/js/modules/reportes/datatable.js')); ?>"></script>