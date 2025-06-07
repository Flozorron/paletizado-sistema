<?php
include 'db.php';

// Verificar campos requeridos
$campos = ['id_palet', 'planta_produccion', 'np_cliente', 'nombre_cliente', 'taller_paletizado', 'tipo_palet', 'unidades'];
foreach ($campos as $campo) {
    if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
        http_response_code(400);
        echo "⚠️ Campo faltante: $campo";
        exit;
    }
}

// Recibir y sanitizar datos
$id_palet = trim($_POST['id_palet']);
$planta = trim($_POST['planta_produccion']);
$np = trim($_POST['np_cliente']);
$cliente = trim($_POST['nombre_cliente']);
$taller = trim($_POST['taller_paletizado']);
$tipo = trim($_POST['tipo_palet']);
$unidades = intval($_POST['unidades']);

// Verificar si el ID ya existe
$verificar = $conn->prepare("SELECT COUNT(*) FROM palets WHERE id_palet = ?");
$verificar->bind_param("s", $id_palet);
$verificar->execute();
$verificar->bind_result($existe);
$verificar->fetch();
$verificar->close();

if ($existe > 0) {
    http_response_code(409);
    echo "❌ El ID Palet '$id_palet' ya está registrado.";
    exit;
}

// Insertar nuevo registro
$stmt = $conn->prepare("INSERT INTO palets (id_palet, planta_produccion, np_cliente, nombre_cliente, taller_paletizado, tipo_palet, cantidad) VALUES (?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    http_response_code(500);
    echo "Error en la consulta: " . $conn->error;
    exit;
}

$stmt->bind_param("ssssssi", $id_palet, $planta, $np, $cliente, $taller, $tipo, $unidades);

if ($stmt->execute()) {
    echo "✅ Palet $id_palet ingresado exitosamente.";
} else {
    http_response_code(500);
    echo "❌ Error al guardar: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
