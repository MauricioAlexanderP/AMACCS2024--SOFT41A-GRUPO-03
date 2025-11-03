<?php
require_once __DIR__ . '/../config/cn.php';

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

    // Obtener detalles con información de docente y materia JOIN para luego hacer el JSON
  public function obtenerDetalles() {
    $sql = "
    SELECT 
      d.id_detalle,
      doc.nom_usuario AS nombre_docente,
      doc.ape_usuario AS apellido_docente,
      d.aula,
      d.ha,
      d.hf,
      d.ciclo,
      d.year,
      d.dia,
      d.grupo,
      d.tipo,
      d.horas,
      d.version,
      d.fechaini,
      d.fechafin,
      d.comentarioreserva,
      d.carnetusuario,
      d.cod_alldetalle
    FROM detalle d
    LEFT JOIN docente doc ON d.id_d = doc.id_docente
    ";

    $result = $this->consulta($sql);
    $detalles = [];

    while ($row = $result->fetch_assoc()) {
      $detalles[] = $row;
    }

    return $detalles;
  }

    /**
     * Genera el JSON en config/detalles.json.
     * Preserva los estados/notes previos si existen (buscando por cod_alldetalle cuando esté disponible).
     */
    public function generarJSON() {
      $detalles = $this->obtenerDetalles();
      $ruta = __DIR__ . '/../config/detalles.json';

      // Cargar estados previos si el archivo existe para preservarlos
      $previos = [];
      if (file_exists($ruta)) {
        $tmp = json_decode(file_get_contents($ruta), true);
        if (is_array($tmp)) {
          foreach ($tmp as $p) {
            // usar cod_alldetalle cuando exista, si no, usar id_detalle como fallback
            $key = isset($p['cod_alldetalle']) && $p['cod_alldetalle'] !== '' ? $p['cod_alldetalle'] : (isset($p['id_detalle']) ? $p['id_detalle'] : null);
            if ($key !== null) {
              $previos[$key] = $p;
            }
          }
        }
      }

      $out = [];
      foreach ($detalles as $d) {
        // identificar la llave para buscar estado previo
        $key = isset($d['cod_alldetalle']) && $d['cod_alldetalle'] !== '' ? $d['cod_alldetalle'] : (isset($d['id_detalle']) ? $d['id_detalle'] : null);

        $estado_prev = null;
        $notas_prev = '';
        $fecha_prev = '';
        if ($key !== null && isset($previos[$key])) {
          $p = $previos[$key];
          $estado_prev = isset($p['estado_disponibilidad']) ? $p['estado_disponibilidad'] : (isset($p['estado']) ? $p['estado'] : null);
          $notas_prev = $p['notas_disponibilidad'] ?? ($p['notas'] ?? '');
          $fecha_prev = $p['fecha_actualizacion'] ?? '';
        }

        // construir registro asegurando que siempre exista la clave estado_disponibilidad y un alias estado
        $registro = $d;
        $registro['estado_disponibilidad'] = $estado_prev !== null ? $estado_prev : 'disponible';
        $registro['notas_disponibilidad'] = $notas_prev;
        $registro['fecha_actualizacion'] = $fecha_prev;
        // alias corto solicitado: 'estado'
        $registro['estado'] = $registro['estado_disponibilidad'];

        $out[] = $registro;
      }

      $resultado = file_put_contents($ruta, json_encode($out, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
      return $resultado !== false;
    }

    // 🔹 Leer datos desde config/detalles.json
public function editarDisponibilidad($nombre_docente, $estado, $notas = '') {
        $ruta = __DIR__ . '/../config/detalles.json';
        
        // Verificar que el archivo existe
        if (!file_exists($ruta)) {
            return [
                'status' => 'error',
                'mensaje' => 'No se encontró el archivo de configuración.'
            ];
        }
        
        try {
            // Obtener datos del JSON
            $detalles = json_decode(file_get_contents($ruta), true);
            
            if (!is_array($detalles)) {
                return [
                    'status' => 'error',
                    'mensaje' => 'Error al decodificar el JSON.'
                ];
            }
            
            // Separar nombre y apellido
            $nombreParts = explode(' ', $nombre_docente, 2);
            $nombre = trim($nombreParts[0]);
            $apellido = isset($nombreParts[1]) ? trim($nombreParts[1]) : '';
            
            // Contador de registros actualizados
            $registrosActualizados = 0;
            
            // Actualizar todos los registros del docente
            foreach ($detalles as &$detalle) {
                if ($detalle['nombre_docente'] === $nombre && $detalle['apellido_docente'] === $apellido) {
                    $detalle['estado_disponibilidad'] = $estado;
                    $detalle['notas_disponibilidad'] = $notas;
                    $detalle['fecha_actualizacion'] = date('Y-m-d H:i:s');
                    $registrosActualizados++;
                }
            }
            
            // Si no encontró registros
            if ($registrosActualizados === 0) {
                return [
                    'status' => 'advertencia',
                    'mensaje' => 'No se encontraron registros del docente especificado.'
                ];
            }
            
            // Guardar cambios en el JSON
            $resultado = file_put_contents(
                $ruta,
                json_encode($detalles, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
            
            if ($resultado === false) {
                return [
                    'status' => 'error',
                    'mensaje' => 'No se pudieron guardar los cambios en el archivo.'
                ];
            }
            
      return [
        'status' => 'exito',
        // Mensaje corto y consistente para la UI
        'mensaje' => 'actualizado correctamente',
        'registros_actualizados' => $registrosActualizados
      ];
            
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'mensaje' => 'Error: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtiene el estado actual de disponibilidad de un docente
     * @param string $nombre_docente - Nombre completo del docente
     * @return array - Array con estado y notas
     */
    public function obtenerDisponibilidad($nombre_docente) {
        $ruta = __DIR__ . '/../config/detalles.json';
        
        if (!file_exists($ruta)) {
            return null;
        }
        
        $detalles = json_decode(file_get_contents($ruta), true);
        $nombreParts = explode(' ', $nombre_docente, 2);
        $nombre = trim($nombreParts[0]);
        $apellido = isset($nombreParts[1]) ? trim($nombreParts[1]) : '';
        
        foreach ($detalles as $detalle) {
            if ($detalle['nombre_docente'] === $nombre && $detalle['apellido_docente'] === $apellido) {
                return [
                    'estado' => $detalle['estado_disponibilidad'] ?? 'disponible',
                    'notas' => $detalle['notas_disponibilidad'] ?? ''
                ];
            }
        }
        
        return null;
    }
    
    /**
     * Obtiene el estado de disponibilidad de todos los docentes agrupados
     * @return array - Array de docentes con su estado
     */
    public function obtenerTodosLosEstados() {
        $ruta = __DIR__ . '/../config/detalles.json';
        
        if (!file_exists($ruta)) {
            return [];
        }
        
        $detalles = json_decode(file_get_contents($ruta), true);
        $estados = [];
        
        foreach ($detalles as $detalle) {
            $nombreCompleto = $detalle['nombre_docente'] . ' ' . $detalle['apellido_docente'];
            
            if (!isset($estados[$nombreCompleto])) {
                $estados[$nombreCompleto] = [
                    'estado' => $detalle['estado_disponibilidad'] ?? 'disponible',
                    'notas' => $detalle['notas_disponibilidad'] ?? '',
                    'fecha_actualizacion' => $detalle['fecha_actualizacion'] ?? ''
                ];
            }
        }
        
        return $estados;
    }
}

/**
 * Script para procesar la actualización (guardar como actualizar_disponibilidad.php)
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_docente = $_POST['nombre_docente'] ?? '';
    $estado = $_POST['estado'] ?? 'disponible';
    $notas = $_POST['notas'] ?? '';
    
  $disponibilidad = new Detalle();
    $resultado = $disponibilidad->editarDisponibilidad($nombre_docente, $estado, $notas);
    
    // Retornar como JSON para AJAX
    header('Content-Type: application/json');
    echo json_encode($resultado);
    exit;
}


?>
