<?php
/**
 * Vista pública: disponibilidad y horarios de docentes 
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
    
    $html = '<div class="container py-5">';
    $html .= '<h3 class="mb-4 text-center">Disponibilidad de Docentes</h3>';
    $html .= '<br>';
    $html .= '<div class="d-flex justify-content-between align-items-center mb-4">';
    $html .= '<div class="col-md-4">';
    $html .= '<div class="input-group">';
    $html .= '<span class="input-group-text"><i class="bi bi-search"></i></span>';
    $html .= '<input type="text" class="form-control" id="buscarDocente" placeholder="Buscar docente...">';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '<div class="row justify-content-center g-4" id="listaDocentes">';
    
    $contador = 1;
    foreach ($docentes as $nombreCompleto => $datos) {
        $html .= generarTarjetaDocente($nombreCompleto, $datos, $contador);
        $contador++;
    }
    
    $html .= '</div></div>';
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
    $imagen = 'https://picsum.photos/400/200?random=' . $id;
    $estado = $primerDetalle['estado_disponibilidad'] ?? 'disponible';
    $notas = $primerDetalle['notas_disponibilidad'] ?? '';

    $estadoClases = [
        'disponible' => 'status-green',
        'ocupado' => 'status-red',
        'revisando' => 'status-yellow',
        'reunion' => 'status-yellow',
        'laboratorio' => 'status-yellow',
        'almuerzo' => 'status-orange'
    ];
    
    $estadoEtiquetas = [
        'disponible' => 'Disponible',
        'ocupado' => 'Atendiendo estudiante',
        'revisando' => 'Revisando tareas',
        'reunion' => 'En reunión',
        'laboratorio' => 'En laboratorio',
        'almuerzo' => 'En almuerzo'
    ];
    
    $estadoClase = $estadoClases[$estado] ?? 'status-green';
    $estadoEtiqueta = $estadoEtiquetas[$estado] ?? 'Disponible';
    
    $html = '<div class="col-md-4 col-sm-6">';
    $html .= '<div class="docente-card">';
    $html .= '<img src="' . htmlspecialchars($imagen) . '" alt="Docente" class="docente-img">';
    $html .= '<div class="docente-body">';
    $html .= '<p class="docente-nombre">' . htmlspecialchars($nombreCompleto) . '</p>';
    $html .= '<p class="docente-area">Área: ' . htmlspecialchars($primerDetalle['aula']) . '</p>';
    $html .= '<span class="status-badge ' . $estadoClase . '">' . $estadoEtiqueta . '</span>';

    if (!empty($notas)) {
        $html .= '<p class="docente-notas" style="font-size: 0.85rem; color: #666; margin-top: 8px;"><em>' . htmlspecialchars($notas) . '</em></p>';
    }

    $html .= '<div class="mt-3 botones-group">';
    $html .= '<button class="btn-horario" data-bs-toggle="modal" data-bs-target="#horarioModal' . $id . '">Ver Horario</button>';
    $html .= '</div>';
    $html .= '</div></div></div>';
    
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
    $todasLasHoras = array_merge($bloque1, $bloque2, $bloque3);

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

    $bloques = [$bloque1, 'receso1', $bloque2, 'receso2', $bloque3];
    foreach ($bloques as $bloque) {
        if ($bloque === 'receso1') {
            $html .= '<tr class="table-danger"><td>8:40 - 9:00</td><td colspan="5" class="text-center fw-bold">RECESO</td></tr>';
            continue;
        } elseif ($bloque === 'receso2') {
            $html .= '<tr class="table-danger"><td>12:20 - 13:00</td><td colspan="5" class="text-center fw-bold">RECESO</td></tr>';
            continue;
        }

        foreach ($bloque as $horaInicio) {
            $horaFin = date('H:i', strtotime('+50 minutes', strtotime($horaInicio)));
            $html .= "<tr><td class='hora-cell'>$horaInicio<br>-<br>$horaFin</td>";
            foreach ($dias as $dia) {
                $clase = $clasesIndex[$dia][$horaInicio] ?? null;
                if ($clase) {
                    $html .= "<td class='clase-ocupada'><strong>{$clase['aula']}</strong><br><small>Grupo: {$clase['grupo']}</small><br><small>Ciclo: {$clase['ciclo']}</small></td>";
                } else {
                    $html .= "<td class='clase-libre'>Libre</td>";
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
    $html .= '<div class="modal-header"><h5 class="modal-title">Horario Semanal: ' . htmlspecialchars($nombreCompleto) . '</h5>';
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
    body{background:#f8f9fa;font-family:"Segoe UI",sans-serif;}
    .docente-card{background:#fff;border-radius:15px;box-shadow:0 4px 12px rgba(0,0,0,0.08);overflow:hidden;transition:transform .2s,box-shadow .2s;}
    .docente-card:hover{transform:translateY(-5px);box-shadow:0 6px 15px rgba(0,0,0,0.15);}
    .docente-img{width:100%;height:180px;object-fit:cover;}
    .docente-body{text-align:center;padding:1rem;}
    .status-badge{padding:.35rem .75rem;border-radius:20px;font-size:.8rem;font-weight:600;}
    .status-green{background:#d4edda;color:#155724;}
    .status-yellow{background:#fff3cd;color:#856404;}
    .status-red{background:#f8d7da;color:#721c24;}
    .btn-horario{background:#EF5350;color:#fff;border:none;border-radius:10px;padding:.4rem 1rem;font-weight:500;}
    .btn-horario:hover{background:#0b5ed7;}
    .clase-ocupada{background:#fff3cd;}
    .clase-libre{background:#d4edda;text-align:center;font-style:italic;}
  </style>
</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Búsqueda en tiempo real
document.addEventListener("DOMContentLoaded",()=>{
  const input=document.getElementById('buscarDocente');
  const lista=document.getElementById('listaDocentes');
  input.addEventListener('input',e=>{
    const txt=e.target.value.toLowerCase();
    lista.querySelectorAll('.col-md-4').forEach(col=>{
      const n=col.querySelector('.docente-nombre').textContent.toLowerCase();
      const a=col.querySelector('.docente-area').textContent.toLowerCase();
      col.style.display=(n.includes(txt)||a.includes(txt))?'':'none';
    });
  });
});
</script>
</body>
</html>
