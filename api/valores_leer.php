<?php
include '../config/conexion.php';

$sql = "SELECT v.id, v.refrigerio_id, v.jornada_id, v.proveedor_id, v.valor, 
               v.fecha_vigencia_inicio, v.fecha_vigencia_fin,
               r.nombre as refrigerio, j.nombre as jornada, p.nombre as proveedor
        FROM valores v
        JOIN refrigerios r ON v.refrigerio_id = r.id
        JOIN jornadas j ON v.jornada_id = j.id
        JOIN proveedores p ON v.proveedor_id = p.id
        WHERE v.activo = TRUE
        ORDER BY p.nombre, j.nombre, r.nombre";

$resultado = $conexion->query($sql);
$valores = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $valores[] = $fila;
    }
    echo json_encode(['success' => true, 'data' => $valores]);
} else {
    echo json_encode(['success' => true, 'data' => []]);
}

$conexion->close();
?>
