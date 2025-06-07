<?php
include 'db.php';

// Validar fechas
if (!isset($_GET['fecha_inicio'], $_GET['fecha_fin'])) {
    die("Faltan las fechas de inicio o fin.");
}

$inicio = $_GET['fecha_inicio'];
$fin = $_GET['fecha_fin'];

// Validar rango de 31 días
$diff = (strtotime($fin) - strtotime($inicio)) / (60 * 60 * 24);
if ($diff > 31) {
    die("El rango de fechas no puede exceder los 31 días.");
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=palets_filtrados.xls");
header("Pragma: no-cache");
header("Expires: 0");

// Encabezados de tabla
echo "<table border='1'>";
echo "<tr>
        <th>ID Palet</th>
        <th>Fecha</th>
        <th>Planta</th>
        <th>NP Cliente</th>
        <th>Nombre Cliente</th>
        <th>Taller</th>
        <th>Tipo</th>
        <th>Cantidad</th>
      </tr>";

$inicio_sql = $inicio . " 00:00:00";
$fin_sql = $fin . " 23:59:59";

$query = "SELECT id_palet, fecha_registro, planta_produccion, np_cliente, nombre_cliente, taller_paletizado, tipo_palet, cantidad 
          FROM palets 
          WHERE fecha_registro BETWEEN '$inicio_sql' AND '$fin_sql' 
          ORDER BY fecha_registro DESC";

$result = $conn->query($query);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['id_palet']}</td>";
        echo "<td>{$row['fecha_registro']}</td>";
        echo "<td>{$row['planta_produccion']}</td>";
        echo "<td>{$row['np_cliente']}</td>";
        echo "<td>{$row['nombre_cliente']}</td>";
        echo "<td>{$row['taller_paletizado']}</td>";
        echo "<td>{$row['tipo_palet']}</td>";
        echo "<td>{$row['cantidad']}</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='8'>No se encontraron registros en el rango de fechas seleccionado.</td></tr>";
}

echo "</table>";
?>
