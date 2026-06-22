<div class="container pt-5 mt-4 pb-4 mb-3">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <h1 class="h3 mb-0 text-dark">
                <i class="bi bi-calendar2-week-fill me-2 text-primary"></i>Gestión de Turnos
            </h1>
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 justify-content-center justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/')); ?>" class="text-decoration-none"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Turnos</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center border-bottom gap-3">
            <h5 class="card-title mb-0 fw-bold text-primary">
                <i class="bi bi-list-ul me-2"></i>Lista de Registros
            </h5>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm shadow-sm" href="<?= e(app_url('/turnos/crear')); ?>" role="button">
                    <i class="bi bi-calendar-plus-fill me-1"></i> Nuevo Turno
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-sm" id="tabla_turnos" style="visibility: hidden;">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center" style="width: 50px;">#</th>
                        <th scope="col">Conductor</th>
                        <th scope="col">Taxi</th>
                        <th scope="col">Inicio</th>
                        <th scope="col">Fin</th>
                        <th scope="col" class="text-center" style="width: 120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1;
                    foreach ($vm->listaTurnos as $registro) : ?>
                        <tr>
                            <td class="text-center text-muted fw-bold"><?= $counter++; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-light rounded-circle border d-flex align-items-center justify-content-center me-3 d-none d-sm-flex" style="width: 35px; height: 35px; flex-shrink: 0;">
                                        <i class="bi bi-person-badge text-secondary"></i>
                                    </div>
                                    <div class="fw-bold"><?= e($registro->conductorNombre); ?></div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3">
                                    <i class="bi bi-car-front-fill me-1"></i><?= e((string) $registro->placa); ?>
                                </span>
                                <span class="text-muted small ms-1"><?= e($registro->taxiModelo); ?></span>
                            </td>
                            <td>
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2">
                                    <i class="bi bi-play-fill me-1"></i><?= e($registro->inicio); ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2">
                                    <i class="bi bi-stop-fill me-1"></i><?= e($registro->fin); ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group shadow-sm" role="group">
                                    <a class="btn btn-light btn-sm border" href="<?= e(app_url('/turnos/editar')); ?>?id=<?= e((string) $registro->id); ?>" title="Editar">
                                        <i class="bi bi-pencil-square text-success"></i>
                                    </a>
                                    <button type="button" class="btn btn-light btn-sm border btn-eliminar"
                                        data-id="<?= e((string) $registro->id); ?>"
                                        data-nombre="<?= e($registro->conductorNombre . ' — ' . $registro->placa); ?>"
                                        data-token="<?= e(\Core\Csrf::token()); ?>"
                                        title="Eliminar">
                                        <i class="bi bi-trash-fill text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script type="module">
    import { initDeleteButtons } from '<?= e(app_url('/js/modules/turnos/delete.js')); ?>';
    initDeleteButtons('<?= e(app_url()); ?>');
</script>
<script src="<?= e(app_url('/js/modules/turnos/datatable.js')); ?>"></script>
