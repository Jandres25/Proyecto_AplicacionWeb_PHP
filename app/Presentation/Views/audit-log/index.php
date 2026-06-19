<div class="container pt-5 mt-4 pb-4 mb-3">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
            <h1 class="h3 mb-0 text-dark">
                <i class="bi bi-shield-lock-fill me-2 text-primary"></i>Log de Auditoría
            </h1>
        </div>
        <div class="col-md-6">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 justify-content-center justify-content-md-end">
                    <li class="breadcrumb-item"><a href="<?= e(app_url('/')); ?>" class="text-decoration-none"><i class="bi bi-house-door-fill"></i> Inicio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Log de Auditoría</li>
                </ol>
            </nav>
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
            <form method="GET" action="<?= e(app_url('/audit-log')); ?>">
                <div class="row g-3 align-items-end">
                    <div class="col-sm-6 col-md-4">
                        <label for="filtro_entidad" class="form-label small fw-semibold">Módulo (entidad)</label>
                        <select id="filtro_entidad" name="entidad" class="form-select form-select-sm">
                            <option value="">Todos los módulos</option>
                            <?php foreach (['auth', 'taxis', 'propietarios', 'conductores', 'usuarios', 'perfil'] as $entidad) : ?>
                                <option value="<?= e($entidad); ?>" <?= $vm->filtroEntidad === $entidad ? 'selected' : ''; ?>>
                                    <?= e(ucfirst($entidad)); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <label for="filtro_usuario_id" class="form-label small fw-semibold">Usuario</label>
                        <select id="filtro_usuario_id" name="usuario_id" class="form-select form-select-sm">
                            <option value="">Todos los usuarios</option>
                            <?php foreach ($vm->usuarios as $usuario) : ?>
                                <option value="<?= e((string) $usuario->id); ?>" <?= $vm->filtroUsuarioId === $usuario->id ? 'selected' : ''; ?>>
                                    <?= e($usuario->nombres . ' ' . $usuario->apellidos . ' (@' . $usuario->usuario . ')'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 d-flex">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary btn-sm px-4">
                                <i class="bi bi-search me-1"></i>Filtrar
                            </button>
                            <a href="<?= e(app_url('/audit-log')); ?>" class="btn btn-outline-secondary btn-sm px-4">
                                <i class="bi bi-x-circle me-1"></i>Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="card-title mb-0 fw-bold text-primary">
                <i class="bi bi-list-ul me-2"></i>Registros de auditoría
            </h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-hover table-sm" id="tabla_audit_log" style="visibility: hidden;">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center" style="width: 60px;">#</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Módulo</th>
                        <th scope="col">Acción</th>
                        <th scope="col">Descripción</th>
                        <th scope="col" class="text-center">IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($vm->entries as $entry) : ?>
                        <tr>
                            <td class="text-center text-muted fw-bold"><?= e((string) $entry->id); ?></td>
                            <td class="text-nowrap"><?= e($entry->creadoEn); ?></td>
                            <td><?= e($entry->usuarioNombre); ?></td>
                            <td class="text-center"><span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle"><?= e($entry->entidad); ?></span></td>
                            <td><code><?= e($entry->accion); ?></code></td>
                            <td><?= e($entry->descripcion); ?></td>
                            <td class="text-center text-muted small"><?= e($entry->ip ?? '—'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= e(app_url('/js/modules/audit-log/datatable.js')); ?>"></script>