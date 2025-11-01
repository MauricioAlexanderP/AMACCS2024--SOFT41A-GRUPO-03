<?php
// Controlador para actualizar la disponibilidad de un docente.
// Soporta peticiones AJAX (devuelve JSON) y peticiones normales (redirige a la vista con mensaje en sesión).

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Cargar el modelo (usar el nombre de fichero real)
$modelPath = __DIR__ . '/../model/detalle.php';
if (!file_exists($modelPath)) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'mensaje' => 'Modelo no encontrado en: ' . $modelPath]);
    exit;
}
require_once $modelPath;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // No es POST -> redirigir a la vista
    header('Location: ../views/vistaDocente.php');
    exit;
}

// Leer y sanitizar entrada
$nombre_docente = trim((string)($_POST['nombre_docente'] ?? ''));
$estado = trim((string)($_POST['estado'] ?? 'disponible'));
$notas = trim((string)($_POST['notas'] ?? ''));

// Validar estado permitido
$allowed = ['disponible','ocupado','revisando','reunion','laboratorio','almuerzo'];
if (!in_array($estado, $allowed, true)) {
    $resp = ['status' => 'error', 'mensaje' => 'Estado inválido.'];
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($resp);
        exit;
    }
    $_SESSION['flash'] = $resp;
    header('Location: ../views/vistaDocente.php');
    exit;
}

// Instanciar modelo y actualizar
try {
    $detalle = new Detalle();
    $resultado = $detalle->editarDisponibilidad($nombre_docente, $estado, $notas);
} catch (Exception $e) {
    $resultado = ['status' => 'error', 'mensaje' => 'Excepción: ' . $e->getMessage()];
}

// Normalizar mensaje de éxito para mostrar solo texto corto
if (is_array($resultado)) {
    $statusLower = strtolower($resultado['status'] ?? '');
    if ($statusLower === 'exito' || $statusLower === 'success') {
        $resultado['mensaje'] = 'actualizado correctamente';
    }
    // Incluir el nombre del docente en la respuesta para mostrar en la UI
    $resultado['nombre'] = $nombre_docente;
} else {
    $resultado = ['status' => 'error', 'mensaje' => 'Respuesta inesperada del modelo', 'nombre' => $nombre_docente];
}

// Responder según tipo de petición
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode($resultado);
    exit;
}

// Petición normal: guardar mensaje en sesión y redirigir
$_SESSION['flash'] = $resultado;
header('Location: ../views/vistaDocente.php');
exit;

?>