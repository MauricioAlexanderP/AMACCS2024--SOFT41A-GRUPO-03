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



<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

        /* Styles para tarjetas similar a verDisponibilidad */
        .docente-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .docente-card:hover { transform: translateY(-5px); box-shadow: 0 6px 15px rgba(0,0,0,0.15); }
        .docente-img { width:100%; height:180px; object-fit:cover; }
        .docente-body { padding: 1rem 1.2rem; text-align:center; }
        .docente-nombre { font-size:1.1rem; font-weight:600; color:#333; }
        .docente-area { font-size:0.95rem; color:#6c757d; margin-bottom:0.5rem; }
        .status-badge { display:inline-block; padding:0.35rem 0.75rem; border-radius:20px; font-size:0.8rem; font-weight:600; }
        .status-green { background-color:#d4edda; color:#155724; }
        .status-yellow { background-color:#fff3cd; color:#856404; }
        .status-red { background-color:#f8d7da; color:#721c24; }
</style>
</head>
<body>

<div class="header">
    <h1>Kiosko - Solicitud de Consulta</h1>
    <img src="logo.png" alt="Logo" style="height:40px;">
</div>

<!-- Cards de docentes (similar a verDisponibilidad) -->
<?php
        $jsonPath = __DIR__ . '/../config/detalles.json';
        $cardsHtml = '';
        if (file_exists($jsonPath)) {
                $jsonContent = file_get_contents($jsonPath);
                $detalles = json_decode($jsonContent, true);
                if (is_array($detalles) && count($detalles) > 0) {
                        // Agrupar por docente
                        $docentes = [];
                        foreach ($detalles as $d) {
                                $nombre = trim(($d['nombre_docente'] ?? '') . ' ' . ($d['apellido_docente'] ?? ''));
                                if ($nombre === '') continue;
                                if (!isset($docentes[$nombre])) $docentes[$nombre] = [];
                                $docentes[$nombre][] = $d;
                        }

                        $cardsHtml .= '<div class="container py-4">';
                        $cardsHtml .= '<h3 class="mb-4 text-center">Docentes disponibles</h3>';
                        $cardsHtml .= '<div class="row justify-content-center g-4">';
                        $rnd = 1;
                        foreach ($docentes as $nombre => $list) {
                                $prim = $list[0];
                                $estado = $prim['estado_disponibilidad'] ?? ($prim['estado'] ?? 'disponible');
                                $aula = $prim['aula'] ?? '';
                                $estadoCls = 'status-green';
                                $estadoLabel = 'Disponible';
                                if ($estado === 'ocupado') { $estadoCls = 'status-red'; $estadoLabel = 'Atendiendo estudiante'; }
                                if ($estado === 'revisando' || $estado === 'reunion' || $estado === 'laboratorio') { $estadoCls = 'status-yellow'; $estadoLabel = ucfirst($estado); }

                                $cardsHtml .= '<div class="col-md-4 col-sm-6">';
                                $cardsHtml .= '<div class="docente-card">';
                                $cardsHtml .= '<img src="https://picsum.photos/400/200?random=' . $rnd . '" alt="Docente" class="docente-img">';
                                $cardsHtml .= '<div class="docente-body">';
                                $cardsHtml .= '<p class="docente-nombre">' . htmlspecialchars($nombre) . '</p>';
                                
                                $cardsHtml .= '<span class="status-badge ' . $estadoCls . '">' . $estadoLabel . '</span>';
                                $cardsHtml .= '<div class="mt-3">';
                                if (strtolower($estado) === 'disponible') {
                                    $cardsHtml .= '<button class="btn btn-primary btn-solicitar" data-docente="' . htmlspecialchars($nombre) . '" data-estado="' . htmlspecialchars($estado) . '" data-bs-toggle="modal" data-bs-target="#solicitarModal">Solicitar</button>';
                                } else {
                                    // Mostrar botón no habilitado cuando el docente no está disponible
                                    $cardsHtml .= '<button class="btn btn-secondary" disabled data-docente="' . htmlspecialchars($nombre) . '" data-estado="' . htmlspecialchars($estado) . '">No disponible</button>';
                                }
                                $cardsHtml .= '</div></div></div></div>';
                                $rnd++;
                        }
                        $cardsHtml .= '</div></div>';
                }
        }
        echo $cardsHtml;
?>

<!-- Modal con el formulario para solicitar consulta -->
<div class="modal fade" id="solicitarModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background:#1976D2;color:#fff;">
                <h5 class="modal-title">Solicitar Consulta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-container">
                        <form id="formConsulta">
                                <input type="hidden" id="docenteInput" name="docente">
                                <label for="carnet">Ingrese su Carnet:</label>
                                <input type="text" id="carnet" name="carnet" placeholder="Ingrese su carné" required>

                                <label for="materia">Materia a consultar:</label>
                                <input type="text" id="materia" name="materia" placeholder="Ej: Matemáticas" required>

                                
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success">Enviar Solicitud</button>
                                </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
async function cargarDocentes() {
    try {
        const response = await fetch('../config/detalles.json');
        const datos = await response.json();

        const select = $('#docenteSelect');
        select.empty();
            // Si no existe el select (ahora usamos cards + modal), no hacer nada
            if (typeof $ === 'function' && select.length === 0) return;

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
        if (typeof $ === 'function' && $('#docenteSelect').length) {
            $('#docenteSelect').html('<option value="">Error al cargar docentes</option>');
        }
    }
}

document.addEventListener('DOMContentLoaded', function(){
    cargarDocentes();

    // Cuando se hace click en Solicitar, prefijar el docente en el formulario modal
    document.querySelectorAll('.btn-solicitar').forEach(btn => {
        btn.addEventListener('click', function(){
            const nombre = this.getAttribute('data-docente') || '';
            document.getElementById('docenteInput').value = nombre;
            // actualizar título del modal
            const modalTitle = document.querySelector('#solicitarModal .modal-title');
            if(modalTitle) modalTitle.textContent = 'Solicitar Consulta - ' + nombre;
        });
    });

    document.getElementById('formConsulta').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validar campos
    const carnet = document.getElementById('carnet').value.trim();
    const materia = document.getElementById('materia').value.trim();
    const descripcion = document.getElementById('descripcion').value.trim();
    const docente = (document.getElementById('docenteInput') && document.getElementById('docenteInput').value) || (document.getElementById('docenteSelect') && document.getElementById('docenteSelect').value) || '';

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

    // Cerrar modal
    try {
      const modalEl = document.getElementById('solicitarModal');
      const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
      modal.hide();
    } catch(e) {}

    // Limpia formulario
    this.reset();
})
});
</script>

</body>
</html>
