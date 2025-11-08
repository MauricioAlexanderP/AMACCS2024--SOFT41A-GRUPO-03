<?php
session_start();
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Importar PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Verificar que los archivos necesarios existan
$requiredFiles = [
  'model' => dirname(__DIR__) . '/model/alumno.php',
  'config' => dirname(__DIR__) . '/config/email_config.php',
  'vendor' => dirname(__DIR__) . '/vendor/autoload.php'
];

foreach ($requiredFiles as $key => $file) {
  if (!file_exists($file)) {
    echo json_encode([
      'success' => false,
      'message' => "Archivo requerido no encontrado: $key",
      'file' => $file,
      'debug' => 'Verifique que composer install se haya ejecutado correctamente'
    ]);
    exit;
  }
}

try {
  require dirname(__DIR__) . '/vendor/autoload.php';
  require_once dirname(__DIR__) . '/model/alumno.php';

  // Cargar configuración de correo
  $emailConfig = require dirname(__DIR__) . '/config/email_config.php';
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => 'Error al cargar archivos requeridos',
    'error' => $e->getMessage(),
    'trace' => $e->getTraceAsString()
  ]);
  exit;
}

// Validar que sea una petición POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  echo json_encode(['success' => false, 'message' => 'Método no permitido']);
  exit;
}

// Obtener los datos del formulario
$carnet = $_POST['carnet'] ?? '';
$materia = $_POST['materia'] ?? '';
$descripcion = $_POST['descripcion'] ?? '';
$docente = $_POST['docente'] ?? '';

// Validar que todos los campos estén completos
if (empty($carnet) || empty($materia) || empty($descripcion) || empty($docente)) {
  echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios']);
  exit;
}

try {
  // Paso 2: Consultar el alumno usando el método get_alumno
  $alumnoModel = new Alumno();
  $resultado = $alumnoModel->get_alumno($carnet);

  if (!$resultado || $resultado->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Carnet no encontrado en el sistema']);
    exit;
  }

  // Obtener el apellido del alumno
  $fila = $resultado->fetch_assoc();
  $apellido = $fila['apellido'] ?? 'Desconocido';

  // Paso 3: Preparar y enviar el correo electrónico
  $emailMode = $emailConfig['EMAIL_MODE'] ?? 'test';

  if ($emailMode === 'mailto') {
    // Modo mailto - generar enlace para abrir cliente de correo del usuario

    $subject = "Nueva solicitud de consulta - " . $docente;
    $body = "Estimado/a {$docente},%0D%0A%0D%0A";
    $body .= "Ha recibido una nueva solicitud de consulta de un estudiante:%0D%0A%0D%0A";
    $body .= "Estudiante: {$apellido} (Carnet: {$carnet})%0D%0A";
    $body .= "Materia a consultar: {$materia}%0D%0A";
    $body .= "Descripción: {$descripcion}%0D%0A%0D%0A";
    $body .= "Por favor, prepare la atención para este estudiante.%0D%0A%0D%0A";
    $body .= "----%0D%0A";
    $body .= "Este es un mensaje automático del Sistema de Consultas ITCA";

    // Codificar para URL
    $subject = rawurlencode($subject);
    $to = $emailConfig['TEST_EMAIL'];

    $mailtoLink = "mailto:{$to}?subject={$subject}&body={$body}";

    // Respuesta exitosa para modo mailto
    echo json_encode([
      'success' => true,
      'message' => 'Solicitud procesada correctamente. Se abrirá tu cliente de correo.',
      'data' => [
        'carnet' => $carnet,
        'apellido' => $apellido,
        'docente' => $docente,
        'materia' => $materia,
        'mode' => 'MAILTO',
        'mailto_link' => $mailtoLink
      ]
    ]);
  } elseif ($emailMode === 'test') {
    // Modo de prueba - simular envío sin SMTP real

    // Crear un log del correo simulado
    $logPath = dirname(__DIR__) . '/logs';
    if (!file_exists($logPath)) {
      mkdir($logPath, 0755, true);
    }

    $emailLog = [
      'timestamp' => date('Y-m-d H:i:s'),
      'to' => $emailConfig['TEST_EMAIL'],
      'from' => $emailConfig['FROM_EMAIL'],
      'subject' => 'Nueva solicitud de consulta',
      'docente' => $docente,
      'estudiante' => $apellido,
      'carnet' => $carnet,
      'materia' => $materia,
      'descripcion' => $descripcion,
      'status' => 'SIMULADO - NO ENVIADO'
    ];

    file_put_contents(
      $logPath . '/email_log.json',
      json_encode($emailLog, JSON_PRETTY_PRINT) . "\n",
      FILE_APPEND | LOCK_EX
    );

    // Respuesta exitosa para modo de prueba
    echo json_encode([
      'success' => true,
      'message' => '✅ Solicitud procesada correctamente. (MODO PRUEBA: El correo fue simulado y guardado en logs/email_log.json)',
      'data' => [
        'carnet' => $carnet,
        'apellido' => $apellido,
        'docente' => $docente,
        'materia' => $materia,
        'mode' => 'TEST_MODE',
        'log_file' => 'logs/email_log.json'
      ]
    ]);
  } elseif ($emailMode === 'smtp') {
    // Modo SMTP real - envío desde servidor
    $mail = new PHPMailer(true);

    // Configuración del servidor SMTP usando el archivo de configuración
    $mail->isSMTP();
    $mail->Host       = $emailConfig['SMTP_HOST'];
    $mail->SMTPAuth   = true;
    $mail->Username   = $emailConfig['SMTP_USERNAME'];
    $mail->Password   = $emailConfig['SMTP_PASSWORD'];
    $mail->SMTPSecure = $emailConfig['SMTP_SECURE'] === 'ssl' ? PHPMailer::ENCRYPTION_SMTPS : PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = $emailConfig['SMTP_PORT'];
    $mail->SMTPDebug  = $emailConfig['DEBUG_MODE'];

    // Configuración del correo
    $mail->setFrom($emailConfig['FROM_EMAIL'], $emailConfig['FROM_NAME']);
    $mail->addAddress($emailConfig['TEST_EMAIL']); // Correo del docente (prueba)

    // Contenido del correo
    $mail->isHTML(true);
    $mail->CharSet = $emailConfig['CHARSET'];
    $mail->Subject = 'Nueva solicitud de consulta';

    $mail->Body = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; }
            .header { background-color: #1976D2; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
            .content { background-color: white; padding: 30px; border-radius: 0 0 5px 5px; }
            .info-row { margin: 15px 0; padding: 10px; background-color: #f5f5f5; border-left: 4px solid #1976D2; }
            .label { font-weight: bold; color: #1976D2; }
            .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h2>Nueva Solicitud de Consulta</h2>
            </div>
            <div class='content'>
                <p>Estimado/a <strong>{$docente}</strong>,</p>
                <p>Ha recibido una nueva solicitud de consulta de un estudiante:</p>
                
                <div class='info-row'>
                    <span class='label'>Estudiante:</span> {$apellido} (Carnet: {$carnet})
                </div>
                
                <div class='info-row'>
                    <span class='label'>Materia a consultar:</span> {$materia}
                </div>
                
                <div class='info-row'>
                    <span class='label'>Descripción:</span><br>
                    {$descripcion}
                </div>
                
                <p style='margin-top: 30px;'>Por favor, prepare la atención para este estudiante.</p>
                
                <div class='footer'>
                    <p>Este es un mensaje automático del Sistema de Consultas ITCA</p>
                    <p>Por favor no responda a este correo</p>
                </div>
            </div>
        </div>
    </body>
    </html>
    ";

    $mail->AltBody = "Nueva solicitud de consulta\n\n"
      . "Docente: {$docente}\n"
      . "Estudiante: {$apellido} (Carnet: {$carnet})\n"
      . "Materia: {$materia}\n"
      . "Descripción: {$descripcion}";

    // Enviar el correo
    $mail->send();

    // Respuesta exitosa para modo real
    echo json_encode([
      'success' => true,
      'message' => 'Solicitud enviada correctamente. El docente ha sido notificado por correo electrónico.',
      'data' => [
        'carnet' => $carnet,
        'apellido' => $apellido,
        'docente' => $docente,
        'materia' => $materia,
        'mode' => 'REAL_SMTP'
      ]
    ]);
  } else {
    // Modo no válido
    echo json_encode([
      'success' => false,
      'message' => "Modo de correo no válido: {$emailMode}. Debe ser 'mailto', 'smtp' o 'test'",
      'type' => 'config_error'
    ]);
  }
} catch (\PHPMailer\PHPMailer\Exception $e) {
  // Error específico de PHPMailer
  echo json_encode([
    'success' => false,
    'message' => 'Error al enviar el correo electrónico',
    'error' => isset($mail) ? $mail->ErrorInfo : $e->getMessage(),
    'type' => 'phpmailer_error'
  ]);
} catch (Exception $e) {
  // Error general del servidor
  echo json_encode([
    'success' => false,
    'message' => 'Error en el servidor',
    'error' => $e->getMessage(),
    'file' => $e->getFile(),
    'line' => $e->getLine(),
    'type' => 'server_error'
  ]);
}
