<?php
include '../config/conexion.php';

$sql = "SELECT id, nombre, hora_inicio, hora_fin, activo, fecha_creacion FROM jornadas WHERE activo = TRUE ORDER BY nombre ASC";
$resultado = $conexion->query($sql);

$jornadas = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $jornadas[] = $fila;
    }
    echo json_encode(['success' => true, 'data' => $jornadas]);
} else {
    echo json_encode(['success' => true, 'data' => []]);
}

$conexion->close();
?>
