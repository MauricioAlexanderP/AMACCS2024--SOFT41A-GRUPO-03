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

    .btn-editar {
      background-color: #28a745;
      color: #fff;
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
      background-color: #0d6efd;
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
      background-color: #0d6efd;
      color: white;
      border-radius: 15px 15px 0 0;
    }

    .modal-header.editar {
      background-color: #28a745;
    }

    .table {
      margin-bottom: 0;
      font-size: 0.85rem;
    }

    .table thead {
      background-color: #0d6efd;
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

  <div class="container py-5">
    <h3 class="mb-4 text-center">Disponibilidad de Docentes</h3>

    <div class="row justify-content-center g-4">

      <!-- Card 1 -->
      <div class="col-md-4 col-sm-6">
        <div class="docente-card">
          <img src="https://picsum.photos/400/200?random=1" alt="Profesor" class="docente-img">
          <div class="docente-body">
            <p class="docente-nombre">Prof. Juan Martínez</p>
            <p class="docente-area">Área: Matemáticas</p>
            <span class="status-badge status-green">Disponible</span>
            <div class="mt-3 botones-group">
              <button class="btn-editar" data-bs-toggle="modal" data-bs-target="#editarModal1">Editar Disponibilidad</button>
              <button class="btn-horario" data-bs-toggle="modal" data-bs-target="#horarioModal1">Ver Horario</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2 -->
      <div class="col-md-4 col-sm-6">
        <div class="docente-card">
          <img src="https://picsum.photos/400/200?random=2" alt="Profesora" class="docente-img">
          <div class="docente-body">
            <p class="docente-nombre">Dra. María Gómez</p>
            <p class="docente-area">Área: Biología</p>
            <span class="status-badge status-yellow">Revisando tareas</span>
            <div class="mt-3 botones-group">
              <button class="btn-editar" data-bs-toggle="modal" data-bs-target="#editarModal2">Editar Disponibilidad</button>
              <button class="btn-horario" data-bs-toggle="modal" data-bs-target="#horarioModal2">Ver Horario</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 3 -->
      <div class="col-md-4 col-sm-6">
        <div class="docente-card">
          <img src="https://picsum.photos/400/200?random=3" alt="Ingeniero" class="docente-img">
          <div class="docente-body">
            <p class="docente-nombre">Ing. Carlos Pérez</p>
            <p class="docente-area">Área: Ingeniería Civil</p>
            <span class="status-badge status-red">Atendiendo estudiante</span>
            <div class="mt-3 botones-group">
              <button class="btn-editar" data-bs-toggle="modal" data-bs-target="#editarModal3">Editar Disponibilidad</button>
              <button class="btn-horario" data-bs-toggle="modal" data-bs-target="#horarioModal3">Ver Horario</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 4 -->
      <div class="col-md-4 col-sm-6">
        <div class="docente-card">
          <img src="https://picsum.photos/400/200?random=4" alt="Profesora" class="docente-img">
          <div class="docente-body">
            <p class="docente-nombre">Lic. Ana Torres</p>
            <p class="docente-area">Área: Literatura</p>
            <span class="status-badge status-green">Disponible</span>
            <div class="mt-3 botones-group">
              <button class="btn-editar" data-bs-toggle="modal" data-bs-target="#editarModal4">Editar Disponibilidad</button>
              <button class="btn-horario" data-bs-toggle="modal" data-bs-target="#horarioModal4">Ver Horario</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 5 -->
      <div class="col-md-4 col-sm-6">
        <div class="docente-card">
          <img src="https://picsum.photos/400/200?random=5" alt="Profesor" class="docente-img">
          <div class="docente-body">
            <p class="docente-nombre">Dr. Luis Ramírez</p>
            <p class="docente-area">Área: Química</p>
            <span class="status-badge status-yellow">En laboratorio</span>
            <div class="mt-3 botones-group">
              <button class="btn-editar" data-bs-toggle="modal" data-bs-target="#editarModal5">Editar Disponibilidad</button>
              <button class="btn-horario" data-bs-toggle="modal" data-bs-target="#horarioModal5">Ver Horario</button>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 6 -->
      <div class="col-md-4 col-sm-6">
        <div class="docente-card">
          <img src="https://picsum.photos/400/200?random=6" alt="Profesora" class="docente-img">
          <div class="docente-body">
            <p class="docente-nombre">Prof. Sandra López</p>
            <p class="docente-area">Área: Historia</p>
            <span class="status-badge status-green">Disponible</span>
            <div class="mt-3 botones-group">
              <button class="btn-editar" data-bs-toggle="modal" data-bs-target="#editarModal6">Editar Disponibilidad</button>
              <button class="btn-horario" data-bs-toggle="modal" data-bs-target="#horarioModal6">Ver Horario</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  <!-- Modal Editar Disponibilidad 1 -->
  <div class="modal fade" id="editarModal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header editar">
          <h5 class="modal-title">Editar Disponibilidad - Prof. Juan Martínez</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="actualizar_disponibilidad.php" method="POST">
            <input type="hidden" name="docente_id" value="1">
            
            <div class="mb-3">
              <label for="estado1" class="form-label">Estado de Disponibilidad:</label>
              <select class="form-select" id="estado1" name="estado" required>
                <option value="disponible" selected>Disponible</option>
                <option value="ocupado">Atendiendo estudiante</option>
                <option value="revisando">Revisando tareas</option>
                <option value="reunion">En reunión</option>
                <option value="laboratorio">En laboratorio</option>
                <option value="almuerzo">En almuerzo</option>
              </select>
            </div>

            <div class="mb-3">
              <label for="notas1" class="form-label">Notas adicionales (opcional):</label>
              <textarea class="form-control" id="notas1" name="notas" rows="3" placeholder="Ej: Regreso en 15 minutos"></textarea>
            </div>

            <div class="d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Horario 1 -->
  <div class="modal fade" id="horarioModal1" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Horario: Prof. Juan Martínez - Matemáticas</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 15%;">Hora</th>
                  <th>Lunes</th>
                  <th>Martes</th>
                  <th>Miércoles</th>
                  <th>Jueves</th>
                  <th>Viernes</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="hora-cell">7:00-8:00</td>
                  <td class="clase-ocupada">Cálculo I<br><small>Aula 201</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Cálculo I<br><small>Aula 201</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Álgebra<br><small>Aula 105</small></td>
                </tr>
                <tr>
                  <td class="hora-cell">8:00-9:00</td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Cálculo II<br><small>Aula 303</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Cálculo II<br><small>Aula 303</small></td>
                  <td class="clase-libre">Libre</td>
                </tr>
                <tr>
                  <td class="hora-cell">9:00-10:00</td>
                  <td class="clase-ocupada">Álgebra<br><small>Aula 105</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Álgebra<br><small>Aula 105</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Cálculo I<br><small>Aula 201</small></td>
                </tr>
                <tr>
                  <td class="hora-cell">10:00-11:00</td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Geometría<br><small>Aula 210</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Geometría<br><small>Aula 210</small></td>
                  <td class="clase-libre">Libre</td>
                </tr>
                <tr>
                  <td class="hora-cell">11:00-12:00</td>
                  <td class="clase-ocupada">Tutoría<br><small>Oficina 12</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Tutoría<br><small>Oficina 12</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Reunión Depto<br><small>Sala 401</small></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modales para los demás docentes (2-6) con el mismo formato -->
  <!-- Modal Editar 2 -->
  <div class="modal fade" id="editarModal2" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header editar">
          <h5 class="modal-title">Editar Disponibilidad - Dra. María Gómez</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="actualizar_disponibilidad.php" method="POST">
            <input type="hidden" name="docente_id" value="2">
            <div class="mb-3">
              <label class="form-label">Estado de Disponibilidad:</label>
              <select class="form-select" name="estado" required>
                <option value="disponible">Disponible</option>
                <option value="ocupado">Atendiendo estudiante</option>
                <option value="revisando" selected>Revisando tareas</option>
                <option value="reunion">En reunión</option>
                <option value="laboratorio">En laboratorio</option>
                <option value="almuerzo">En almuerzo</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Notas adicionales:</label>
              <textarea class="form-control" name="notas" rows="3"></textarea>
            </div>
            <div class="d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Horario 2 -->
  <div class="modal fade" id="horarioModal2" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Horario: Dra. María Gómez - Biología</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 15%;">Hora</th>
                  <th>Lunes</th>
                  <th>Martes</th>
                  <th>Miércoles</th>
                  <th>Jueves</th>
                  <th>Viernes</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="hora-cell">7:00-8:00</td>
                  <td class="clase-ocupada">Biología I<br><small>Lab 101</small></td>
                  <td class="clase-ocupada">Biología I<br><small>Lab 101</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Ecología<br><small>Aula 305</small></td>
                  <td class="clase-libre">Libre</td>
                </tr>
                <tr>
                  <td class="hora-cell">8:00-9:00</td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Genética<br><small>Aula 210</small></td>
                  <td class="clase-ocupada">Biología II<br><small>Lab 102</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Genética<br><small>Aula 210</small></td>
                </tr>
                <tr>
                  <td class="hora-cell">9:00-10:00</td>
                  <td class="clase-ocupada">Biología II<br><small>Lab 102</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Ecología<br><small>Aula 305</small></td>
                  <td class="clase-ocupada">Biología II<br><small>Lab 102</small></td>
                  <td class="clase-libre">Libre</td>
                </tr>
                <tr>
                  <td class="hora-cell">10:00-11:00</td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Tutoría<br><small>Oficina 15</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Tutoría<br><small>Oficina 15</small></td>
                  <td class="clase-ocupada">Investigación<br><small>Lab 103</small></td>
                </tr>
                <tr>
                  <td class="hora-cell">11:00-12:00</td>
                  <td class="clase-ocupada">Investigación<br><small>Lab 103</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Investigación<br><small>Lab 103</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-libre">Libre</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar 3 -->
  <div class="modal fade" id="editarModal3" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header editar">
          <h5 class="modal-title">Editar Disponibilidad - Ing. Carlos Pérez</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="actualizar_disponibilidad.php" method="POST">
            <input type="hidden" name="docente_id" value="3">
            <div class="mb-3">
              <label class="form-label">Estado de Disponibilidad:</label>
              <select class="form-select" name="estado" required>
                <option value="disponible">Disponible</option>
                <option value="ocupado" selected>Atendiendo estudiante</option>
                <option value="revisando">Revisando tareas</option>
                <option value="reunion">En reunión</option>
                <option value="laboratorio">En laboratorio</option>
                <option value="almuerzo">En almuerzo</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Notas adicionales:</label>
              <textarea class="form-control" name="notas" rows="3"></textarea>
            </div>
            <div class="d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Horario 3 -->
  <div class="modal fade" id="horarioModal3" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Horario: Ing. Carlos Pérez - Ingeniería Civil</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 15%;">Hora</th>
                  <th>Lunes</th>
                  <th>Martes</th>
                  <th>Miércoles</th>
                  <th>Jueves</th>
                  <th>Viernes</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="hora-cell">7:00-8:00</td>
                  <td class="clase-ocupada">Estructuras I<br><small>Aula 401</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Estructuras I<br><small>Aula 401</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Materiales<br><small>Lab 205</small></td>
                </tr>
                <tr>
                  <td class="hora-cell">8:00-9:00</td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Hidráulica<br><small>Aula 310</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Hidráulica<br><small>Aula 310</small></td>
                  <td class="clase-libre">Libre</td>
                </tr>
                <tr>
                  <td class="hora-cell">9:00-10:00</td>
                  <td class="clase-ocupada">Geotecnia<br><small>Aula 215</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Geotecnia<br><small>Aula 215</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Estructuras II<br><small>Aula 402</small></td>
                </tr>
                <tr>
                  <td class="hora-cell">10:00-11:00</td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Estructuras II<br><small>Aula 402</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Proyecto Final<br><small>Taller 1</small></td>
                  <td class="clase-libre">Libre</td>
                </tr>
                <tr>
                  <td class="hora-cell">11:00-12:00</td>
                  <td class="clase-ocupada">Tutoría<br><small>Oficina 20</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Tutoría<br><small>Oficina 20</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Supervisión Obra<br><small>Campo</small></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar 4 -->
  <div class="modal fade" id="editarModal4" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header editar">
          <h5 class="modal-title">Editar Disponibilidad - Lic. Ana Torres</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="actualizar_disponibilidad.php" method="POST">
            <input type="hidden" name="docente_id" value="4">
            <div class="mb-3">
              <label class="form-label">Estado de Disponibilidad:</label>
              <select class="form-select" name="estado" required>
                <option value="disponible" selected>Disponible</option>
                <option value="ocupado">Atendiendo estudiante</option>
                <option value="revisando">Revisando tareas</option>
                <option value="reunion">En reunión</option>
                <option value="laboratorio">En laboratorio</option>
                <option value="almuerzo">En almuerzo</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Notas adicionales:</label>
              <textarea class="form-control" name="notas" rows="3"></textarea>
            </div>
            <div class="d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Horario 4 -->
  <div class="modal fade" id="horarioModal4" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Horario: Lic. Ana Torres - Literatura</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 15%;">Hora</th>
                  <th>Lunes</th>
                  <th>Martes</th>
                  <th>Miércoles</th>
                  <th>Jueves</th>
                  <th>Viernes</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="hora-cell">7:00-8:00</td>
                  <td class="clase-ocupada">Literatura I<br><small>Aula 115</small></td>
                  <td class="clase-ocupada">Redacción<br><small>Aula 120</small></td>
                  <td class="clase-ocupada">Literatura I<br><small>Aula 115</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Poesía<br><small>Aula 118</small></td>
                </tr>
                <tr>
                  <td class="hora-cell">8:00-9:00</td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Literatura II<br><small>Aula 116</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Literatura II<br><small>Aula 116</small></td>
                  <td class="clase-libre">Libre</td>
                </tr>
                <tr>
                  <td class="hora-cell">9:00-10:00</td>
                  <td class="clase-ocupada">Redacción<br><small>Aula 120</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Teatro<br><small>Auditorio</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Literatura I<br><small>Aula 115</small></td>
                </tr>
                <tr>
                  <td class="hora-cell">10:00-11:00</td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Poesía<br><small>Aula 118</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Redacción<br><small>Aula 120</small></td>
                  <td class="clase-libre">Libre</td>
                </tr>
                <tr>
                  <td class="hora-cell">11:00-12:00</td>
                  <td class="clase-ocupada">Club Lectura<br><small>Biblioteca</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Tutoría<br><small>Oficina 8</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Taller Escritura<br><small>Aula 122</small></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar 5 -->
  <div class="modal fade" id="editarModal5" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header editar">
          <h5 class="modal-title">Editar Disponibilidad - Dr. Luis Ramírez</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="actualizar_disponibilidad.php" method="POST">
            <input type="hidden" name="docente_id" value="5">
            <div class="mb-3">
              <label class="form-label">Estado de Disponibilidad:</label>
              <select class="form-select" name="estado" required>
                <option value="disponible">Disponible</option>
                <option value="ocupado">Atendiendo estudiante</option>
                <option value="revisando">Revisando tareas</option>
                <option value="reunion">En reunión</option>
                <option value="laboratorio" selected>En laboratorio</option>
                <option value="almuerzo">En almuerzo</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Notas adicionales:</label>
              <textarea class="form-control" name="notas" rows="3"></textarea>
            </div>
            <div class="d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Horario 5 -->
  <div class="modal fade" id="horarioModal5" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Horario: Dr. Luis Ramírez - Química</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 15%;">Hora</th>
                  <th>Lunes</th>
                  <th>Martes</th>
                  <th>Miércoles</th>
                  <th>Jueves</th>
                  <th>Viernes</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="hora-cell">7:00-8:00</td>
                  <td class="clase-ocupada">Química Org.<br><small>Lab 301</small></td>
                  <td class="clase-ocupada">Química Org.<br><small>Lab 301</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Química Gen.<br><small>Aula 205</small></td>
                  <td class="clase-libre">Libre</td>
                </tr>
                <tr>
                  <td class="hora-cell">8:00-9:00</td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Bioquímica<br><small>Lab 302</small></td>
                  <td class="clase-ocupada">Química Inorg.<br><small>Lab 303</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Bioquímica<br><small>Lab 302</small></td>
                </tr>
                <tr>
                  <td class="hora-cell">9:00-10:00</td>
                  <td class="clase-ocupada">Química Gen.<br><small>Aula 205</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Química Org.<br><small>Lab 301</small></td>
                  <td class="clase-ocupada">Bioquímica<br><small>Lab 302</small></td>
                  <td class="clase-libre">Libre</td>
                </tr>
                <tr>
                  <td class="hora-cell">10:00-11:00</td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Química Gen.<br><small>Aula 205</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Química Inorg.<br><small>Lab 303</small></td>
                  <td class="clase-ocupada">Investigación<br><small>Lab 304</small></td>
                </tr>
                <tr>
                  <td class="hora-cell">11:00-12:00</td>
                  <td class="clase-ocupada">Tutoría<br><small>Oficina 25</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Investigación<br><small>Lab 304</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Seminario<br><small>Sala 302</small></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Editar 6 -->
  <div class="modal fade" id="editarModal6" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header editar">
          <h5 class="modal-title">Editar Disponibilidad - Prof. Sandra López</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="actualizar_disponibilidad.php" method="POST">
            <input type="hidden" name="docente_id" value="6">
            <div class="mb-3">
              <label class="form-label">Estado de Disponibilidad:</label>
              <select class="form-select" name="estado" required>
                <option value="disponible" selected>Disponible</option>
                <option value="ocupado">Atendiendo estudiante</option>
                <option value="revisando">Revisando tareas</option>
                <option value="reunion">En reunión</option>
                <option value="laboratorio">En laboratorio</option>
                <option value="almuerzo">En almuerzo</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Notas adicionales:</label>
              <textarea class="form-control" name="notas" rows="3"></textarea>
            </div>
            <div class="d-flex justify-content-end gap-2">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-success">Guardar Cambios</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Horario 6 -->
  <div class="modal fade" id="horarioModal6" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Horario: Prof. Sandra López - Historia</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th style="width: 15%;">Hora</th>
                  <th>Lunes</th>
                  <th>Martes</th>
                  <th>Miércoles</th>
                  <th>Jueves</th>
                  <th>Viernes</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="hora-cell">7:00-8:00</td>
                  <td class="clase-ocupada">Historia Universal<br><small>Aula 130</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Historia Universal<br><small>Aula 130</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">H. Latinoam.<br><small>Aula 135</small></td>
                </tr>
                <tr>
                  <td class="hora-cell">8:00-9:00</td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Historia Antigua<br><small>Aula 132</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Historia Antigua<br><small>Aula 132</small></td>
                  <td class="clase-libre">Libre</td>
                </tr>
                <tr>
                  <td class="hora-cell">9:00-10:00</td>
                  <td class="clase-ocupada">H. Latinoam.<br><small>Aula 135</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">H. Moderna<br><small>Aula 140</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Historia Universal<br><small>Aula 130</small></td>
                </tr>
                <tr>
                  <td class="hora-cell">10:00-11:00</td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">H. Moderna<br><small>Aula 140</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">H. Latinoam.<br><small>Aula 135</small></td>
                  <td class="clase-libre">Libre</td>
                </tr>
                <tr>
                  <td class="hora-cell">11:00-12:00</td>
                  <td class="clase-ocupada">Tutoría<br><small>Oficina 18</small></td>
                  <td class="clase-libre">Libre</td>
                  <td class="clase-ocupada">Seminario<br><small>Sala 250</small></td>
                  <td class="clase-ocupada">Tutoría<br><small>Oficina 18</small></td>
                  <td class="clase-ocupada">Investigación<br><small>Biblioteca</small></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>