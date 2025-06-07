<?php
session_start();
if (isset($_SESSION['usuario'])) {
    header("Location: admin_etiquetas.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso al Generador</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('fondo.jpg') no-repeat center center fixed;
            background-size: cover;
            position: relative;
        }
        .fondo-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100vw;
            height: 100vh;
            backdrop-filter: blur(8px) brightness(0.9);
            z-index: -1;
        }
        .login-container {
            max-width: 400px;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            margin-top: 100px;
        }
    </style>
</head>
<body>
<div class="fondo-overlay"></div>
<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="login-container">
        <h3 class="text-center mb-4">Iniciar Sesión</h3>
        <form action="validar.php" method="POST">
            <div class="mb-3">
                <input type="text" name="usuario" class="form-control" placeholder="Usuario" required>
            </div>
            <div class="mb-3">
                <input type="password" name="clave" class="form-control" placeholder="Clave" required>
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Entrar</button>
            </div>
        </form>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
