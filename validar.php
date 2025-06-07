<?php
session_start();
include 'db.php';

$usuario = $_POST['usuario'];
$clave = sha1($_POST['clave']);

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ? AND clave = ?");
$stmt->bind_param("ss", $usuario, $clave);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $datos = $result->fetch_assoc();
    $_SESSION['usuario'] = $datos['usuario'];
    $_SESSION['nombre'] = $datos['nombre_completo'];
    header("Location: admin_etiquetas.php");
} else {
    echo "<script>alert('Usuario o clave incorrectos'); window.location.href='login.php';</script>";
}
?>
