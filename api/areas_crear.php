<?php
include '../config/conexion.php';

$nombre = $_POST['nombre'] ?? '';
$finca = $_POST['finca'] ?? '';

if (empty($nombre) || empty($finca)) {
    echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos']);
    exit;
}

$sql = "INSERT INTO areas (nombre, finca) VALUES (?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ss", $nombre, $finca);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Área creada exitosamente', 'id' => $conexion->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>
