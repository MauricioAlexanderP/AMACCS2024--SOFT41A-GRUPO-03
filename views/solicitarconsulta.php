<?php 
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(__DIR__) . '/model/detalle.php';

$detalle = new Detalle();
$detalle->generarJSON();
$estados = $detalle->obtenerTodosLosEstados();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consultas</title>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />

<style>
    :root {
        --azul: #1976D2;
        --gris-text: #333;
    }

    body {
        background:#f5f5f5;
        margin:0;
        padding:20px;
        color:var(--gris-text);
        font-family: Arial, Helvetica, sans-serif;
    }

    .header {
        display:flex;
        align-items:center;
        gap:10px;
        margin-bottom:20px;
    }

    .header h1 {
        font-size:24px;
        margin:0;
    }

    .form-container {
        background:#fff;
        padding:20px;
        border-radius:12px;
        box-shadow:0 8px 20px rgba(0,0,0,0.1);
        max-width:600px;
        margin:0 auto 30px;
    }

    .form-container label {
        display:block;
        margin-bottom:6px;
        font-weight:600;
    }

    .form-container input, .form-container textarea, .form-container select, .form-container button {
        width:100%;
        padding:10px;
        margin-bottom:15px;
        border-radius:6px;
        border:1px solid #ccc;
        font-size:14px;
    }

    .form-container textarea {
        resize: vertical;
        min-height:80px;
    }

    /* 🔹 Separar el botón del Select2 */
    .select2-container {
        margin-bottom: 20px !important;
    }

    .form-container button {
        background:var(--azul);
        color:#fff;
        border:none;
        font-weight:600;
        cursor:pointer;
        transition:background 0.3s;
    }

    .form-container button:hover {
        background:#1565C0;
    }

    h2 {
        text-align:center;
        margin-bottom:12px;
    }
</style>
</head>
<body>

<div class="header">
    <h1>Kiosko - Solicitud de Consulta</h1>
    <img src="logo.png" alt="Logo" style="height:40px;">
</div>

<div class="form-container">
    <form id="formConsulta">
        <label for="carnet">Ingrese su Carnet:</label>
        <input type="text" id="carnet" name="carnet" placeholder="Ingrese su carné" required>

        <label for="materia">Materia a consultar:</label>
        <input type="text" id="materia" name="materia" placeholder="Ej: Matemáticas" required>

        <label for="descripcion">Descripción de la consulta:</label>
        <textarea id="descripcion" name="descripcion" placeholder="Escriba aquí su consulta..." required></textarea>

        <label for="docenteSelect">Seleccione un docente disponible:</label>
        <select id="docenteSelect" name="docente" required>
            <option value="">Cargando docentes disponibles...</option>
        </select>

        <button type="submit">Enviar Solicitud</button>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
async function cargarDocentes() {
    try {
        const response = await fetch('../config/detalles.json');
        const datos = await response.json();

        const select = $('#docenteSelect');
        select.empty();

        if (!Array.isArray(datos) || datos.length === 0) {
            select.append('<option value="">No hay docentes disponibles</option>');
            return;
        }

        const dias = ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"];
        const hoy = dias[new Date().getDay()];

        const docentesPorNombre = {};
        datos.forEach(d => {
            const nombre = `${d.nombre_docente} ${d.apellido_docente}`.trim();
            if (!docentesPorNombre[nombre]) docentesPorNombre[nombre] = [];
            docentesPorNombre[nombre].push(d);
        });

        const disponiblesHoy = Object.entries(docentesPorNombre)
            .filter(([nombre, clases]) =>
                !clases.some(c => (c.dia || '').toLowerCase() === hoy)
            )
            .map(([nombre]) => nombre);

        if (disponiblesHoy.length === 0) {
            select.append('<option value="">No hay docentes disponibles hoy</option>');
        } else {
            select.append('<option value="">Seleccione un docente...</option>');
            disponiblesHoy.forEach(nombre => {
                select.append(`<option value="${nombre}">${nombre}</option>`);
            });
        }

        select.select2({
            placeholder: "Seleccione un docente...",
            width: '100%'
        });

    } catch (error) {
        console.error('Error cargando JSON:', error);
        $('#docenteSelect').html('<option value="">Error al cargar docentes</option>');
    }
}

document.addEventListener('DOMContentLoaded', cargarDocentes);


document.getElementById('formConsulta').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validar campos
    const carnet = document.getElementById('carnet').value.trim();
    const materia = document.getElementById('materia').value.trim();
    const descripcion = document.getElementById('descripcion').value.trim();
    const docente = document.getElementById('docenteSelect').value;

    if (!carnet || !materia || !descripcion || !docente) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor complete todos los campos antes de enviar.'
        });
        return;
    }

    // Muestra confirmación
    Swal.fire({
        icon: 'success',
        title: 'Solicitud enviada',
        text: 'Su solicitud ha sido enviada correctamente.',
        confirmButtonColor: '#1976D2'
    });

    // Limpia formulario
    this.reset();
    $('#docenteSelect').val(null).trigger('change');
});
</script>

</body>
</html>
