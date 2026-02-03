<?php
include '../config/conexion.php';

$año = $_POST['año'] ?? date('Y');
$mes = $_POST['mes'] ?? date('m');
$quincena = $_POST['quincena'] ?? '';

$sql = "SELECT h.id, h.fecha_id, h.proveedor_id, h.seccion_id, h.refrigerio_id, h.jornada_id,
               h.cantidad, h.valor_unitario, h.valor_total, h.cuenta_cobro, h.observaciones, h.fecha_creacion,
               f.fecha, f.año, f.mes, f.quincena, p.nombre as proveedor, s.nombre as seccion, r.nombre as refrigerio, j.nombre as jornada, a.nombre as area
        FROM hechos h
        JOIN fechas f ON h.fecha_id = f.id
        JOIN proveedores p ON h.proveedor_id = p.id
        JOIN secciones s ON h.seccion_id = s.id
        JOIN areas a ON s.id_area = a.id
        JOIN refrigerios r ON h.refrigerio_id = r.id
        JOIN jornadas j ON h.jornada_id = j.id
        WHERE f.año = ? AND f.mes = ?";

$params = [$año, $mes];
$types = "ii";

if (!empty($quincena)) {
    $sql .= " AND f.quincena = ?";
    $params[] = $quincena;
    $types .= "i";
}

$sql .= " ORDER BY f.fecha ASC, p.nombre ASC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$resultado = $stmt->get_result();

$hechos = [];
$resumen_proveedor = [];
$resumen_area = [];

if ($resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $hechos[] = $fila;
        
        // Resumen por proveedor
        $prov = $fila['proveedor'];
        if (!isset($resumen_proveedor[$prov])) {
            $resumen_proveedor[$prov] = 0;
        }
        $resumen_proveedor[$prov] += $fila['valor_total'];
        
        // Resumen por área
        $area = $fila['area'];
        if (!isset($resumen_area[$area])) {
            $resumen_area[$area] = 0;
        }
        $resumen_area[$area] += $fila['valor_total'];
    }
}

echo json_encode([
    'success' => true,
    'data' => $hechos,
    'resumen_proveedor' => $resumen_proveedor,
    'resumen_area' => $resumen_area,
    'total_general' => array_sum($resumen_proveedor)
]);

$stmt->close();
$conexion->close();
?>
