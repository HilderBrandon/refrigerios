<?php
header('Content-Type: application/json; charset=utf-8');

$host = 'localhost';
$usuario = 'root';
$contraseña = '1234';
$bd = 'info';

// Crear conexión
$conexion = new mysqli($host, $usuario, $contraseña, $bd);

// Verificar conexión
if ($conexion->connect_error) {
    die(json_encode(['error' => 'Error de conexión: ' . $conexion->connect_error]));
}

// Configurar el charset
$conexion->set_charset("utf8");

?>
