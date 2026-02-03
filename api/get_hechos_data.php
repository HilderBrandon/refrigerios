<?php
include '../config/conexion.php';

$sql_fechas = "SELECT id, fecha FROM fechas WHERE activo = TRUE ORDER BY fecha DESC LIMIT 30";
$fechas = $conexion->query($sql_fechas)->fetch_all(MYSQLI_ASSOC);

$sql_prov = "SELECT id, nombre FROM proveedores WHERE activo = TRUE ORDER BY nombre";
$prov = $conexion->query($sql_prov)->fetch_all(MYSQLI_ASSOC);

$sql_secc = "SELECT id, nombre FROM secciones WHERE activo = TRUE ORDER BY nombre";
$secc = $conexion->query($sql_secc)->fetch_all(MYSQLI_ASSOC);

$sql_ref = "SELECT id, nombre FROM refrigerios WHERE activo = TRUE ORDER BY nombre";
$ref = $conexion->query($sql_ref)->fetch_all(MYSQLI_ASSOC);

$sql_jor = "SELECT id, nombre FROM jornadas WHERE activo = TRUE ORDER BY nombre";
$jor = $conexion->query($sql_jor)->fetch_all(MYSQLI_ASSOC);

echo json_encode(['fechas' => $fechas, 'proveedores' => $prov, 'secciones' => $secc, 'refrigerios' => $ref, 'jornadas' => $jor]);
$conexion->close();
?>
