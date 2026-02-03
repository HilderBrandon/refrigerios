<?php
include '../config/conexion.php';

$nombre = $_POST['nombre'] ?? '';
$comida = isset($_POST['comida']) ? 1 : 0;
$descripcion = $_POST['descripcion'] ?? '';

if (empty($nombre)) {
    echo json_encode(['success' => false, 'message' => 'Por favor ingresa el nombre']);
    exit;
}

$sql = "INSERT INTO refrigerios (nombre, comida, descripcion) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sis", $nombre, $comida, $descripcion);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Refrigerio creado exitosamente', 'id' => $conexion->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>
