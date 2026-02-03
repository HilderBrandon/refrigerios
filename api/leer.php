<?php
include '../config/conexion.php';

$sql = "SELECT id, titulo, descripcion, fecha_creacion FROM tareas ORDER BY fecha_creacion DESC";
$resultado = $conexion->query($sql);

$tareas = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $tareas[] = $fila;
    }
    echo json_encode(['success' => true, 'data' => $tareas]);
} else {
    echo json_encode(['success' => true, 'data' => []]);
}

$conexion->close();
?>
