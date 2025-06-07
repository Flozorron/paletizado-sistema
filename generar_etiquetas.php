<?php
session_start();
include 'db.php';

// Validación de campos requeridos
if (
    empty($_POST['np_cliente']) || empty($_POST['nombre_cliente']) ||
    empty($_POST['taller_paletizado']) || empty($_POST['tipo_palet']) ||
    empty($_POST['planta_produccion']) || empty($_POST['unidades']) ||
    empty($_POST['cantidad'])
) {
    die("Faltan campos requeridos.");
}

// Recolección de datos
$np_cliente = trim($_POST['np_cliente']);
$nombre_cliente = trim($_POST['nombre_cliente']);
$taller_paletizado = trim($_POST['taller_paletizado']);
$tipo_palet = trim($_POST['tipo_palet']);
$planta_produccion = trim($_POST['planta_produccion']);
$unidades = (int)$_POST['unidades'];
$cantidad = (int)$_POST['cantidad'];

// Obtener el último correlativo existente
$stmt = $conn->prepare("SELECT MAX(correlativo) AS max_corr FROM etiquetas_temp");
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$ultimo_correlativo = $row['max_corr'] ?? 0;
$correlativo = $ultimo_correlativo + 1;

// Insertar etiquetas
$insert = $conn->prepare("INSERT INTO etiquetas_temp 
(np_cliente, nombre_cliente, taller_paletizado, tipo_palet, planta_produccion, unidades, correlativo) 
VALUES (?, ?, ?, ?, ?, ?, ?)");

for ($i = 0; $i < $cantidad; $i++) {
    $insert->bind_param(
        "ssssssi",
        $np_cliente,
        $nombre_cliente,
        $taller_paletizado,
        $tipo_palet,
        $planta_produccion,
        $unidades,
        $correlativo
    );
    $insert->execute();
    $correlativo++;
}

// Redirigir de vuelta
header("Location: admin_etiquetas.php");
exit;
?>
