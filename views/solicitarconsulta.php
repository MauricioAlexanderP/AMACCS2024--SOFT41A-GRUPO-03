
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

    <!-- Icons used by vistaDocente search field -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet" />

    <style>
        :root {
            --azul: #1976D2;
            --gris-text: #333;
        }

        body {
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
            color: var(--gris-text);
            font-family: Arial, Helvetica, sans-serif;
        }

        .header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
        }

        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto 30px;
        }

        .form-container label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }

        .form-container input,
        .form-container textarea,
        .form-container select,
        .form-container button {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        .form-container textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* 🔹 Separar el botón del Select2 */
        .select2-container {
            margin-bottom: 20px !important;
        }

        .form-container button {
            background: var(--azul);
            color: #fff;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
        }

        .form-container button:hover {
            background: #1565C0;
        }

        h2 {
            text-align: center;
            margin-bottom: 12px;
        }

        /* Styles para tarjetas similar a verDisponibilidad */
        .docente-card {
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .docente-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .docente-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .docente-body {
            padding: 1rem 1.2rem;
            text-align: center;
        }
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



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <div class="header">
        <h1>Kiosko - Solicitud de Consulta</h1>
        <img src="../img/logo.png" alt="Logo" style="height:40px;">
    </div>

    <!-- Campo de búsqueda similar a vistaDocente -->
    <div class="d-flex justify-content-end mb-3">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="buscarDocente" placeholder="Buscar docente...">
            </div>
        </div>
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
                $cardsHtml .= '<div class="row justify-content-center g-4" id="listaDocentes">';
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

                            <label for="descripcion">Descripción de la consulta:</label>
                            <textarea id="descripcion" name="descripcion" placeholder="Describa brevemente su consulta..." required></textarea>

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

        document.addEventListener('DOMContentLoaded', function() {
            cargarDocentes();

            // Información importante (botón)
            const infoBtn = document.getElementById('infoBtn');
            if (infoBtn) {
                infoBtn.addEventListener('click', function mostrarInformacion() {
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
                        <h3 style="margin: 0 0 5px; font-size: 16px;">Notificación automática</h3>
                        <p style="margin: 0; color: #6c757d; font-size: 14px;">Al enviar su solicitud, el docente será notificado mediante <strong>correo electrónico</strong> y <strong>mensaje de voz</strong> para atenderlo lo antes posible.</p>
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
                });
            }

            // Cuando se hace click en Solicitar, prefijar el docente en el formulario modal
            document.querySelectorAll('.btn-solicitar').forEach(btn => {
                btn.addEventListener('click', function() {
                    const nombre = this.getAttribute('data-docente') || '';
                    document.getElementById('docenteInput').value = nombre;
                    // actualizar título del modal
                    const modalTitle = document.querySelector('#solicitarModal .modal-title');
                    if (modalTitle) modalTitle.textContent = 'Solicitar Consulta - ' + nombre;
                });
            });

            // Funciones para detectar si un docente está en clase ahora y deshabilitar su botón
            function parseTimeToMinutes(t) {
                if (!t) return null;
                const parts = ('' + t).trim().split(':');
                if (parts.length < 2) return null;
                const h = parseInt(parts[0], 10);
                const m = parseInt(parts[1], 10) || 0;
                if (Number.isNaN(h) || Number.isNaN(m)) return null;
                return h * 60 + m;
            }

            async function actualizarEstadoEnClase() {
                try {
                    const resp = await fetch('../config/detalles.json');
                    if (!resp.ok) return;
                    const datos = await resp.json();
                    if (!Array.isArray(datos)) return;

                    const dias = ["domingo", "lunes", "martes", "miercoles", "jueves", "viernes", "sabado"];
                    const ahora = new Date();
                    const hoyRaw = dias[ahora.getDay()];
                    const hoy = ('' + hoyRaw).toLowerCase();
                    // normalize (remove accents) helper
                    const normalize = s => ('' + s).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                    const hoyNorm = normalize(hoy);
                    const ahoraMin = ahora.getHours() * 60 + ahora.getMinutes();

                    // Agrupar por docente
                    const porDocente = {};
                    datos.forEach(d => {
                        const nombre = `${d.nombre_docente || ''} ${d.apellido_docente || ''}`.trim();
                        if (!nombre) return;
                        if (!porDocente[nombre]) porDocente[nombre] = [];
                        porDocente[nombre].push(d);
                    });

                    Object.keys(porDocente).forEach(nombre => {
                        const clases = porDocente[nombre];
                        const enClase = clases.some(c => {
                            const diaRaw = (c.dia || '') + '';
                            const diaNorm = normalize(diaRaw);
                            if (diaNorm !== hoyNorm) return false;
                            const ha = parseTimeToMinutes(c.ha || c.hora_inicio || c.h_inicio || c.hora || c.h_inicio_raw);
                            const hf = parseTimeToMinutes(c.hf || c.hora_fin || c.h_fin || c.hora_fin_raw);
                            if (ha === null || hf === null) return false;
                            if (hf < ha) return false;
                            return ahoraMin >= ha && ahoraMin < hf;
                        });

                        console.debug('[actualizarEstadoEnClase] docente:', nombre, 'enClase:', enClase);

                        // Actualizar botones/badges en DOM (comparación normalizada de nombres)
                        const normalizeName = s => (''+s).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g,'');
                        document.querySelectorAll('.btn-solicitar[data-docente]').forEach(btn => {
                            if (normalizeName(btn.getAttribute('data-docente') || '') === normalizeName(nombre)) {
                                if (enClase) {
                                    btn.disabled = true;
                                    btn.classList.remove('btn-primary');
                                    btn.classList.add('btn-secondary');
                                    btn.setAttribute('data-estado', 'en_clase');
                                } else {
                                    // only enable if it wasn't explicitly non-available (e.g., ocupado via SSE)
                                    const current = (btn.getAttribute('data-estado') || '').toLowerCase();
                                    if (!current || current === 'disponible' || current === 'en_clase') {
                                        btn.disabled = false;
                                        btn.classList.remove('btn-secondary');
                                        btn.classList.add('btn-primary');
                                        btn.setAttribute('data-estado', 'disponible');
                                    }
                                }
                            }
                        });

                        // actualizar badge si existe
                        document.querySelectorAll('.status-badge').forEach(b => {
                            if (normalizeName(b.getAttribute('data-docente') || '') === normalizeName(nombre)) {
                                if (enClase) {
                                    b.textContent = 'En clase';
                                    b.className = 'status-badge status-yellow';
                                } else {
                                    // no cambiar si badge muestra otro estado gestionado por SSE
                                }
                            }
                        });
                    });
                } catch (err) {
                    console.warn('No se pudo actualizar estado en clase:', err);
                }
            }

            // Ejecutar al cargar y cada minuto para mantener sincronizado con el reloj del cliente
            actualizarEstadoEnClase();
            setInterval(actualizarEstadoEnClase, 5 * 1000);

            // Consolidated submit handler: validates student, sends request, triggers speech and feedback
            document.getElementById('formConsulta').addEventListener('submit', async function(e) {
                e.preventDefault();

                const form = this;
                const carnet = (form.querySelector('#carnet') && form.querySelector('#carnet').value.trim()) || '';
                const materia = (form.querySelector('#materia') && form.querySelector('#materia').value.trim()) || '';
                const descripcion = (form.querySelector('#descripcion') && form.querySelector('#descripcion').value.trim()) || '';
                const docente = (form.querySelector('#docenteInput') && form.querySelector('#docenteInput').value) || (form.querySelector('#docenteSelect') && form.querySelector('#docenteSelect').value) || '';
                const submitBtn = form.querySelector('button[type="submit"]') || document.getElementById('submitBtn');

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
                if (submitBtn) {
                    submitBtn.innerHTML = '<div class="loading"></div> Procesando...';
                    submitBtn.disabled = true;
                }

                try {
                    const buscarUrl = '../controller/buscarAlumno.php?carnet=' + encodeURIComponent(carnet);
                    const resp = await fetch(buscarUrl);

                    if (!resp.ok) {
                        throw new Error(`HTTP error! status: ${resp.status}`);
                    }

                    const responseText = await resp.text();
                    console.log('Respuesta buscarAlumno:', responseText);

                    let data;
                    try {
                        data = JSON.parse(responseText);
                    } catch (parseError) {
                        console.error('❌ Error al parsear JSON de buscarAlumno:', parseError);
                        console.error('❌ Texto de respuesta:', responseText);
                        throw new Error('Respuesta del servidor no es JSON válido');
                    }

                    if (!data.apellido) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Carnet inválido',
                            text: 'No existe un alumno con ese carnet.',
                            confirmButtonColor: '#1976D2'
                        });

                        // Restaurar el botón
                        if (submitBtn) {
                            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Solicitud';
                            submitBtn.disabled = false;
                        }
                        return;
                    }

                    const apellido = data.apellido;
                    const docenteNombre = docente;

                    // Enviar petición al controlador que envía el correo
                    const formData = new FormData();
                    formData.append('carnet', carnet);
                    formData.append('materia', materia);
                    formData.append('descripcion', descripcion);
                    formData.append('docente', docenteNombre);

                    console.log('Enviando correo automático...');

                    try {
                        const emailResponse = await fetch('../controller/solicitarConsulta.php', {
                            method: 'POST',
                            body: formData
                        });

                        if (!emailResponse.ok) {
                            throw new Error(`HTTP error! status: ${emailResponse.status}`);
                        }

                        const responseText2 = await emailResponse.text();
                        console.log('Respuesta del servidor:', responseText2);

                        let emailResult;
                        try {
                            emailResult = JSON.parse(responseText2);
                        } catch (parseError) {
                            console.warn('Respuesta no JSON en enviar correo, texto:', responseText2);
                            emailResult = { success: false, message: responseText2 };
                        }

                        if (emailResult.success) {
                            console.log('✅ Correo enviado exitosamente');
                        } else {
                            console.warn('❌ Error al enviar correo:', emailResult.message);
                        }
                    } catch (emailError) {
                        console.error('❌ Error en el envío de correo:', emailError);
                        // continuar con la voz aunque falle el correo
                    }

                    // Mensaje de voz (si está disponible)
                    if ('speechSynthesis' in window) {
                        const repeticiones = 3;
                        const intervalo = 5000; // 5 segundos
                        for (let i = 0; i < repeticiones; i++) {
                            setTimeout(() => {
                                try {
                                    const mensaje = new SpeechSynthesisUtterance(`Estudiante ${apellido} solicita al docente ${docenteNombre}.`);
                                    mensaje.lang = 'es-ES';
                                    mensaje.rate = 0.9;
                                    mensaje.volume = 1.0;
                                    speechSynthesis.speak(mensaje);
                                } catch (sErr) {
                                    console.warn('SpeechSynthesis error', sErr);
                                }
                            }, 2000 + (i * intervalo));
                        }
                    }

                    await Swal.fire({
                        icon: 'success',
                        title: 'Solicitud enviada exitosamente',
                        html: `
                <div style="text-align: left; margin-top: 15px;">
                    <p><strong>Estudiante:</strong> ${apellido} (${carnet})</p>
                    <p><strong>Docente solicitado:</strong> ${docenteNombre}</p>
                    <p><strong>Materia:</strong> ${materia}</p>
                </div>
            `,
                        confirmButtonColor: '#1976D2'
                    });

                    // Reset y cierre de modal si aplica
                    try {
                        const modalEl = document.getElementById('solicitarModal');
                        if (modalEl) {
                            const modal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                            modal.hide();
                        }
                    } catch (err) {}

                    form.reset();
                    if (window.$ && $('#docenteSelect').length) $('#docenteSelect').val(null).trigger('change');

                } catch (error) {
                    console.error('Error al procesar la solicitud:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ha ocurrido un error al procesar su solicitud. Inténtelo nuevamente.',
                        confirmButtonColor: '#1976D2'
                    });
                } finally {
                    if (submitBtn) {
                        submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Solicitud';
                        submitBtn.disabled = false;
                    }
                }
            });
        });
    </script>

        <!-- Script: filtrado por búsqueda en tiempo real (estructura solicitada) -->
        <script>
            (function() {
                const inputBusqueda = document.getElementById('buscarDocente');
                const listaDocentes = document.getElementById('listaDocentes');

                const normalize = s => ('' + (s || '')).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

                if (inputBusqueda && listaDocentes) {
                    inputBusqueda.addEventListener('input', function(e) {
                        const busqueda = normalize((e.target.value || '').toLowerCase().trim());
                        // Seleccionar las columnas que contienen las tarjetas (tolerante a diferentes clases)
                        const tarjetas = listaDocentes.querySelectorAll('.col-md-4, .col-sm-6, .docente-card');

                        Array.from(tarjetas).forEach(tarjetaWrapper => {
                            // Si la tarjeta fue pasada directamente como .docente-card, buscar el wrapper col
                            let tarjeta = tarjetaWrapper.classList && tarjetaWrapper.classList.contains('docente-card') ? tarjetaWrapper : tarjetaWrapper.querySelector('.docente-card') || tarjetaWrapper;

                            const nombreEl = tarjeta.querySelector ? tarjeta.querySelector('.docente-nombre') : null;
                            const areaEl = tarjeta.querySelector ? tarjeta.querySelector('.docente-area') : null;
                            const nombreDocente = nombreEl ? normalize(nombreEl.textContent || '') : '';
                            const aula = areaEl ? normalize(areaEl.textContent || '') : '';

                            if (nombreDocente.includes(busqueda) || aula.includes(busqueda)) {
                                // mostrar el elemento columna si existe, si no mostrar la tarjeta
                                if (tarjetaWrapper.style) tarjetaWrapper.style.display = '';
                            } else {
                                if (tarjetaWrapper.style) tarjetaWrapper.style.display = 'none';
                            }
                        });
                    });
                }
            })();
        </script>

    <!-- SSE client: escucha cambios en views/sse.php y actualiza badges/botones en tiempo real -->
    <script>
        (function() {
            if (typeof EventSource === 'undefined') return;
            try {
                const es = new EventSource('../scripts/sse.php');
                const classMap = {
                    'disponible': 'status-green',
                    'ocupado': 'status-red'
                };
                es.onmessage = function(e) {
                    try {
                        const datos = JSON.parse(e.data || '{}');
                        // helper to normalize strings (remove accents and lowercase)
                        const normalizeName = s => ('' + (s || '')).toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');

                        Object.keys(datos).forEach(function(nombre) {
                            const info = datos[nombre] || {};
                            const estado = (info.estado || '').toLowerCase();
                            const nombreNorm = normalizeName(nombre);

                            // actualizar badges (comparación normalizada)
                            document.querySelectorAll('.status-badge').forEach(function(b) {
                                if (normalizeName(b.getAttribute('data-docente')) === nombreNorm) {
                                    const label = (estado === 'ocupado') ? 'Atendiendo estudiante' : (estado === 'disponible' ? 'Disponible' : (estado ? estado.charAt(0).toUpperCase() + estado.slice(1) : 'Desconocido'));
                                    b.textContent = label;
                                    b.className = 'status-badge ' + (classMap[estado] || 'status-yellow');
                                }
                            });

                            // actualizar botones (comparación normalizada)
                            document.querySelectorAll('button[data-docente]').forEach(function(btn) {
                                if (normalizeName(btn.getAttribute('data-docente')) === nombreNorm) {
                                    if (estado === 'disponible') {
                                        btn.disabled = false;
                                        btn.classList.remove('btn-secondary');
                                        btn.classList.add('btn-primary');
                                        btn.setAttribute('data-estado', 'disponible');
                                    } else {
                                        btn.disabled = true;
                                        btn.classList.remove('btn-primary');
                                        btn.classList.add('btn-secondary');
                                        btn.setAttribute('data-estado', estado || 'no-disponible');
                                    }
                                }
                            });
                        });

                        // Después de aplicar los cambios que vienen por SSE, re-evaluar quién está "En clase"
                        // para que la lógica de horarios tenga prioridad sobre la disponibilidad editada.
                        try {
                            // give DOM a tiny moment to settle
                            setTimeout(() => {
                                if (typeof actualizarEstadoEnClase === 'function') actualizarEstadoEnClase();
                            }, 50);
                        } catch (e2) {
                            console.warn('No se pudo re-ejecutar actualizarEstadoEnClase tras SSE', e2);
                        }
                    } catch (err) {
                        console.error('SSE JSON parse error', err);
                    }
                };
                es.onerror = function() {
                    /* navegador reconectará automáticamente */
                };
            } catch (e) {
                console.error('SSE init error', e);
            }
        })();
    </script>

</body>

</html>