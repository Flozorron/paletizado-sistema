<?php
$host = "localhost";
$user = "root";           // <-- este es el usuario por defecto de XAMPP
$password = "admin123";           // <-- este campo va vacío si no tienes contraseña
$dbname = "registro_paletizado";  // asegúrate de que el nombre sea correcto

$conn = new mysqli($host, $user, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>