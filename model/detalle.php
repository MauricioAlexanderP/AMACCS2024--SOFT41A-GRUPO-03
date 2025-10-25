<?php
require_once 'config/cn.php';

class Detalle extends cn
{
  public function __construct()
  {
    parent::__construct();
  }

  // Obtener todos los detalles
  public function get_detalles()
  {
    $sql = "SELECT * FROM detalle";
    return $this->consulta($sql);
  }

  // Obtener un detalle por ID
  public function get_detalle_by_id($id_detalle)
  {
    $sql = "SELECT * FROM detalle WHERE id_detalle = '$id_detalle'";
    return $this->consulta($sql);
  }

  // Obtener detalles por docente
  public function get_detalles_by_docente($id_d)
  {
    $sql = "SELECT * FROM detalle WHERE id_d = '$id_d'";
    return $this->consulta($sql);
  }

  // Obtener detalles por grupo
  public function get_detalles_by_grupo($grupo)
  {
    $sql = "SELECT * FROM detalle WHERE grupo = '$grupo'";
    return $this->consulta($sql);
  }

  // Obtener detalles por aula
  public function get_detalles_by_aula($aula)
  {
    $sql = "SELECT * FROM detalle WHERE aula = '$aula'";
    return $this->consulta($sql);
  }

  // Obtener detalles por ciclo y año
  public function get_detalles_by_ciclo_year($ciclo, $year)
  {
    $sql = "SELECT * FROM detalle WHERE ciclo = '$ciclo' AND year = '$year'";
    return $this->consulta($sql);
  }

  // Obtener detalles por día
  public function get_detalles_by_dia($dia)
  {
    $sql = "SELECT * FROM detalle WHERE dia = '$dia'";
    return $this->consulta($sql);
  }

  // Obtener detalles por tipo
  public function get_detalles_by_tipo($tipo)
  {
    $sql = "SELECT * FROM detalle WHERE tipo = '$tipo'";
    return $this->consulta($sql);
  }

  // Cargar todos los detalles en sesión (una vez al día)
  public function cargar_detalles_sesion()
  {
    // Iniciar sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $fecha_actual = date('Y-m-d');

    // Verificar si los datos de hoy ya están en sesión
    if (isset($_SESSION['detalles_fecha']) && $_SESSION['detalles_fecha'] === $fecha_actual) {
      return [
        'success' => true,
        'message' => 'Datos ya cargados en sesión para hoy',
        'fecha' => $fecha_actual,
        'total_registros' => count($_SESSION['detalles'])
      ];
    }

    // Obtener todos los detalles de la base de datos
    $resultado = $this->get_detalles();

    // Convertir el resultado a un array asociativo
    $detalles = [];
    if ($resultado && $resultado->num_rows > 0) {
      while ($fila = $resultado->fetch_assoc()) {
        $detalles[] = $fila;
      }
    }

    // Guardar en sesión
    $_SESSION['detalles'] = $detalles;
    $_SESSION['detalles_fecha'] = $fecha_actual;

    return [
      'success' => true,
      'message' => 'Datos cargados exitosamente en sesión',
      'fecha' => $fecha_actual,
      'total_registros' => count($detalles)
    ];
  }

  // Obtener los datos desde la sesión
  public function get_detalles_from_sesion()
  {
    // Iniciar sesión si no está iniciada
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $fecha_actual = date('Y-m-d');

    // Si no hay datos o son de otro día, recargar
    if (!isset($_SESSION['detalles']) || !isset($_SESSION['detalles_fecha']) || $_SESSION['detalles_fecha'] !== $fecha_actual) {
      $this->cargar_detalles_sesion();
    }

    return isset($_SESSION['detalles']) ? $_SESSION['detalles'] : [];
  }

  // Limpiar los datos de sesión (opcional)
  public function limpiar_detalles_sesion()
  {
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    unset($_SESSION['detalles']);
    unset($_SESSION['detalles_fecha']);

    return [
      'success' => true,
      'message' => 'Datos de sesión limpiados'
    ];
  }
}
