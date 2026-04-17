<div class="container pt-5 mt-4 pb-4 mb-3">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <h1 class="h3 mb-0 text-dark">
                <i class="bi bi-car-front-fill me-2 text-primary"></i>Gestión de Taxis
            </h1>
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 justify-content-center justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/')); ?>" class="text-decoration-none"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Taxis</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex flex-column flex-sm-row justify-content-between align-items-center border-bottom gap-3">
            <h5 class="card-title mb-0 fw-bold text-primary">
                <i class="bi bi-list-ul me-2"></i>Lista de Taxis
            </h5>
            <div class="card-tools">
                <a class="btn btn-primary btn-sm shadow-sm" href="<?= e(app_url('/taxis/crear')); ?>" role="button">
                    <i class="bi bi-plus-circle-fill me-1"></i> Nuevo Taxi
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm" id="tabla_id" style="visibility: hidden;">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="text-center" style="width: 80px;">Placa</th>
                            <th scope="col">Modelo</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Propietario</th>
                            <th scope="col" class="text-center" style="width: 120px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $counter = 1;
                        foreach ($lista_taxis as $registro) : ?>
                            <tr>
                                <td class="text-center fw-bold text-primary"><?= $counter++; ?></td>
                                <td><?= e($registro['Modelo']); ?></td>
                                <td><?= e($registro['Marca']); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-badge me-2 text-secondary"></i>
                                        <?= e($registro['propietario']); ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group shadow-sm" role="group">
                                        <a class="btn btn-light btn-sm border" href="<?= e(app_url('/taxis/editar')); ?>?placa=<?= e($registro['Placa']); ?>" title="Editar">
                                            <i class="bi bi-pencil-square text-success"></i>
                                        </a>
                                        <button type="button" class="btn btn-light btn-sm border btn-eliminar"
                                            data-placa="<?= e($registro['Placa']); ?>"
                                            data-token="<?= e(\App\Core\Csrf::token()); ?>"
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
</div>

<script type="module">
    import {
        initDeleteButtons
    } from '<?= e(app_url('/modules/taxis/delete.js')); ?>';
    initDeleteButtons('<?= e(app_url()); ?>');
</script>