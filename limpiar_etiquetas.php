<?php
session_start();
include 'db.php';

// Solo administradores autenticados
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

// Eliminar todas las etiquetas
$conn->query("DELETE FROM etiquetas_temp");

// Redirigir de vuelta al administrador
header("Location: admin_etiquetas.php");
exit;
?>
