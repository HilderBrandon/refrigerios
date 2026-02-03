<?php
include '../config/conexion.php';

$id = $_POST['id'] ?? '';
$refrigerio_id = $_POST['refrigerio_id'] ?? '';
$jornada_id = $_POST['jornada_id'] ?? '';
$proveedor_id = $_POST['proveedor_id'] ?? '';
$valor = $_POST['valor'] ?? '';

if (empty($id) || empty($refrigerio_id) || empty($jornada_id) || empty($proveedor_id) || empty($valor)) {
    echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos']);
    exit;
}

$sql = "UPDATE valores SET refrigerio_id = ?, jornada_id = ?, proveedor_id = ?, valor = ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iiidi", $refrigerio_id, $jornada_id, $proveedor_id, $valor, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Valor actualizado exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>
