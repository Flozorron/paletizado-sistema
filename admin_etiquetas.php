<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}
include 'db.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generador de Etiquetas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 5 -->
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
            backdrop-filter: blur(8px) brightness(0.8);
            z-index: -1;
        }
    </style>
</head>
<body class="bg-light">
<div class="fondo-overlay"></div>

<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="text-white">Generador de Etiquetas</h2>
        <div>
            <span class="me-3 text-white">Bienvenido, <strong><?php echo $_SESSION['nombre']; ?></strong></span>
            <a href="logout.php" class="btn btn-sm btn-outline-light">Cerrar sesión</a>
        </div>
    </div>

    <!-- Formulario para crear nuevas etiquetas -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <h5 class="mb-3">Crear nuevo grupo de etiquetas</h5>
            <form method="post" action="generar_etiquetas.php">
                <div class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="np_cliente" class="form-control form-control-sm" placeholder="NP Cliente" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="nombre_cliente" class="form-control form-control-sm" placeholder="Nombre Cliente" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="taller_paletizado" class="form-control form-control-sm" placeholder="Taller Paletizado" required>
                    </div>
                    <div class="col-md-4">
                        <select name="tipo_palet" class="form-select form-select-sm" required>
                            <option value="">Tipo de Palet...</option>
                            <option value="Tapa">Tapa</option>
                            <option value="Base">Base</option>
                            <option value="Maleta">Maleta</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="planta_produccion" class="form-select form-select-sm" required>
                            <option value="">Planta de Producción...</option>
                            <option value="Faret">Faret</option>
                            <option value="Innpack">Innpack</option>
                            <option value="Innpack SFM">Innpack SFM</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="unidades" class="form-control form-control-sm" placeholder="Unidades por palet" required min="1">
                    </div>
                    <div class="col-md-4">
                        <input type="number" name="cantidad" class="form-control form-control-sm" placeholder="Cantidad de etiquetas" required min="1">
                    </div>
                </div>
                <div class="d-grid mt-3">
                    <button type="submit" class="btn btn-sm btn-success">➕ Generar Etiquetas</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Buscador y botón limpiar -->
    <form method="get" class="mb-3 d-flex align-items-center gap-2">
        <input type="text" name="buscar_np" class="form-control form-control-sm" placeholder="Buscar NP cliente..." value="<?php echo isset($_GET['buscar_np']) ? htmlspecialchars($_GET['buscar_np']) : ''; ?>">
        <button type="submit" class="btn btn-sm btn-outline-primary">Buscar</button>
        <a href="limpiar_etiquetas.php" class="btn btn-sm btn-danger ms-auto" onclick="return confirm('¿Estás seguro de que deseas eliminar todas las etiquetas?')">🗑️ Limpiar Etiquetas</a>
    </form>

    <!-- Tabla de etiquetas agrupadas -->
    <div class="bg-white rounded shadow-sm p-3">
        <h5 class="mb-3">Etiquetas agrupadas por NP cliente</h5>
        <?php
        $buscar_np = isset($_GET['buscar_np']) ? $_GET['buscar_np'] : '';
        $query = "SELECT np_cliente, COUNT(*) AS cantidad, nombre_cliente, taller_paletizado, tipo_palet, planta_produccion, unidades
                  FROM etiquetas_temp 
                  WHERE np_cliente LIKE CONCAT('%', ?, '%')
                  GROUP BY np_cliente, nombre_cliente, taller_paletizado, tipo_palet, planta_produccion, unidades 
                  ORDER BY np_cliente ASC";

        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $buscar_np);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle table-sm">
                    <thead class="table-dark">
                        <tr>
                            <th>NP Cliente</th>
                            <th>Nombre Cliente</th>
                            <th>Taller</th>
                            <th>Tipo Palet</th>
                            <th>Planta</th>
                            <th>Unidades</th>
                            <th>Etiquetas Generadas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
<?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?php echo $row['np_cliente']; ?></td>
        <td><?php echo $row['nombre_cliente']; ?></td>
        <td><?php echo $row['taller_paletizado']; ?></td>
        <td><?php echo $row['tipo_palet']; ?></td>
        <td><?php echo $row['planta_produccion']; ?></td>
        <td><?php echo $row['unidades']; ?></td>
        <td><?php echo $row['cantidad']; ?></td>
        <td>
            <!-- Agregar más etiquetas -->
            <form method="post" action="generar_etiquetas.php" class="d-flex align-items-center gap-2">
                <input type="hidden" name="np_cliente" value="<?php echo $row['np_cliente']; ?>">
                <input type="hidden" name="nombre_cliente" value="<?php echo $row['nombre_cliente']; ?>">
                <input type="hidden" name="taller_paletizado" value="<?php echo $row['taller_paletizado']; ?>">
                <input type="hidden" name="tipo_palet" value="<?php echo $row['tipo_palet']; ?>">
                <input type="hidden" name="planta_produccion" value="<?php echo $row['planta_produccion']; ?>">
                <input type="hidden" name="unidades" value="<?php echo $row['unidades']; ?>">
                <input type="number" name="cantidad" class="form-control form-control-sm" style="width:80px;" min="1" required placeholder="+">
                <button type="submit" class="btn btn-sm btn-success">➕</button>
            </form>

            <!-- Botón imprimir -->
            <form method="post" action="imprimir_etiquetas.php" class="mt-1" target="_blank">
                <input type="hidden" name="np_cliente" value="<?php echo $row['np_cliente']; ?>">
                <input type="hidden" name="nombre_cliente" value="<?php echo $row['nombre_cliente']; ?>">
                <input type="hidden" name="taller_paletizado" value="<?php echo $row['taller_paletizado']; ?>">
                <input type="hidden" name="tipo_palet" value="<?php echo $row['tipo_palet']; ?>">
                <input type="hidden" name="planta_produccion" value="<?php echo $row['planta_produccion']; ?>">
                <input type="hidden" name="unidades" value="<?php echo $row['unidades']; ?>">
                <button type="submit" class="btn btn-sm btn-primary w-100">🖨️ Imprimir</button>
            </form>
        </td>
    </tr>
<?php endwhile; ?>
</tbody>


                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">No se encontraron etiquetas generadas para el NP ingresado.</div>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
