<?php
include 'db.php';
session_start();

if (!isset($_GET['id'])) {
    die("Etiqueta no especificada.");
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM etiquetas_temp WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$etiqueta = $result->fetch_assoc();

if (!$etiqueta) {
    die("Etiqueta no encontrada.");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Etiqueta</title>
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
            width: 145mm;
            height: 168mm;
            font-family: 'Arial', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
        }

        .etiqueta {
            border: 3px solid #000;
            padding: 20px;
            width: 90%;
            height: 90%;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .titulo {
            text-align: center;
            font-size: 34px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .datos {
            font-size: 22px;
            line-height: 1.6;
        }

        .dato-label {
            font-weight: bold;
        }

        .qr {
            text-align: center;
            margin-top: 10px;
        }

        .footer {
            text-align: center;
            font-size: 18px;
            margin-top: 15px;
            font-style: italic;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="etiqueta">
        <div class="titulo">ETIQUETA DE PALET</div>
        <div class="datos">
            <div><span class="dato-label">NP Cliente:</span> <?php echo htmlspecialchars($etiqueta['np_cliente']); ?></div>
            <div><span class="dato-label">Cliente:</span> <?php echo htmlspecialchars($etiqueta['nombre_cliente']); ?></div>
            <div><span class="dato-label">Taller:</span> <?php echo htmlspecialchars($etiqueta['taller_paletizado']); ?></div>
            <div><span class="dato-label">Tipo de Palet:</span> <?php echo htmlspecialchars($etiqueta['tipo_palet']); ?></div>
            <div><span class="dato-label">Unidades:</span> <?php echo htmlspecialchars($etiqueta['unidades']); ?></div>
            <div><span class="dato-label">N° Correlativo:</span> <?php echo str_pad($etiqueta['correlativo'], 3, '0', STR_PAD_LEFT); ?></div>
            <div><span class="dato-label">Planta:</span> <?php echo htmlspecialchars($etiqueta['planta_produccion']); ?></div>
        </div>

        <div class="qr">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=<?php 
                echo urlencode(
                    $etiqueta['np_cliente'] . ' | ' .
                    $etiqueta['nombre_cliente'] . ' | ' .
                    $etiqueta['taller_paletizado'] . ' | ' .
                    $etiqueta['tipo_palet'] . ' | ' .
                    'U: ' . $etiqueta['unidades'] . ' | ' .
                    'C: ' . str_pad($etiqueta['correlativo'], 3, '0', STR_PAD_LEFT) . ' | ' .
                    $etiqueta['planta_produccion']
                ); 
            ?>" alt="QR">
        </div>

        <div class="footer">
            Generado automáticamente por el sistema de etiquetado
        </div>
    </div>
</body>
</html>
