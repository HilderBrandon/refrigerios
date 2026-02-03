<?php
include '../config/conexion.php';

$id = $_POST['id'] ?? '';
$id_area = $_POST['id_area'] ?? '';
$nombre = $_POST['nombre'] ?? '';

if (empty($id) || empty($id_area) || empty($nombre)) {
    echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos']);
    exit;
}

$sql = "UPDATE secciones SET id_area = ?, nombre = ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("isi", $id_area, $nombre, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Sección actualizada exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>
