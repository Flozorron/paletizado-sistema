<?php
include 'db.php';
session_start();

if (!isset($_POST['np_cliente']) || empty($_POST['np_cliente'])) {
    die("Faltan datos necesarios para generar las etiquetas.");
}

$np_cliente = $_POST['np_cliente'];

// Consulta completa de etiquetas
$stmt = $conn->prepare("SELECT * FROM etiquetas_temp WHERE np_cliente = ? ORDER BY correlativo ASC");
$stmt->bind_param("s", $np_cliente);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Impresión de Etiquetas</title>
    <style>
        @media print {
            @page {
                size: 145mm 168mm;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
            }
        }

        body {
            font-family: Arial, sans-serif;
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .etiqueta {
            width: 145mm;
            height: 168mm;
            box-sizing: border-box;
            padding: 10mm;
            margin: 0 auto;
            page-break-after: always;
            border: 2px solid #000;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .contenido {
            text-align: center;
        }

        .titulo {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
        }

        .dato {
            font-size: 20px;
            margin: 8px auto;
            border: 1px solid #000;
            padding: 8px 16px;
            width: 80%;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .qr {
            margin-top: 20px;
        }

        .qr img {
            height: 140px;
            width: 140px;
        }
    </style>
</head>
<body onload="window.print()">
<?php while ($row = $result->fetch_assoc()): ?>
    <div class="etiqueta">
        <div class="contenido">
            <div class="titulo">NP: <?php echo htmlspecialchars($row['np_cliente']); ?></div>
            <div class="dato">Cliente: <?php echo htmlspecialchars($row['nombre_cliente']); ?></div>
            <div class="dato">Taller: <?php echo htmlspecialchars($row['taller_paletizado']); ?></div>
            <div class="dato">Tipo Palet: <?php echo htmlspecialchars($row['tipo_palet']); ?></div>
            <div class="dato">Planta: <?php echo htmlspecialchars($row['planta_produccion'] ?? ''); ?></div>
            <div class="dato">Unidades por Palet: <?php echo htmlspecialchars($row['unidades']); ?></div>
            <div class="dato">Correlativo: <?php echo str_pad($row['correlativo'], 3, '0', STR_PAD_LEFT); ?></div>
            <div class="qr">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=<?php 
                    echo urlencode(
                        $row['np_cliente'] . '|' .
                        $row['nombre_cliente'] . '|' .
                        $row['taller_paletizado'] . '|' .
                        $row['tipo_palet'] . '|' .
                        ($row['planta_produccion'] ?? '') . '|' .
                        $row['unidades'] . '|' .
                        str_pad($row['correlativo'], 3, '0', STR_PAD_LEFT)
                    );
                ?>" alt="QR Code">
            </div>
        </div>
    </div>
<?php endwhile; ?>
</body>
</html>
