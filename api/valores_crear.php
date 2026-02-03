<?php
include '../config/conexion.php';

$refrigerio_id = $_POST['refrigerio_id'] ?? '';
$jornada_id = $_POST['jornada_id'] ?? '';
$proveedor_id = $_POST['proveedor_id'] ?? '';
$valor = $_POST['valor'] ?? '';
$fecha_inicio = $_POST['fecha_inicio'] ?? '';

if (empty($refrigerio_id) || empty($jornada_id) || empty($proveedor_id) || empty($valor) || empty($fecha_inicio)) {
    echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos']);
    exit;
}

$sql = "INSERT INTO valores (refrigerio_id, jornada_id, proveedor_id, valor, fecha_vigencia_inicio) VALUES (?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iiids", $refrigerio_id, $jornada_id, $proveedor_id, $valor, $fecha_inicio);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Valor creado exitosamente', 'id' => $conexion->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>
