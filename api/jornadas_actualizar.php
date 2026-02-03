<?php
include '../config/conexion.php';

$id = $_POST['id'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$hora_inicio = $_POST['hora_inicio'] ?? '';
$hora_fin = $_POST['hora_fin'] ?? '';

if (empty($id) || empty($nombre)) {
    echo json_encode(['success' => false, 'message' => 'Por favor completa los campos requeridos']);
    exit;
}

$sql = "UPDATE jornadas SET nombre = ?, hora_inicio = ?, hora_fin = ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sssi", $nombre, $hora_inicio, $hora_fin, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Jornada actualizada exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>
