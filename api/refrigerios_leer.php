<?php
include '../config/conexion.php';

$sql = "SELECT id, nombre, comida, descripcion, activo, fecha_creacion FROM refrigerios WHERE activo = TRUE ORDER BY nombre ASC";
$resultado = $conexion->query($sql);

$refrigerios = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $refrigerios[] = $fila;
    }
    echo json_encode(['success' => true, 'data' => $refrigerios]);
} else {
    echo json_encode(['success' => true, 'data' => []]);
}

$conexion->close();
?>
