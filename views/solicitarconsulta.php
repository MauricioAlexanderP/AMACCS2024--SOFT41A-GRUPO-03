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
<title>Kiosko - Solicitud de Consulta</title>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --primary: #1976D2;
        --primary-dark: #1565C0;
        --primary-light: #42A5F5;
        --secondary: #FF9800;
        --success: #4CAF50;
        --warning: #FFC107;
        --danger: #F44336;
        --light: #f8f9fa;
        --dark: #212529;
        --gray: #6c757d;
        --gray-light: #e9ecef;
        --border-radius: 12px;
        --box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        --transition: all 0.3s ease;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        margin: 0;
        padding: 20px;
        color: var(--dark);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container {
        max-width: 600px;
        width: 100%;
        margin: 0 auto;
    }

    .header {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 30px;
        padding: 20px;
        background: white;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .logo-container {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .logo {
        width: 80px;
        height: 80px;
        object-fit: contain;
    }

    .header-text h1 {
        font-size: 28px;
        margin: 0;
        color: var(--primary);
        font-weight: 700;
    }

    .header-text p {
        margin: 5px 0 0;
        color: var(--gray);
        font-size: 16px;
    }

    .form-container {
        background: white;
        padding: 30px;
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        transition: var(--transition);
        position: relative;
    }

    .form-container:hover {
        box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    }

    .form-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--gray-light);
    }

    .form-title {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .form-title i {
        color: var(--primary);
        font-size: 24px;
    }

    .form-title h2 {
        font-size: 22px;
        margin: 0;
        color: var(--dark);
    }

    .info-btn {
        background: var(--secondary);
        color: white;
        border: none;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 18px;
        transition: var(--transition);
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }

    .info-btn:hover {
        background: #e68900;
        transform: scale(1.05);
        box-shadow: 0 6px 15px rgba(0,0,0,0.15);
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--dark);
        font-size: 15px;
    }

    .form-control {
        width: 100%;
        padding: 12px 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        font-size: 15px;
        transition: var(--transition);
        background-color: white;
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.2);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .select2-container {
        margin-bottom: 20px !important;
    }

    .select2-container--default .select2-selection--single {
        border: 1px solid #ddd;
        border-radius: 8px;
        height: 46px;
        padding: 8px;
    }

    .select2-container--default .select2-selection--single:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(25, 118, 210, 0.2);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 24px;
        border-radius: 8px;
        border: none;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: var(--transition);
        width: 100%;
    }

    .btn-primary {
        background: var(--primary);
        color: white;
    }

    .btn-primary:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(25, 118, 210, 0.3);
    }

    .footer {
        text-align: center;
        margin-top: 40px;
        padding: 20px;
        color: var(--gray);
        font-size: 14px;
    }

    .loading {
        display: inline-block;
        width: 20px;
        height: 20px;
        border: 3px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 1s ease-in-out infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    @media (max-width: 768px) {
        .header {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }
        
        .form-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
        
        .info-btn {
            align-self: flex-end;
        }
    }
</style>
</head>
<body>

<div class="container">
    <div class="header">
        <div class="logo-container">
            <img src="../img/logo.png" alt="Logo" class="logo">
            <div class="header-text">
                <h1>Kiosko Académico</h1>
                <p>Solicitud de consultas con docentes</p>
            </div>
        </div>
    </div>

    <div class="form-container">
        <div class="form-header">
            <div class="form-title">
                <i class="fas fa-file-alt"></i>
                <h2>Solicitud de Consulta</h2>
            </div>
            <button class="info-btn" id="infoBtn">
                <i class="fas fa-info"></i>
            </button>
        </div>
        
        <form id="formConsulta">
            <div class="form-group">
                <label for="carnet"><i class="fas fa-id-card"></i> Ingrese su Carnet:</label>
                <input type="text" id="carnet" name="carnet" class="form-control" placeholder="Ej: A12345" required>
            </div>

            <div class="form-group">
                <label for="materia"><i class="fas fa-book"></i> Materia a consultar:</label>
                <input type="text" id="materia" name="materia" class="form-control" placeholder="Ej: Matemáticas, Física, etc." required>
            </div>

            <div class="form-group">
                <label for="descripcion"><i class="fas fa-comment-alt"></i> Descripción de la consulta:</label>
                <textarea id="descripcion" name="descripcion" class="form-control" placeholder="Describa detalladamente su consulta..." required></textarea>
            </div>

            <div class="form-group">
                <label for="docenteSelect"><i class="fas fa-chalkboard-teacher"></i> Seleccione un docente disponible:</label>
                <select id="docenteSelect" name="docente" class="form-control" required>
                    <option value="">Cargando docentes disponibles...</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary" id="submitBtn">
                <i class="fas fa-paper-plane"></i> Enviar Solicitud
            </button>
        </form>
    </div>

    <div class="footer">
        <p>Kiosko Académico - Sistema de consultas estudiantiles</p>
    </div>
</div>

        .docente-body {
            padding: 1rem 1.2rem;
            text-align: center;
        }
<script>
// Función para mostrar información importante
function mostrarInformacion() {
    Swal.fire({
        title: 'Información Importante',
        html: `
            <div style="text-align: left;">
                <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 15px;">
                    <i class="fas fa-clock" style="color: #1976D2; font-size: 18px; margin-top: 3px;"></i>
                    <div>
                        <h3 style="margin: 0 0 5px; font-size: 16px;">Horarios de atención</h3>
                        <p style="margin: 0; color: #6c757d; font-size: 14px;">Las consultas se realizan durante el horario de clases de cada docente. Verifique la disponibilidad antes de solicitar.</p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 15px;">
                    <i class="fas fa-user-check" style="color: #1976D2; font-size: 18px; margin-top: 3px;"></i>
                    <div>
                        <h3 style="margin: 0 0 5px; font-size: 16px;">Docentes disponibles</h3>
                        <p style="margin: 0; color: #6c757d; font-size: 14px;">Solo se muestran los docentes que no tienen clases en este momento. La lista se actualiza automáticamente.</p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: flex-start; gap: 12px; margin-bottom: 15px;">
                    <i class="fas fa-bell" style="color: #1976D2; font-size: 18px; margin-top: 3px;"></i>
                    <div>
                        <h3 style="margin: 0 0 5px; font-size: 16px;">Notificación al docente</h3>
                        <p style="margin: 0; color: #6c757d; font-size: 14px;">Al enviar su solicitud, el docente será notificado mediante un mensaje de voz para atenderlo lo antes posible.</p>
                    </div>
                </div>
                
                <div style="display: flex; align-items: flex-start; gap: 12px;">
                    <i class="fas fa-exclamation-triangle" style="color: #1976D2; font-size: 18px; margin-top: 3px;"></i>
                    <div>
                        <h3 style="margin: 0 0 5px; font-size: 16px;">Datos correctos</h3>
                        <p style="margin: 0; color: #6c757d; font-size: 14px;">Asegúrese de ingresar correctamente su carnet y los detalles de la consulta para una atención eficiente.</p>
                    </div>
                </div>
            </div>
        `,
        width: '600px',
        confirmButtonColor: '#1976D2',
        confirmButtonText: 'Entendido'
    });
}

// Asignar evento al botón de información
document.getElementById('infoBtn').addEventListener('click', mostrarInformacion);

// Manejo del formulario
document.getElementById('formConsulta').addEventListener('submit', async function(e) {
    e.preventDefault();

    const carnet = document.getElementById('carnet').value.trim();
    const materia = document.getElementById('materia').value.trim();
    const descripcion = document.getElementById('descripcion').value.trim();
    const docente = document.getElementById('docenteSelect').value;
    const submitBtn = document.getElementById('submitBtn');

    if (!carnet || !materia || !descripcion || !docente) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos incompletos',
            text: 'Por favor complete todos los campos antes de enviar.',
            confirmButtonColor: '#1976D2'
        });
        return;
    }

    // Cambiar el texto del botón y mostrar indicador de carga
    submitBtn.innerHTML = '<div class="loading"></div> Procesando...';
    submitBtn.disabled = true;

    try {
        // ✅ Obtener apellido del alumno por el carnet
        const resp = await fetch('../controller/buscarAlumno.php?carnet=' + carnet);
        const data = await resp.json();

        if (!data.apellido) {
            Swal.fire({
                icon: 'error',
                title: 'Carnet inválido',
                text: 'No existe un alumno con ese carnet.',
                confirmButtonColor: '#1976D2'
            });
            
            // Restaurar el botón
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Solicitud';
            submitBtn.disabled = false;
            return;
        }

        const apellido = data.apellido;
        const docenteNombre = docente;

        
        let repeticiones = 3;
        let intervalo = 5000; // 5 segundos entre repeticiones

        for (let i = 0; i < repeticiones; i++) {
            setTimeout(() => {
                const mensaje = new SpeechSynthesisUtterance(
                    `Estudiante ${apellido} solicita al docente ${docenteNombre}.`
                );
                mensaje.lang = "es-ES";
                mensaje.rate = 0.9;   // velocidad más clara
                mensaje.volume = 1.0; // volumen máximo
                speechSynthesis.speak(mensaje);
            }, 2000 + (i * intervalo)); 
        }

        await Swal.fire({
            icon: 'success',
            title: 'Solicitud enviada',
            html: `
                <div style="text-align: left; margin-top: 15px;">
                    <p><strong>Estudiante:</strong> ${apellido}</p>
                    <p><strong>Docente solicitado:</strong> ${docenteNombre}</p>
                    <p><strong>Materia:</strong> ${materia}</p>
                </div>
            `,
            confirmButtonColor: '#1976D2'
        });

        
        this.reset();
        $('#docenteSelect').val(null).trigger('change');

    } catch (error) {
        console.error('Error al procesar la solicitud:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ha ocurrido un error al procesar su solicitud. Inténtelo nuevamente.',
            confirmButtonColor: '#1976D2'
        });
    } finally {
        // Restaurar el botón
        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Solicitud';
        submitBtn.disabled = false;
    }
});
</script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        .docente-nombre {
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }

        .docente-area {
            font-size: 0.95rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
        }

        .status-badge {
            display: inline-block;
            padding: 0.35rem 0.75rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .status-green {
            background-color: #d4edda;
            color: #155724;
        }

        .status-yellow {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-red {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Kiosko - Solicitud de Consulta</h1>
        <img src="../img/logo.png" alt="Logo" style="height:40px;">
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
                if ($estado === 'ocupado') {
                    $estadoCls = 'status-red';
                    $estadoLabel = 'Atendiendo estudiante';
                }
                if ($estado === 'revisando' || $estado === 'reunion' || $estado === 'laboratorio') {
                    $estadoCls = 'status-yellow';
                    $estadoLabel = ucfirst($estado);
                }

                $cardsHtml .= '<div class="col-md-4 col-sm-6">';
                $cardsHtml .= '<div class="docente-card">';
                $cardsHtml .= '<img src="https://picsum.photos/400/200?random=' . $rnd . '" alt="Docente" class="docente-img">';
                $cardsHtml .= '<div class="docente-body">';
                $cardsHtml .= '<p class="docente-nombre">' . htmlspecialchars($nombre) . '</p>';

                $cardsHtml .= '<span class="status-badge ' . $estadoCls . '" data-docente="' . htmlspecialchars($nombre) . '">' . $estadoLabel . '</span>';
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
}

document.addEventListener('DOMContentLoaded', cargarDocentes);
</script>

</body>
</html>