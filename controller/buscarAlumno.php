<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificar que sea GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
  echo json_encode(['success' => false, 'message' => 'Método no permitido']);
  exit;
}

// Obtener carnet
$carnet = $_GET['carnet'] ?? '';

if (empty($carnet)) {
  echo json_encode(['success' => false, 'message' => 'Carnet requerido']);
  exit;
}

try {
  // Cargar modelo
  require_once dirname(__DIR__) . '/model/alumno.php';

  // Consultar alumno
  $alumnoModel = new Alumno();
  $resultado = $alumnoModel->get_alumno($carnet);

  if (!$resultado || $resultado->num_rows === 0) {
    echo json_encode([
      'success' => false,
      'message' => 'Carnet no encontrado',
      'apellido' => null
    ]);
    exit;
  }

  // Obtener datos
  $fila = $resultado->fetch_assoc();
  $apellido = $fila['apellido'] ?? 'Desconocido';

  // Respuesta exitosa
  echo json_encode([
    'success' => true,
    'apellido' => $apellido,
    'carnet' => $carnet
  ]);
} catch (Exception $e) {
  echo json_encode([
    'success' => false,
    'message' => 'Error en el servidor: ' . $e->getMessage(),
    'apellido' => null
  ]);
}
