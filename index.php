<!doctype html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Disponibilidad de Docentes</title>
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

<div class="header">
    <h1>Disponibilidad de Docentes</h1>
    <img src="img/logo.png" alt="Logo de la empresa">
</div>

<div class="grid">
    <div class="card blanco">Prof. Martínez<button class="btn-detalles">Ver detalles</button></div>
    <div class="card amarillo">Dra. Gómez<button class="btn-detalles">Ver detalles</button></div>
    <div class="card rojo">Ing. Pérez<button class="btn-detalles">Ver detalles</button></div>
    <div class="card blanco">Prof. López<button class="btn-detalles">Ver detalles</button></div>
    <div class="card amarillo">Dra. Ramírez<button class="btn-detalles">Ver detalles</button></div>
    <div class="card rojo">Ing. Torres<button class="btn-detalles">Ver detalles</button></div>
</div>

</body>
</html>