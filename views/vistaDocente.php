<?php
/**
 * Función para generar las tarjetas de docentes desde el JSON
 * Lee config/detalles.json y agrupa los datos por docente
 */


function generarVistasDocentes() {
    $jsonPath = __DIR__ . '/../config/detalles.json';
    
    if (!file_exists($jsonPath)) {
        return '<div class="alert alert-danger">No se encontró el archivo de configuración.</div>';
    }
    
    $jsonContent = file_get_contents($jsonPath);
    $detalles = json_decode($jsonContent, true);
    
    if (empty($detalles)) {
        return '<div class="alert alert-warning">No hay datos de docentes disponibles.</div>';
    }
    
    // Agrupar datos por docente
    $docentes = agruparPorDocente($detalles);
    
    // Generar HTML
    $html = '<div class="container py-5">';
    $html .= '<h3 class="mb-4 text-center">Disponibilidad de Docentes</h3>';
    $html .= '<br>';
    $html .= '<div class="d-flex justify-content-between align-items-center mb-4">';
    $html .= '<a href="logout.php" class="btn btn-danger">Cerrar Sesión</a>';
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

/**
 * Agrupa los detalles por nombre de docente
 */
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

/**
 * Genera una tarjeta individual de docente
 */
function generarTarjetaDocente($nombreCompleto, $detalles, $id) {
    $primerDetalle = $detalles[0];
    $imagen = 'https://picsum.photos/400/200?random=' . $id;
    
    // Obtener el estado del JSON
    $estado = $primerDetalle['estado_disponibilidad'] ?? 'disponible';
    $notas = $primerDetalle['notas_disponibilidad'] ?? '';
    
    // Mapear estado a clase de badge
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
    $html .= '<button class="btn-editar" data-bs-toggle="modal" data-bs-target="#editarModal' . $id . '">Editar Disponibilidad</button>';
    $html .= '<button class="btn-horario" data-bs-toggle="modal" data-bs-target="#horarioModal' . $id . '">Ver Horario</button>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Genera todos los modales (edición y horarios)
 */
function generarModales($docentes) {
    $html = '';
    $id = 1;
    
    foreach ($docentes as $nombreCompleto => $detalles) {
        $html .= generarModalEditar($nombreCompleto, $detalles, $id);
        $html .= generarModalHorario($nombreCompleto, $detalles, $id);
        $id++;
    }
    
    return $html;
}

/**
 * Genera el modal de edición de disponibilidad
 */
function generarModalEditar($nombreCompleto, $detalles, $id) {
    $html = '<!-- Modal Editar ' . $id . ' -->';
    $html .= '<div class="modal fade" id="editarModal' . $id . '" tabindex="-1" aria-hidden="true">';
    $html .= '<div class="modal-dialog modal-dialog-centered">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header editar">';
    $html .= '<h5 class="modal-title">Editar Disponibilidad - ' . htmlspecialchars($nombreCompleto) . '</h5>';
    $html .= '<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>';
    $html .= '</div>';
    $html .= '<div class="modal-body">';
  $html .= '<form action="../controller/actualizaDocente.php" method="POST">';
    $html .= '<input type="hidden" name="docente_id" value="' . $id . '">';
    $html .= '<input type="hidden" name="nombre_docente" value="' . htmlspecialchars($nombreCompleto) . '">';
    $html .= '<div class="mb-3">';
    $html .= '<label for="estado' . $id . '" class="form-label">Estado de Disponibilidad:</label>';
    $html .= '<select class="form-select" id="estado' . $id . '" name="estado" required>';
    $html .= '<option value="disponible" selected>Disponible</option>';
    $html .= '<option value="ocupado">En Clases</option>';
    $html .= '<option value="revisando">Disponible</option>';
    $html .= '<option value="reunion">En reunión</option>';
    $html .= '<option value="laboratorio">Ausente</option>';
    $html .= '</select>';
    $html .= '</div>';
    $html .= '<div class="d-flex justify-content-end gap-2">';
    $html .= '<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>';
    $html .= '<button type="submit" class="btn btn-success">Guardar Cambios</button>';
    $html .= '</div>';
    $html .= '</form>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Genera el horario semanal completo (7:00 a 16:20)
 * Con recesos: 8:40-9:00 y 12:20-13:00
 */
function generarHorarioSemanal($detalles) {
    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
    $horarios = [];
    
    // Bloques de horarios: antes del primer receso, después del primer receso, después del segundo receso
    $bloque1 = ['07:00', '07:50', '08:40']; // Termina a las 8:40
    $bloque2 = ['09:00', '09:50', '10:40', '11:30', '12:20']; // Termina a las 12:20
    $bloque3 = ['13:00', '13:50', '14:40', '15:30', '16:20']; // Hasta las 16:20
    
    $todasLasHoras = array_merge($bloque1, $bloque2, $bloque3);
    
    foreach ($todasLasHoras as $horaInicio) {
        $horaInicioObj = strtotime($horaInicio);
        $horaFinObj = strtotime('+50 minutes', $horaInicioObj);
        $horaFin = date('H:i', $horaFinObj);
        
        $horarios[] = [
            'inicio' => $horaInicio,
            'fin' => $horaFin
        ];
    }
    
    // Crear índice de clases por hora y día
    $clasesIndex = [];
    foreach ($detalles as $detalle) {
        $dia = htmlspecialchars($detalle['dia']);
        $inicio = substr($detalle['ha'], 0, 5); // Formato HH:MM
        
        if (!isset($clasesIndex[$dia])) {
            $clasesIndex[$dia] = [];
        }
        
        $clasesIndex[$dia][$inicio] = [
            'aula' => $detalle['aula'],
            'grupo' => $detalle['grupo'],
            'ciclo' => $detalle['ciclo'],
            'tipo' => $detalle['tipo']
        ];
    }
    
    // Generar tabla
    $html = '<div class="table-responsive" style="max-height: 600px; overflow-y: auto;">';
    $html .= '<table class="table table-bordered table-sm">';
    $html .= '<thead class="table-dark">';
    $html .= '<tr>';
    $html .= '<th style="width: 12%; position: sticky; top: 0; background-color: #212529;">Hora</th>';
    
    foreach ($dias as $dia) {
        $html .= '<th style="width: 18%; position: sticky; top: 0; background-color: #212529;">' . $dia . '</th>';
    }
    
    $html .= '</tr>';
    $html .= '</thead>';
    $html .= '<tbody>';
    
    // Mostrar bloque 1
    foreach ($bloque1 as $horaInicio) {
        $horaFin = date('H:i', strtotime('+50 minutes', strtotime($horaInicio)));
        $html .= '<tr>';
        $html .= '<td class="hora-cell" style="font-weight: bold;">' . $horaInicio . '<br>-<br>' . $horaFin . '</td>';
        
        foreach ($dias as $dia) {
            $clase = $clasesIndex[$dia][$horaInicio] ?? null;
            
            if ($clase) {
                $html .= '<td class="clase-ocupada">';
                $html .= '<strong>' . htmlspecialchars($clase['aula']) . '</strong><br>';
                $html .= '<small>Grupo: ' . htmlspecialchars($clase['grupo']) . '</small><br>';
                $html .= '<small>Ciclo: ' . htmlspecialchars($clase['ciclo']) . '</small>';
                $html .= '</td>';
            } else {
                $html .= '<td class="clase-libre">Libre</td>';
            }
        }
        
        $html .= '</tr>';
    }
    
    // Fila de receso 1
    $html .= '<tr style="background-color: #f8d7da;">';
    $html .= '<td class="hora-cell" style="font-weight: bold; text-align: center;">8:40 - 9:00<br><em>RECESO</em></td>';
    foreach ($dias as $dia) {
        $html .= '<td style="text-align: center; font-weight: bold;">RECESO</td>';
    }
    $html .= '</tr>';
    
    // Mostrar bloque 2
    foreach ($bloque2 as $horaInicio) {
        $horaFin = date('H:i', strtotime('+50 minutes', strtotime($horaInicio)));
        $html .= '<tr>';
        $html .= '<td class="hora-cell" style="font-weight: bold;">' . $horaInicio . '<br>-<br>' . $horaFin . '</td>';
        
        foreach ($dias as $dia) {
            $clase = $clasesIndex[$dia][$horaInicio] ?? null;
            
            if ($clase) {
                $html .= '<td class="clase-ocupada">';
                $html .= '<strong>' . htmlspecialchars($clase['aula']) . '</strong><br>';
                $html .= '<small>Grupo: ' . htmlspecialchars($clase['grupo']) . '</small><br>';
                $html .= '<small>Ciclo: ' . htmlspecialchars($clase['ciclo']) . '</small>';
                $html .= '</td>';
            } else {
                $html .= '<td class="clase-libre">Libre</td>';
            }
        }
        
        $html .= '</tr>';
    }
    
    // Fila de receso 2
    $html .= '<tr style="background-color: #f8d7da;">';
    $html .= '<td class="hora-cell" style="font-weight: bold; text-align: center;">12:20 - 1:00<br><em>RECESO</em></td>';
    foreach ($dias as $dia) {
        $html .= '<td style="text-align: center; font-weight: bold;">RECESO</td>';
    }
    $html .= '</tr>';
    
    // Mostrar bloque 3
    foreach ($bloque3 as $horaInicio) {
        $horaFin = date('H:i', strtotime('+50 minutes', strtotime($horaInicio)));
        $html .= '<tr>';
        $html .= '<td class="hora-cell" style="font-weight: bold;">' . $horaInicio . '<br>-<br>' . $horaFin . '</td>';
        
        foreach ($dias as $dia) {
            $clase = $clasesIndex[$dia][$horaInicio] ?? null;
            
            if ($clase) {
                $html .= '<td class="clase-ocupada">';
                $html .= '<strong>' . htmlspecialchars($clase['aula']) . '</strong><br>';
                $html .= '<small>Grupo: ' . htmlspecialchars($clase['grupo']) . '</small><br>';
                $html .= '<small>Ciclo: ' . htmlspecialchars($clase['ciclo']) . '</small>';
                $html .= '</td>';
            } else {
                $html .= '<td class="clase-libre">Libre</td>';
            }
        }
        
        $html .= '</tr>';
    }
    
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Genera el modal con la tabla de horarios completa
 */
function generarModalHorario($nombreCompleto, $detalles, $id) {
    $html = '<!-- Modal Horario ' . $id . ' -->';
    $html .= '<div class="modal fade" id="horarioModal' . $id . '" tabindex="-1" aria-hidden="true">';
    $html .= '<div class="modal-dialog modal-xl modal-dialog-centered">';
    $html .= '<div class="modal-content">';
    $html .= '<div class="modal-header" style="background-color: #f8f9fa;">';
    $html .= '<h5 class="modal-title">Horario Semanal: ' . htmlspecialchars($nombreCompleto) . '</h5>';
    $html .= '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>';
    $html .= '</div>';
    $html .= '<div class="modal-body">';
    $html .= '<div class="alert alert-info" role="alert">';
    $html .= '<small>';
    $html .= '<strong>Horario:</strong> 7:00 AM - 4:20 PM | ';
    $html .= '<strong>Duración de clase:</strong> 50 minutos | ';
    $html .= '<strong>Recesos:</strong> 8:40-9:00 AM y 12:20-1:00 PM';
    $html .= '</small>';
    $html .= '</div>';
    $html .= generarHorarioSemanal($detalles);
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';
    
    return $html;
}

// Mostrar el contenido en la vista
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
    body {
      background-color: #f8f9fa;
      font-family: "Segoe UI", sans-serif;
    }

    .docente-card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      overflow: hidden;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .docente-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.15);
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
      font-size: 1.2rem;
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

    .status-green { background-color: #d4edda; color: #155724; }
    .status-yellow { background-color: #fff3cd; color: #856404; }
    .status-red { background-color: #f8d7da; color: #721c24; }

    .btn-editar {
      background-color: #EF5350;
      color: #ffd5af;
      border-radius: 10px;
      padding: 0.4rem 1rem;
      font-weight: 500;
      transition: 0.2s;
      border: none;
    }

    .btn-editar:hover {
      background-color: #218838;
    }

    .btn-horario {
      background-color: #EF5350;
      color: #fff;
      border-radius: 10px;
      padding: 0.4rem 1rem;
      font-weight: 500;
      transition: 0.2s;
      border: none;
    }

    .btn-horario:hover {
      background-color: #0b5ed7;
    }

    .botones-group {
      display: flex;
      gap: 0.5rem;
      justify-content: center;
      flex-wrap: wrap;
    }

    .modal-content {
      border-radius: 15px;
    }

    .modal-header {
      background-color: #EF5350;
      color: white;
      border-radius: 15px 15px 0 0;
    }

    .modal-header.editar {
      background-color: #ffd5af;
    }

    .table {
      margin-bottom: 0;
      font-size: 0.85rem;
    }

    .table thead {
      background-color: #EF5350;
      color: white;
    }

    .table thead th {
      border: none;
      font-weight: 500;
      padding: 0.75rem 0.5rem;
      text-align: center;
    }

    .table tbody td {
      vertical-align: middle;
      padding: 0.6rem 0.4rem;
      font-size: 0.8rem;
    }

    .hora-cell {
      font-weight: 600;
      color: #495057;
      background-color: #f8f9fa;
      white-space: nowrap;
      font-size: 0.75rem;
    }

    .clase-ocupada {
      background-color: #fff3cd;
    }

    .clase-libre {
      background-color: #d4edda;
      color: #155724;
      text-align: center;
      font-style: italic;
    }
  </style>
</head>
<body>



<script>
document.addEventListener("DOMContentLoaded", function() {
  // Funcionalidad de búsqueda
  const inputBusqueda = document.getElementById('buscarDocente');
  const listaDocentes = document.getElementById('listaDocentes');

  if (inputBusqueda && listaDocentes) {
    inputBusqueda.addEventListener('input', function(e) {
      const busqueda = e.target.value.toLowerCase().trim();
      // Seleccionar las columnas que contienen las tarjetas (tolerante a diferentes clases)
      const tarjetas = listaDocentes.querySelectorAll('.col-md-4, .col-sm-6, .docente-card');

      Array.from(tarjetas).forEach(tarjetaWrapper => {
        // Si la tarjeta fue pasada directamente como .docente-card, buscar el wrapper col
        let tarjeta = tarjetaWrapper.classList.contains('docente-card') ? tarjetaWrapper : tarjetaWrapper.querySelector('.docente-card') || tarjetaWrapper;

        const nombreEl = tarjeta.querySelector('.docente-nombre');
        const areaEl = tarjeta.querySelector('.docente-area');
        const nombreDocente = nombreEl ? nombreEl.textContent.toLowerCase() : '';
        const aula = areaEl ? areaEl.textContent.toLowerCase() : '';

        if (nombreDocente.includes(busqueda) || aula.includes(busqueda)) {
          // mostrar el elemento columna si existe, si no mostrar la tarjeta
          if (tarjetaWrapper.style) tarjetaWrapper.style.display = '';
        } else {
          if (tarjetaWrapper.style) tarjetaWrapper.style.display = 'none';
        }
      });
    });
  }

  // Lógica existente de días de la semana
  const diasSemana = ["domingo", "lunes", "martes", "miércoles", "jueves", "viernes", "sábado"];
  const hoy = new Date();
  const diaActual = diasSemana[hoy.getDay()];
  const horaActual = hoy.getHours();

  // Función para obtener el rango horario actual
  function obtenerRangoHora(hora) {
    if (hora >= 7 && hora < 8) return "7:00-8:00";
    if (hora >= 8 && hora < 9) return "8:00-9:00";
    if (hora >= 9 && hora < 10) return "9:00-10:00";
    if (hora >= 10 && hora < 11) return "10:00-11:00";
    if (hora >= 11 && hora < 12) return "11:00-12:00";
    return null;
  }

  const rango = obtenerRangoHora(horaActual);
  if (!rango) return; // Fuera del horario lectivo

  // Recorre cada docente
  document.querySelectorAll(".docente-card").forEach(card => {
    const modalId = card.querySelector(".btn-horario").getAttribute("data-bs-target");
    const tabla = document.querySelector(`${modalId} table`);
    const filas = tabla.querySelectorAll("tbody tr");

    let estaOcupado = false;

    filas.forEach(fila => {
      const hora = fila.querySelector(".hora-cell").textContent.trim();
      if (hora === rango) {
        const indiceDia = diasSemana.indexOf(diaActual);
        if (indiceDia >= 1 && indiceDia <= 5) {
          const celda = fila.children[indiceDia]; // Lunes=1 ... Viernes=5
          if (celda.classList.contains("clase-ocupada")) {
            estaOcupado = true;
          }
        }
      }
    });

    const estado = card.querySelector(".status-badge");
    if (estaOcupado) {
      estado.textContent = "En clase";
      estado.className = "status-badge status-red";
    } else {
      estado.textContent = "Disponible";
      estado.className = "status-badge status-green";
    }
  });
});
</script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <div id="flash-container" style="position:fixed;top:16px;right:16px;z-index:2000"></div>

  <script>
  (function(){
    function showFlash(type, msg, timeout = 3000){
      const container = document.getElementById('flash-container');
      if(!container) return;
      const wrapper = document.createElement('div');
      wrapper.innerHTML = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>`;
      container.appendChild(wrapper);
      setTimeout(()=>{ try{ const bs = bootstrap.Alert.getOrCreateInstance(wrapper.querySelector('.alert')); bs.close(); }catch(e){} }, timeout);
    }

   
    function estadoToClass(estado){
      estado = (estado||'').toLowerCase();
      if(estado === 'disponible') return 'status-green';
      if(estado === 'ocupado' || estado === 'atendiendo estudiante' || estado === 'atendiendo') return 'status-red';
      return 'status-yellow';
    }

    
    function updateBadgeForDocente(nombre, estado){
      const cards = document.querySelectorAll('.docente-card');
      for(const card of cards){
        const n = card.querySelector('.docente-nombre');
        if(!n) continue;
        if(n.textContent.trim() === nombre.trim()){
          const badge = card.querySelector('.status-badge');
          if(badge){
            badge.textContent = estado.charAt(0).toUpperCase() + estado.slice(1);
            badge.classList.remove('status-green','status-yellow','status-red');
            badge.classList.add(estadoToClass(estado));
          }
          return true;
        }
      }
      return false;
    }

    document.addEventListener('submit', function(e){
      const form = e.target;
      if(!form || !form.action) return;
      if(!form.action.includes('actualizaDocente.php')) return;

      e.preventDefault();

      const fd = new FormData(form);

     
      if(!fd.get('nombre_docente')){
        const modal = form.closest('.modal');
        if(modal){
          const title = modal.querySelector('.modal-title');
          if(title){
            const text = title.textContent.replace(/^Editar Disponibilidad - /i,'').trim();
            if(text) fd.set('nombre_docente', text);
          }
        }
      }

    
      fetch(form.action, {
        method: 'POST',
        body: fd,
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      }).then(r=>r.json()).then(data=>{
        const ok = (data.status === 'exito' || data.status === 'success');
        // Mostrar mensaje simplificado en caso de éxito
        if (ok) {
          showFlash('success', 'actualizado correctamente');
        } else {
          const displayName = data.nombre ? (data.nombre + ': ') : '';
          const displayMsg = data.mensaje || data.message || 'Error';
          showFlash('danger', displayName + displayMsg);
        }

       
        try{
          const modalEl = form.closest('.modal');
          if(modalEl){
            const bsModal = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
            bsModal.hide();
          }
        }catch(e){ }

        
        const nombre = fd.get('nombre_docente') || '';
        const estado = fd.get('estado') || '';
        if(nombre && estado){
          updateBadgeForDocente(nombre, estado);
        }

      }).catch(err=>{
        showFlash('danger','Error de red al actualizar');
      });
    });
  })();
  </script>
</body>
</html>