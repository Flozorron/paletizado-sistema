<?php
include 'db.php';

// Obtener los valores del formulario
$id_palet = $_POST['id_palet'] ?? '';
$planta_produccion = $_POST['planta_produccion'] ?? '';
$np_cliente = $_POST['np_cliente'] ?? '';
$np = $_POST['np'] ?? '';
$nombre_cliente = $_POST['nombre_cliente'] ?? '';
$taller_paletizado = $_POST['taller_paletizado'] ?? '';
$paletizador = $_POST['paletizador'] ?? '';
$otro_paletizador = $_POST['otro_paletizador'] ?? '';
$tipo_palet = $_POST['tipo_palet'] ?? '';
$cantidad = $_POST['cantidad'] ?? 0;

// Preparar la consulta
$stmt = $conn->prepare("INSERT INTO palets (id_palet, planta_produccion, np_cliente, np, nombre_cliente, taller_paletizado, paletizador, otro_paletizador, tipo_palet, cantidad) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssssssi", $id_palet, $planta_produccion, $np_cliente, $np, $nombre_cliente, $taller_paletizado, $paletizador, $otro_paletizador, $tipo_palet, $cantidad);

// Ejecutar
if ($stmt->execute()) {
    echo "<script>alert('Registro guardado exitosamente.'); window.location.href='index.php';</script>";
} else {
    echo "<script>alert('Error al guardar: " . $stmt->error . "'); window.history.back();</script>";
}

// Cerrar
$stmt->close();
$conn->close();
?>
