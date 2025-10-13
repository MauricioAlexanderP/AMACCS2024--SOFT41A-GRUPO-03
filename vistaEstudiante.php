<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Disponibilidad de Docentes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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

    .btn-detalle {
      background-color: #0d6efd;
      color: #fff;
      border-radius: 10px;
      padding: 0.4rem 1.2rem;
      font-weight: 500;
      transition: 0.2s;
    }

    .btn-detalle:hover {
      background-color: #0b5ed7;
    }
  </style>
</head>
<body>

  <div class="container py-5">
    <h3 class="mb-4 text-center">Disponibilidad de Docentes</h3>

    <div class="row justify-content-center g-4">

      <!-- Card 1 -->
      <div class="col-md-4 col-sm-6">
        <div class="docente-card">
          <img src="https://picsum.photos/400/200?random=1" alt="Profesor" class="docente-img" id="img-Docente">
          <div class="docente-body">
            <p class="docente-nombre" id="docente-nombre" >Prof. Martínez</p>
            <p class="docente-area" id="docente-area">Área: Matemáticas</p>
            <span class="status-badge status-green">Disponible</span>
            <div class="mt-3">
              <button class="btn btn-detalle" id="solicitar">Solicitar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-4 col-sm-6">
        <div class="docente-card">
          <img src="https://picsum.photos/400/200?random=2" alt="Profesora" class="docente-img" id="img-Docente">
          <div class="docente-body">
            <p class="docente-nombre" id="docente-nombre">Dra. Gómez</p>
            <p class="docente-area" id="docente-area">Área: Biología</p>
            <span class="status-badge status-yellow">Revisando tareas</span>
            <div class="mt-3">
              <button class="btn btn-detalle" id="solicitar">Solicitar</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-md-4 col-sm-6">
        <div class="docente-card">
          <img src="https://picsum.photos/400/200?random=3" alt="Ingeniero" class="docente-img" id="img-Docente">
          <div class="docente-body">
            <p class="docente-nombre" id="docente-nombre">Ing. Pérez</p>
            <p class="docente-area" id="docente-area">Área: Ingeniería Civil</p>
            <span class="status-badge status-red">Atendiendo a un estudiante</span>
            <div class="mt-3">
              <button class="btn btn-detalle" id="solicitar">Solicitar</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

</body>
</html>
