<?php
require_once __DIR__ . '/../config/env.php';

$servidor = env('DB_HOST');
$baseDeDatos = env('DB_DATABASE');
$usuario = env('DB_USERNAME');
$contrasenia = env('DB_PASSWORD');

try {
    $conexion = new PDO("mysql:host=$servidor;dbname=$baseDeDatos;charset=utf8mb4", $usuario, $contrasenia);
} catch (Exception $ex) {
    echo $ex->getMessage();
}
