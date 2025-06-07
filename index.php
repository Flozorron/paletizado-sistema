<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Paletizado</title>
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
            backdrop-filter: blur(8px) brightness(0.9);
            z-index: -1;
        }
        .info-box {
            background-color: #e3f2fd;
            border-left: 5px solid #0d6efd;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="fondo-overlay"></div>

<!-- Modal de éxito -->
<div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title" id="modalExitoLabel">✔ Registro exitoso</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body" id="mensajeExito"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!-- Campo oculto para capturar el QR -->
<textarea id="lectorQR" style="opacity:0; position:absolute; top:-1000px; left:-1000px;" autofocus></textarea>

<div class="container py-5">
    <div class="card shadow-lg mx-auto" style="max-width: 600px;">
        <div class="card-header bg-primary text-white text-center">
            <h4 class="mb-0">Formulario de Registro de Paletizado</h4>
        </div>
        <div class="card-body">

            <!-- Botón de acceso a listado_palets -->
            <div class="d-flex justify-content-end mb-3">
                <a href="listar_palets.php" class="btn btn-outline-primary" target="_blank">
                    📋 Lista de Palets
                </a>
            </div>

            <div class="info-box text-center">
                Último ID Palet ingresado: <span id="ultimoIdPalet" class="text-primary">Cargando...</span>
            </div>

            <div class="mb-4 text-center">
                <label class="form-label fw-bold">Datos escaneados:</label>
                <input type="text" id="qrPreview" class="form-control text-center" readonly>
            </div>

            <form id="formularioQR" method="POST">
                <div class="mb-3">
                    <label class="form-label">ID Palet:</label>
                    <input type="text" name="id_palet" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Planta de Producción:</label>
                    <select name="planta_produccion" class="form-select" required>
                        <option value="">Seleccione una planta...</option>
                        <option value="Faret">Faret</option>
                        <option value="Innpack">Innpack</option>
                        <option value="Innpack SFM">Innpack SFM</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">NP Cliente:</label>
                    <input type="text" name="np_cliente" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nombre de Cliente:</label>
                    <input type="text" name="nombre_cliente" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Taller de Paletizado:</label>
                    <input type="text" name="taller_paletizado" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Tipo de Palet:</label>
                    <select name="tipo_palet" class="form-select" required>
                        <option value="">Seleccione un tipo...</option>
                        <option value="Tapa">Tapa</option>
                        <option value="Base">Base</option>
                        <option value="Maleta">Maleta</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Unidades por palet:</label>
                    <input type="number" name="unidades" class="form-control" required min="1">
                </div>
            </form>

            <!-- Botón de acceso a admin_etiquetas -->
            <div class="text-center mt-4">
                <a href="admin_etiquetas.php" class="btn btn-outline-dark btn-lg" target="_blank">
                    ⚙️ Administrar Etiquetas
                </a>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const lectorQR = document.getElementById('lectorQR');
    const qrPreview = document.getElementById('qrPreview');
    const formulario = document.getElementById('formularioQR');

    function cargarUltimoIdPalet() {
        fetch('ultimo_id.php')
            .then(res => res.text())
            .then(data => {
                document.getElementById('ultimoIdPalet').innerText = data;
            })
            .catch(() => {
                document.getElementById('ultimoIdPalet').innerText = 'Error';
            });
    }

    function mostrarModalExito(idPalet) {
        const modal = new bootstrap.Modal(document.getElementById('modalExito'));
        document.getElementById('mensajeExito').textContent = `Palet ${idPalet} ingresado exitosamente.`;
        modal.show();
    }

    lectorQR.addEventListener('input', function () {
        const data = lectorQR.value.trim();
        lectorQR.value = '';
        qrPreview.value = data;

        const partes = data.split('|');
        if (partes.length >= 7) {
            const [np_cliente, nombre_cliente, taller, tipo, planta, unidades, correlativo] = partes;

            formulario.id_palet.value = correlativo;
            formulario.planta_produccion.value = planta;
            formulario.np_cliente.value = np_cliente;
            formulario.nombre_cliente.value = nombre_cliente;
            formulario.taller_paletizado.value = taller;
            formulario.tipo_palet.value = tipo;
            formulario.unidades.value = unidades;

            const formData = new URLSearchParams(new FormData(formulario));

            fetch('registro_qr.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: formData.toString()
            })
            .then(res => {
                if (!res.ok) return res.text().then(t => { throw new Error(t); });
                return res.text();
            })
            .then(res => {
                mostrarModalExito(correlativo);
                formulario.reset();
                qrPreview.value = '';
                cargarUltimoIdPalet();
            })
            .catch(err => {
                alert(err.message);
                formulario.reset();
                qrPreview.value = '';
                lectorQR.value = '';
                formulario.id_palet.value = '';
                formulario.planta_produccion.value = '';
                formulario.np_cliente.value = '';
                formulario.nombre_cliente.value = '';
                formulario.taller_paletizado.value = '';
                formulario.tipo_palet.value = '';
                formulario.unidades.value = '';
            });
        } else {
            alert("Código QR incompleto");
        }
    });

    window.onload = () => {
        lectorQR.focus();
        cargarUltimoIdPalet();
    };
    document.addEventListener('click', () => lectorQR.focus());
</script>
</body>
</html>

