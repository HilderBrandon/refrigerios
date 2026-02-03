<?php
include '../config/conexion.php';

$fecha_id = $_POST['fecha_id'] ?? '';
$proveedor_id = $_POST['proveedor_id'] ?? '';
$seccion_id = $_POST['seccion_id'] ?? '';
$refrigerio_id = $_POST['refrigerio_id'] ?? '';
$jornada_id = $_POST['jornada_id'] ?? '';
$cantidad = $_POST['cantidad'] ?? '';
$valor_unitario = $_POST['valor_unitario'] ?? '';
$cuenta_cobro = $_POST['cuenta_cobro'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';

if (empty($fecha_id) || empty($proveedor_id) || empty($seccion_id) || empty($refrigerio_id) || empty($jornada_id) || empty($cantidad)) {
    echo json_encode(['success' => false, 'message' => 'Por favor completa todos los campos requeridos']);
    exit;
}

$valor_total = $cantidad * $valor_unitario;

$sql = "INSERT INTO hechos (fecha_id, proveedor_id, seccion_id, refrigerio_id, jornada_id, cantidad, valor_unitario, valor_total, cuenta_cobro, observaciones) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iiiiiddsss", $fecha_id, $proveedor_id, $seccion_id, $refrigerio_id, $jornada_id, $cantidad, $valor_unitario, $valor_total, $cuenta_cobro, $observaciones);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Registro creado exitosamente', 'id' => $conexion->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>
