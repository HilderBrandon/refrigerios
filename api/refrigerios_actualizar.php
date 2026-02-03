<?php
include '../config/conexion.php';

$id = $_POST['id'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$comida = isset($_POST['comida']) ? 1 : 0;
$descripcion = $_POST['descripcion'] ?? '';

if (empty($id) || empty($nombre)) {
    echo json_encode(['success' => false, 'message' => 'Por favor completa los campos requeridos']);
    exit;
}

$sql = "UPDATE refrigerios SET nombre = ?, comida = ?, descripcion = ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sisi", $nombre, $comida, $descripcion, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Refrigerio actualizado exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>
