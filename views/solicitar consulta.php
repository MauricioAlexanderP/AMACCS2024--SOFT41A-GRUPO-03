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
<div class="grid">
    <div class="card verde"><span class="status">Disponible</span>Prof. Martínez<button style="margin-top:10px; padding:6px 12px; border:none; border-radius:6px; background:#1976D2; color:#fff;">Seleccionar</button></div>
    <div class="card amarillo"><span class="status">Ocupado</span>Dra. Gómez<button style="margin-top:10px; padding:6px 12px; border:none; border-radius:6px; background:#1976D2; color:#fff;">Seleccionar</button></div>
    <div class="card rojo"><span class="status">No disponible</span>Ing. Pérez<button style="margin-top:10px; padding:6px 12px; border:none; border-radius:6px; background:#1976D2; color:#fff;">Seleccionar</button></div>
    <div class="card verde"><span class="status">Disponible</span>Prof. López<button style="margin-top:10px; padding:6px 12px; border:none; border-radius:6px; background:#1976D2; color:#fff;">Seleccionar</button></div>
    <div class="card amarillo"><span class="status">Ocupado</span>Dra. Ramírez<button style="margin-top:10px; padding:6px 12px; border:none; border-radius:6px; background:#1976D2; color:#fff;">Seleccionar</button></div>
    <div class="card rojo"><span class="status">No disponible</span>Ing. Torres<button style="margin-top:10px; padding:6px 12px; border:none; border-radius:6px; background:#1976D2; color:#fff;">Seleccionar</button></div>
</div>

</body>
</html>
