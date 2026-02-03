<?php
include '../config/conexion.php';

$nombre = $_POST['nombre'] ?? '';
$hora_inicio = $_POST['hora_inicio'] ?? '';
$hora_fin = $_POST['hora_fin'] ?? '';

if (empty($nombre)) {
    echo json_encode(['success' => false, 'message' => 'Por favor ingresa el nombre']);
    exit;
}

$sql = "INSERT INTO jornadas (nombre, hora_inicio, hora_fin) VALUES (?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("sss", $nombre, $hora_inicio, $hora_fin);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Jornada creada exitosamente', 'id' => $conexion->insert_id]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>
