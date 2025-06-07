document.getElementById('qrInput').addEventListener('keypress', function (e) {
    if (e.key === 'Enter') {
        e.preventDefault();

        const qrData = this.value.trim();
        const partes = qrData.split('|');

        // Rellenar campos si hay datos suficientes
        if (partes.length >= 9) {
            document.getElementById('id_palet').value = partes[0];
            document.getElementById('planta_produccion').value = partes[1];
            document.getElementById('np_cliente').value = partes[2];
            document.getElementById('np').value = partes[3];
            document.getElementById('nombre_cliente').value = partes[4];
            document.getElementById('taller_paletizado').value = partes[5];
            document.getElementById('paletizador').value = partes[6];
            document.getElementById('otro_paletizador').value = partes[7];
            document.getElementById('tipo_palet').value = partes[8];
            document.getElementById('cantidad').value = partes[9] || '';

            // Limpiar input de escaneo
            this.value = '';
        } else {
            alert('El código escaneado no tiene el formato correcto.');
        }
    }
});
