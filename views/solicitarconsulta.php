<?php 
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once dirname(__DIR__) . '/model/detalle.php';
// Intenta cargar el modelo
// //$path = dirname(__DIR__) . '/model/detalle.php';
// echo "<p>Ruta detectada: <b>$path</b></p>";

// if (file_exists($path)) {
//     require_once $path;
//     echo "<p style='color:green;'>✔ Archivo cargado correctamente</p>";
// } else {
//     echo "<p style='color:red;'>❌ No se encontró el archivo</p>";
// }//


$detalle = new Detalle();

// Llamamos al método que genera el JSON
if ($detalle->generarJSON()) {
    echo "✔ Archivo JSON generado correctamente.";
} else {
    echo "❌ Error al generar el archivo JSON.";
}

$detalle = new Detalle();
$estados = $detalle->obtenerTodosLosEstados();

// Luego en tu código, accede al estado así: 

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consultas</title>
<style>
    :root {
        --verde: #4CAF50;
        --amarillo: #FFD54F;
        --rojo: #EF5350;
        --blanco: #FFFFFF;
        --gris-text: #333;
        --card-shadow: 0 4px 10px rgba(0,0,0,0.08);
        font-family: Arial, Helvetica, sans-serif;
    }

    body {
        background:#f5f5f5;
        margin:0;
        padding:20px;
        color:var(--gris-text);
    }

    .header {
        display:flex;
        align-items:center;
        gap:10px;
        margin-bottom:20px;
    }

    .header h1 {
        font-size:24px;
        margin:0;
    }

    .header img {
        height:40px;
    }

    .form-container {
        background:#fff;
        padding:20px;
        border-radius:12px;
        box-shadow:0 8px 20px rgba(0,0,0,0.1);
        max-width:600px;
        margin:0 auto 30px;
    }

    .form-container label {
        display:block;
        margin-bottom:6px;
        font-weight:600;
    }

    .form-container input, .form-container textarea, .form-container select, .form-container button {
        width:100%;
        padding:10px;
        margin-bottom:15px;
        border-radius:6px;
        border:1px solid #ccc;
        font-size:14px;
    }

    .form-container textarea {
        resize: vertical;
        min-height:80px;
    }

    .form-container button {
        background:#1976D2;
        color:#fff;
        border:none;
        font-weight:600;
        cursor:pointer;
    }

    .grid {
        display:grid;
        grid-template-columns:repeat(auto-fill,minmax(150px,1fr));
        gap:12px;
        max-width:800px;
        margin:0 auto;
    }

    .card {
        padding:16px;
        border-radius:8px;
        box-shadow:var(--card-shadow);
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
        height:120px;
        font-weight:700;
        font-size:16px;
        color:#000;
        text-align:center;
        position:relative;
    }

    .card.blanco {background:var(--blanco); border:1px solid #ddd;}
    .card.verde {background:var(--verde); color:#fff;}
    .card.amarillo {background:var(--amarillo);}
    .card.rojo {background:var(--rojo); color:#fff;}

    .card span.status {
        position:absolute;
        top:10px;
        right:10px;
        font-size:12px;
        font-weight:600;
        padding:2px 6px;
        border-radius:4px;
        color:#fff;
    }

    .card.verde span.status {background:var(--verde);}
    .card.amarillo span.status {background:var(--amarillo); color:#000;}
    .card.rojo span.status {background:var(--rojo);}
</style>
</head>
<body>

<div class="header">
    <h1>Kiosko - Solicitud de Consulta</h1>
    <img src="logo.png" alt="Logo">
</div>

<div class="form-container">
    <form>
        <label for="carnet">Ingrese su Carnet:</label>
        <input type="text" id="carnet" name="carnet" placeholder="Ingrese su carné" required>

        <label for="materia">Materia a consultar:</label>
        <input type="text" id="materia" name="materia" placeholder="Ej: Matemáticas" required>

        <label for="descripcion">Descripción de la consulta:</label>
        <textarea id="descripcion" name="descripcion" placeholder="Escriba aquí su consulta..." required></textarea>

        <button type="submit">Enviar Solicitud</button>
    </form>
</div>

<h2 style="text-align:center; margin-bottom:12px;">Disponibilidad de docentes</h2>

<div id="grid-docentes" class="grid"></div>

<script>
async function cargarDocentes() {
    try {
        const response = await fetch('../config/detalles.json'); // ruta al JSON
        const datos = await response.json();

        const grid = document.getElementById('grid-docentes');
        grid.innerHTML = '';

        if (!Array.isArray(datos) || datos.length === 0) {
            grid.innerHTML = '<p style="text-align:center;">No hay datos disponibles</p>';
            return;
        }

        // 📅 Día actual (Ej: "Martes")
        const dias = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
        const hoy = dias[new Date().getDay()].toLowerCase();

        // 🔹 Agrupar docentes por nombre completo
        const docentesPorNombre = {};
        datos.forEach(d => {
            const nombre = `${d.nombre_docente} ${d.apellido_docente}`.trim();
            if (!docentesPorNombre[nombre]) docentesPorNombre[nombre] = [];
            docentesPorNombre[nombre].push(d);
        });

        // 🔹 Buscar los que NO tienen clase hoy
        const disponiblesHoy = Object.entries(docentesPorNombre)
            .filter(([nombre, clases]) => 
                !clases.some(c => (c.dia || '').toLowerCase() === hoy)
            )
            .map(([nombre]) => nombre);

        if (disponiblesHoy.length === 0) {
            grid.innerHTML = `<p style="text-align:center;">Todos los docentes tienen clases hoy (${hoy})</p>`;
            return;
        }

        // 🔹 Crear tarjetas solo para los disponibles
        disponiblesHoy.forEach(nombre => {
            const card = document.createElement('div');
            card.className = 'card verde';
            card.innerHTML = `
                <span class="status">Disponible</span>
                ${nombre}
                <button style="margin-top:10px; padding:6px 12px; border:none; border-radius:6px; background:#1976D2; color:#fff;">
                    Seleccionar
                </button>
            `;
            grid.appendChild(card);
        });

    } catch (error) {
        console.error('Error cargando JSON:', error);
    }
}

document.addEventListener('DOMContentLoaded', cargarDocentes);
</script>


<script>
document.addEventListener("DOMContentLoaded", function() {
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


</body>
</html> 

<