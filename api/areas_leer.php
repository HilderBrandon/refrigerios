<?php
include '../config/conexion.php';

$sql = "SELECT id, nombre, finca, activo, fecha_creacion FROM areas WHERE activo = TRUE ORDER BY nombre ASC";
$resultado = $conexion->query($sql);

$areas = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $areas[] = $fila;
    }
    echo json_encode(['success' => true, 'data' => $areas]);
} else {
    echo json_encode(['success' => true, 'data' => []]);
}

$conexion->close();
?>
