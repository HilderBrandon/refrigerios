<?php
include '../config/conexion.php';

$sql = "SELECT h.id, h.fecha_id, h.proveedor_id, h.seccion_id, h.refrigerio_id, h.jornada_id,
               h.cantidad, h.valor_unitario, h.valor_total, h.observaciones, h.fecha_creacion,
               f.fecha, p.nombre as proveedor, s.nombre as seccion, r.nombre as refrigerio, j.nombre as jornada
        FROM hechos h
        JOIN fechas f ON h.fecha_id = f.id
        JOIN proveedores p ON h.proveedor_id = p.id
        JOIN secciones s ON h.seccion_id = s.id
        JOIN refrigerios r ON h.refrigerio_id = r.id
        JOIN jornadas j ON h.jornada_id = j.id
        ORDER BY f.fecha DESC, h.fecha_creacion DESC";

$resultado = $conexion->query($sql);
$hechos = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $hechos[] = $fila;
    }
    echo json_encode(['success' => true, 'data' => $hechos]);
} else {
    echo json_encode(['success' => true, 'data' => []]);
}

$conexion->close();
?>
