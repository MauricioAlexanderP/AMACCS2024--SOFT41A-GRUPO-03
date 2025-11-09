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
            --box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
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
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .info-btn:hover {
            background: #e68900;
            transform: scale(1.05);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
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
            border: 3px solid rgba(255, 255, 255, .3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
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
                const resp = await fetch('../controller/buscarAlumno.php?carnet=' + carnet);

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
                    submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Enviar Solicitud';
                    submitBtn.disabled = false;
                    return;
                }

                const apellido = data.apellido;
                const docenteNombre = docente;

                // ✅ ENVIAR CORREO AUTOMÁTICO VÍA SMTP
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

                    // Verificar si la respuesta es exitosa
                    if (!emailResponse.ok) {
                        throw new Error(`HTTP error! status: ${emailResponse.status}`);
                    }

                    // Obtener el texto primero para debugging
                    const responseText = await emailResponse.text();
                    console.log('Respuesta del servidor:', responseText);

                    // Intentar parsear como JSON
                    let emailResult;
                    try {
                        emailResult = JSON.parse(responseText);
                    } catch (parseError) {
                        console.error('❌ Error al parsear JSON:', parseError);
                        console.error('❌ Texto de respuesta:', responseText);
                        throw new Error('Respuesta del servidor no es JSON válido');
                    }

                    if (emailResult.success) {
                        console.log('✅ Correo enviado exitosamente');
                    } else {
                        console.error('❌ Error al enviar correo:', emailResult.message);
                        // Continúa con el mensaje de voz aunque falle el correo
                    }
                } catch (emailError) {
                    console.error('❌ Error en el envío de correo:', emailError);
                    // Continúa con el mensaje de voz aunque falle el correo
                }


                let repeticiones = 3;
                let intervalo = 5000; // 5 segundos entre repeticiones

                for (let i = 0; i < repeticiones; i++) {
                    setTimeout(() => {
                        const mensaje = new SpeechSynthesisUtterance(
                            `Estudiante ${apellido} solicita al docente ${docenteNombre}.`
                        );
                        mensaje.lang = "es-ES";
                        mensaje.rate = 0.9; // velocidad más clara
                        mensaje.volume = 1.0; // volumen máximo
                        speechSynthesis.speak(mensaje);
                    }, 2000 + (i * intervalo));
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
    </script>

</body>

</html>