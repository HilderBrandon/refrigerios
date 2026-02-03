<?php
include '../config/conexion.php';

$id = $_POST['id'] ?? '';
$nombre = $_POST['nombre'] ?? '';
$nit = $_POST['nit'] ?? '';
$descuento = isset($_POST['descuento']) ? 1 : 0;

if (empty($id) || empty($nombre) || empty($nit)) {
    echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos']);
    exit;
}

$sql = "UPDATE proveedores SET nombre = ?, nit = ?, descuento_administrativo = ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssii", $nombre, $nit, $descuento, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Proveedor actualizado exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>
