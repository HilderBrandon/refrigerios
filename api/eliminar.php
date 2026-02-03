<?php
include '../config/conexion.php';

$id = $_POST['id'] ?? '';

if (empty($id)) {
    echo json_encode(['success' => false, 'message' => 'ID no válido']);
    exit;
}

$sql = "DELETE FROM tareas WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Tarea eliminada exitosamente']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al eliminar la tarea: ' . $conexion->error]);
}

$stmt->close();
$conexion->close();
?>
