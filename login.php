<?php
    session_start();
    if ($_POST) {
        include("./model/bd.php");

        $sentencia = $conexion->prepare("SELECT *, count(*) as n_usuarios FROM `usuarios` WHERE Usuario = :Usuario AND Clave = :Clave");
        $usuario = $_POST["usuario"];
        $password = $_POST["password"];

        $sentencia->bindParam(":Usuario", $usuario);
        $sentencia->bindParam(":Clave", $password);
        $sentencia->execute();
        $registro = $sentencia->fetch(PDO::FETCH_LAZY);

        if ($registro["n_usuarios"] > 0) {
            $_SESSION["usuario"] = $usuario;
            $_SESSION['logueado'] = true;

            // Obtener el campo nombres del registro
            $nombres = $registro["Nombres"];

            // Almacenar el valor de nombres en la sesión
            $_SESSION["Nombres"] = $nombres;

            header("Location: ./index.php");
        } else {
            $mensaje = "Error: El usuario o contraseña son incorrectos";
        }
    }

?>

<!doctype html>
<html lang="es">

<head>
    <title>Login</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS v5.2.1 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
    <link href="https://tresplazas.com/web/img/big_punto_de_venta.png" rel="shortcut icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
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
            <form method="post" action="">
                <img src="img/avatar.svg" alt="Imagen Usuario">
                <h2 class="title">BIENVENIDO</h2>
                <?php if(isset($mensaje)) { ?>
                    <div class="alert alert-danger" role="alert">
                        <strong><?php echo $mensaje; ?></strong> 
                    </div>
                <?php } ?>
                <div class="input-div one">
                    <div class="i">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <div class="div">
                        <h5>Usuario</h5>
                        <input id="usuario" type="text" class="input" name="usuario" title="Usuario">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="bi bi-lock-fill"></i>
                    </div>
                    <div class="div">
                        <h5>Contraseña</h5>
                        <input type="password" id="input" class="input" name="password" title="Password">
                    </div>
                </div>
                <div class="view">
                    <div class="bi bi-eye-fill verPassword" onclick="vista()" id="verPassword"></div>
                </div>
                <input name="btningresar" class="btn" type="submit" value="INICIAR SESIÓN">
            </form>
        </div>
    </div>

    <!-- Bootstrap JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.min.js"
        integrity="sha384-7VPbUDkoPSGFnVtYi0QogXtr74QeVeeIs99Qfg5YCF+TidwNdjvaKZX19NZ/e6oz" crossorigin="anonymous">
    </script>
    <script src="js/main.js"></script>
    <script src="js/main2.js"></script>
</body>

</html>