<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error 500</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(app_url('/img/compras.png')); ?>" rel="shortcut icon">
</head>

<body class="bg-light">
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <section class="card shadow-sm border-0 text-center p-4 p-md-5" style="max-width: 640px;">
            <h1 class="display-3 fw-bold text-danger mb-3">500</h1>
            <h2 class="h4 mb-3">Error interno del servidor</h2>
            <p class="text-secondary mb-4">Ocurrió un error inesperado. Intenta nuevamente en unos minutos.</p>
            <a href="<?= e(app_url('/')); ?>" class="btn btn-dark">Volver al inicio</a>
        </section>
    </main>
</body>

</html>
