<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Palets</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('fondo.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .fondo-overlay {
            position: fixed;
            top: 0; left: 0;
            width: 100vw;
            height: 100vh;
            backdrop-filter: blur(8px) brightness(0.9);
            z-index: -1;
        }
        .fixed-header {
            position: sticky;
            top: 0;
            background-color: white;
            z-index: 100;
        }
    </style>
</head>
<body>
<div class="fondo-overlay"></div>
<div class="container py-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Listado de Palets Registrados</h4>
            <a href="listar_palets.php" class="btn btn-light btn-sm">Actualizar</a>
        </div>
        <div class="card-body">
            <form class="row g-3 mb-4" method="GET">
                <div class="col-md-4">
                    <label class="form-label">Fecha Inicio:</label>
                    <input type="date" name="fecha_inicio" class="form-control" value="<?= $_GET['fecha_inicio'] ?? '' ?>">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha Fin:</label>
                    <input type="date" name="fecha_fin" class="form-control" value="<?= $_GET['fecha_fin'] ?? '' ?>">
                </div>
                <div class="col-md-4 align-self-end">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                    <?php if (isset($_GET['fecha_inicio'], $_GET['fecha_fin'])): ?>
                        <a href="exportar_excel_palets.php?fecha_inicio=<?= $_GET['fecha_inicio'] ?>&fecha_fin=<?= $_GET['fecha_fin'] ?>" class="btn btn-success">Descargar Excel</a>
                    <?php endif; ?>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-light fixed-header">
                        <tr>
                            <th>ID Palet</th>
                            <th>Fecha</th>
                            <th>Planta</th>
                            <th>NP Cliente</th>
                            <th>Nombre Cliente</th>
                            <th>Taller</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $limit = 25;
                    $pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1;
                    $offset = ($pagina - 1) * $limit;

                    $filtro = "";
                    if (!empty($_GET['fecha_inicio']) && !empty($_GET['fecha_fin'])) {
                        $inicio = $_GET['fecha_inicio'];
                        $fin = $_GET['fecha_fin'];
                        $filtro = "WHERE fecha_registro BETWEEN '$inicio 00:00:00' AND '$fin 23:59:59'";
                    }

                    $sql_total = "SELECT COUNT(*) as total FROM palets $filtro";
                    $total_resultado = $conn->query($sql_total);
                    $total = $total_resultado ? $total_resultado->fetch_assoc()['total'] : 0;
                    $total_paginas = ceil($total / $limit);

                    $sql = "SELECT * FROM palets $filtro ORDER BY fecha_registro DESC LIMIT $limit OFFSET $offset";
                    $result = $conn->query($sql);
                    if ($result && $result->num_rows > 0):
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id_palet']) ?></td>
                                <td><?= date('Y-m-d H:i:s', strtotime($row['fecha_registro'])) ?></td>
                                <td><?= htmlspecialchars($row['planta_produccion']) ?></td>
                                <td><?= htmlspecialchars($row['np_cliente']) ?></td>
                                <td><?= htmlspecialchars($row['nombre_cliente']) ?></td>
                                <td><?= htmlspecialchars($row['taller_paletizado']) ?></td>
                                <td><?= htmlspecialchars($row['tipo_palet']) ?></td>
                                <td><?= htmlspecialchars($row['cantidad']) ?></td>
                            </tr>
                        <?php endwhile;
                    else: ?>
                        <tr><td colspan="8" class="text-center">No se encontraron palets registrados.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($total_paginas > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                        <li class="page-item <?= $i == $pagina ? 'active' : '' ?>">
                            <a class="page-link" href="?pagina=<?= $i ?>&fecha_inicio=<?= $_GET['fecha_inicio'] ?? '' ?>&fecha_fin=<?= $_GET['fecha_fin'] ?? '' ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
            <?php endif; ?>

        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
