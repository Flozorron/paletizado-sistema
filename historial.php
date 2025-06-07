<?php
include('conexion.php');
$result = $conn->query("SELECT * FROM etiquetas ORDER BY fecha_registro DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Historial de Etiquetas</title>
</head>
<body>
    <h2>Registros Recientes</h2>
    <table border="1">
        <tr>
            <th>NP Cliente</th>
            <th>Nombre</th>
            <th>Taller</th>
            <th>Tipo Palet</th>
            <th>Planta</th>
            <th>Unidades</th>
            <th>Fecha</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['np_cliente'] ?></td>
            <td><?= $row['nombre_cliente'] ?></td>
            <td><?= $row['taller_paletizado'] ?></td>
            <td><?= $row['tipo_palet'] ?></td>
            <td><?= $row['planta_produccion'] ?></td>
            <td><?= $row['unidades'] ?></td>
            <td><?= $row['fecha_registro'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
