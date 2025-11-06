<?php
/**
 * Vista pública: disponibilidad y horarios de docentes (solo lectura)
 * Estilo adaptado al diseño del usuario (colores + logo)
 */

    

function generarVistasDocentes() {
    $jsonPath = __DIR__ . '/config/detalles.json';
    
    if (!file_exists($jsonPath)) {
        return '<div class="alert alert-danger">No se encontró el archivo de configuración.</div>';
        




    }
    
    $jsonContent = file_get_contents($jsonPath);
    $detalles = json_decode($jsonContent, true);
    
    if (empty($detalles)) {
        return '<div class="alert alert-warning">No hay datos de docentes disponibles.</div>';
    }
    
    $docentes = agruparPorDocente($detalles);
    
    $html = '<div class="header">
                <h1>Disponibilidad de Docentes</h1>
                <img src="img/logo.png" alt="Logo de la institución">
             </div>';
    
    // Campo de búsqueda
    $html .= '<div class="input-group mb-4" style="max-width:400px;">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="buscarDocente" placeholder="Buscar docente...">
              </div>';
    
    $html .= '<div class="grid" id="listaDocentes">';
    
    $contador = 1;
    foreach ($docentes as $nombreCompleto => $datos) {
        $html .= generarTarjetaDocente($nombreCompleto, $datos, $contador);
        $contador++;
    }
    
    $html .= '</div>';
    $html .= generarModales($docentes);
    
    return $html;
}

function agruparPorDocente($detalles) {
    $docentes = [];
    foreach ($detalles as $detalle) {
        $nombreCompleto = $detalle['nombre_docente'] . ' ' . $detalle['apellido_docente'];
        if (!isset($docentes[$nombreCompleto])) {
            $docentes[$nombreCompleto] = [];
        }
        $docentes[$nombreCompleto][] = $detalle;
    }
    return $docentes;
}

function generarTarjetaDocente($nombreCompleto, $detalles, $id) {
    $primerDetalle = $detalles[0];
    $estado = $primerDetalle['estado_disponibilidad'] ?? 'disponible';

    // Colores según estado
    $claseColor = match($estado) {
        'disponible' => 'blanco',
        'ocupado' => 'rojo',
        'revisando', 'reunion', 'laboratorio', 'almuerzo' => 'amarillo',
        default => 'blanco'
    };

    $html = '<div class="card ' . $claseColor . '">';
    $html .= htmlspecialchars($nombreCompleto);
    $html .= '<button class="btn-detalles" data-bs-toggle="modal" data-bs-target="#horarioModal' . $id . '">Ver detalles</button>';
    $html .= '</div>';

    return $html;
}

function generarModales($docentes) {
    $html = '';
    $id = 1;
    foreach ($docentes as $nombreCompleto => $detalles) {
        $html .= generarModalHorario($nombreCompleto, $detalles, $id);
        $id++;
    }
    return $html;
}

function generarHorarioSemanal($detalles) {
    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
    $bloque1 = ['07:00', '07:50', '08:40'];
    $bloque2 = ['09:00', '09:50', '10:40', '11:30', '12:20'];
    $bloque3 = ['13:00', '13:50', '14:40', '15:30', '16:20'];
    $bloques = [$bloque1, 'receso1', $bloque2, 'receso2', $bloque3];

    $clasesIndex = [];
    foreach ($detalles as $detalle) {
        $dia = htmlspecialchars($detalle['dia']);
        $inicio = substr($detalle['ha'], 0, 5);
        $clasesIndex[$dia][$inicio] = [
            'aula' => $detalle['aula'],
            'grupo' => $detalle['grupo'],
            'ciclo' => $detalle['ciclo']
        ];
    }

    $html = '<div class="table-responsive" style="max-height:600px;overflow-y:auto;"><table class="table table-bordered table-sm">';
    $html .= '<thead class="table-dark"><tr><th>Hora</th>';
    foreach ($dias as $d) $html .= "<th>$d</th>";
    $html .= '</tr></thead><tbody>';

    foreach ($bloques as $bloque) {
        if ($bloque === 'receso1') {
            $html .= '<tr class="table-warning"><td>8:40 - 9:00</td><td colspan="5" class="text-center fw-bold">RECESO</td></tr>';
            continue;
        } elseif ($bloque === 'receso2') {
            $html .= '<tr class="table-warning"><td>12:20 - 13:00</td><td colspan="5" class="text-center fw-bold">RECESO</td></tr>';
            continue;
        }

        foreach ($bloque as $horaInicio) {
            $horaFin = date('H:i', strtotime('+50 minutes', strtotime($horaInicio)));
            $html .= "<tr><td>$horaInicio<br>-<br>$horaFin</td>";
            foreach ($dias as $dia) {
                $clase = $clasesIndex[$dia][$horaInicio] ?? null;
                if ($clase) {
                    $html .= "<td class='table-warning text-center'><strong>{$clase['aula']}</strong><br><small>Grupo: {$clase['grupo']}</small><br><small>Ciclo: {$clase['ciclo']}</small></td>";
                } else {
                    $html .= "<td class='table-success text-center'><em>Libre</em></td>";
                }
            }
            $html .= '</tr>';
        }
    }

    $html .= '</tbody></table></div>';
    return $html;
}

function generarModalHorario($nombreCompleto, $detalles, $id) {
    $html = '<div class="modal fade" id="horarioModal' . $id . '" tabindex="-1" aria-hidden="true">';
    $html .= '<div class="modal-dialog modal-xl modal-dialog-centered"><div class="modal-content">';
    $html .= '<div class="modal-header" style="background:#FFD54F;"><h5 class="modal-title fw-bold">' . htmlspecialchars($nombreCompleto) . ' - Horario Semanal</h5>';
    $html .= '<button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>';
    $html .= '<div class="modal-body"><div class="alert alert-info"><small><strong>Horario:</strong> 7:00 AM - 4:20 PM | <strong>Recesos:</strong> 8:40-9:00 y 12:20-13:00</small></div>';
    $html .= generarHorarioSemanal($detalles);
    $html .= '</div></div></div></div>';
    return $html;
}

echo generarVistasDocentes();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Disponibilidad de Docentes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    :root {
        --amarillo: #FFD54F;
        --rojo: #EF5350;
        --blanco: #FFFFFF;
        --gris-text: #333;
        --card-shadow: 0 4px 10px rgba(0,0,0,0.08);
        font-family: Arial, Helvetica, sans-serif;
    }
    body {background:#f5f5f5; margin:0; padding:20px; color:var(--gris-text);}
    .header {display:flex; align-items:center; gap:10px; margin-bottom:20px;}
    .header h1 {font-size:24px; margin:0;}
    .header img {height:40px;}

    .grid {display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:12px;}
    .card {padding:16px; border-radius:8px; box-shadow:var(--card-shadow); display:flex; flex-direction:column; align-items:center; justify-content:center; height:140px; font-weight:700; font-size:18px; color:#000; text-align:center; position:relative;}

    .card.blanco {background:var(--blanco); border:1px solid #ddd;}
    .card.amarillo {background:var(--amarillo);}
    .card.rojo {background:var(--rojo); color:#fff;}

    .btn-detalles {position:absolute; bottom:10px; padding:6px 12px; border:none; border-radius:6px; cursor:pointer; background:#1976D2; color:#fff; font-weight:600; font-size:14px;}
  </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded",()=>{
  const input=document.getElementById('buscarDocente');
  const lista=document.getElementById('listaDocentes');
  input.addEventListener('input',e=>{
    const txt=e.target.value.toLowerCase();
    lista.querySelectorAll('.card').forEach(card=>{
      const n=card.textContent.toLowerCase();
      card.style.display=n.includes(txt)?'':'none';
    });
  });
});
</script>
</body>
</html>
