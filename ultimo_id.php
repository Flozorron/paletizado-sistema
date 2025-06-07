<?php
include 'db.php';

$result = $conn->query("SELECT id_palet FROM palets ORDER BY id_palet DESC LIMIT 1");

if ($result && $row = $result->fetch_assoc()) {
    echo $row['id_palet'];
} else {
    echo "Ninguno";
}

$conn->close();
?>
