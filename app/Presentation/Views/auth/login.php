<!doctype html>
<html lang="es">

<head>
    <title>Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link href="<?= e(app_url('/img/compras.png')); ?>" rel="shortcut icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="<?= e(app_url('/css/style.css')); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/icheck-bootstrap@3.0.1/icheck-bootstrap.min.css">
</head>

<body>
    <div class="snow">
        <div class="flake"> ❅ </div>
        <div class="flake"> ❅ </div>
        <div class="flake"> ❆ </div>
        <div class="flake"> ❅ </div>
        <div class="flake"> ❅ </div>
        <div class="flake"> ❆ </div>
        <div class="flake"> ❅ </div>
        <div class="flake"> ❅ </div>
        <div class="flake"> ❆ </div>
        <div class="flake"> ❅ </div>
    </div>
    <div class="container d-flex justify-content-center align-items-center login-form">
        <div class="login-content">
            <form method="post" action="<?= e(app_url('/login')); ?>">
                <input type="hidden" name="_token" value="<?= e(\Core\Csrf::token()); ?>">
                <img src="<?= e(app_url('/img/avatar.svg')); ?>" alt="Imagen Usuario">
                <h2 class="title">BIENVENIDO</h2>
                <?php if (!empty($vm->mensaje)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <strong><?= e($vm->mensaje); ?></strong>
                    </div>
                <?php } ?>
                <div class="input-div one">
                    <div class="i">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="div">
                        <h5>Usuario</h5>
                        <input id="usuario" type="text" class="input" name="usuario" title="Usuario" required>
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="bi bi-lock-fill"></i>
                    </div>
                    <div class="div">
                        <h5>Contraseña</h5>
                        <input type="password" id="input" class="input" name="password" title="Password" required>
                    </div>
                </div>
                <div class="view">
                    <div class="bi bi-eye-fill verPassword" onclick="vista()" id="verPassword"></div>
                </div>
                <?php if (filter_var($_ENV['REMEMBER_ME_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN)) : ?>
                    <div class="remember-me">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember" name="remember" value="1">
                            <label for="remember">Recuérdame</label>
                        </div>
                    </div>
                <?php endif; ?>
                <button name="btningresar" class="btn" type="submit">INICIAR SESIÓN</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js" integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous"></script>
    <script src="<?= e(app_url('/js/main.js')); ?>"></script>
    <script src="<?= e(app_url('/js/main2.js')); ?>"></script>
</body>

</html>