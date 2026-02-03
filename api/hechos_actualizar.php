<?php
include '../config/conexion.php';

$id = $_POST['id'] ?? '';
$fecha_id = $_POST['fecha_id'] ?? '';
$proveedor_id = $_POST['proveedor_id'] ?? '';
$seccion_id = $_POST['seccion_id'] ?? '';
$refrigerio_id = $_POST['refrigerio_id'] ?? '';
$jornada_id = $_POST['jornada_id'] ?? '';
$cantidad = $_POST['cantidad'] ?? '';
$valor_unitario = $_POST['valor_unitario'] ?? '';
$cuenta_cobro = $_POST['cuenta_cobro'] ?? '';
$observaciones = $_POST['observaciones'] ?? '';

if (empty($id) || empty($fecha_id) || empty($proveedor_id) || empty($seccion_id) || empty($refrigerio_id) || empty($jornada_id) || empty($cantidad)) {
    echo json_encode(['success' => false, 'message' => 'Por favor completa los campos requeridos']);
    exit;
}

$valor_total = $cantidad * $valor_unitario;

$sql = "UPDATE hechos SET fecha_id = ?, proveedor_id = ?, seccion_id = ?, refrigerio_id = ?, 
        jornada_id = ?, cantidad = ?, valor_unitario = ?, valor_total = ?, cuenta_cobro = ?, observaciones = ? WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("iiiiidissi", $fecha_id, $proveedor_id, $seccion_id, $refrigerio_id, $jornada_id, $cantidad, $valor_unitario, $valor_total, $cuenta_cobro, $observaciones, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Registro actualizado exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>
