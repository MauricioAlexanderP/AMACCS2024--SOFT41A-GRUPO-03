<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inicio de sesión</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f5f5f5;
      font-family: "Segoe UI", sans-serif;
    }
    .login-card {
      background: #fff;
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      padding: 2rem;
      max-width: 400px;
      margin: 5% auto;
    }
    .status-badge {
      display: inline-block;
      padding: 0.4rem 0.8rem;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 600;
    }
    .status-green { background-color: #d4edda; color: #155724; }
    .status-yellow { background-color: #fff3cd; color: #856404; }
    .status-red { background-color: #f8d7da; color: #721c24; }

    .btn-login {
      background-color: #0d6efd;
      color: white;
      font-weight: 500;
      border-radius: 10px;
      transition: 0.2s;
    }
    .btn-login:hover {
      background-color: #0b5ed7;
    }
  </style>
</head>
<body>

  <div class="login-card text-center">
    <h4 class="mb-3">Inicio de sesión para Docentes</h4>


    <form>
      <div class="mb-3 text-start">
        <label for="usuario" class="form-label">Usuario</label>
        <input type="text" class="form-control" id="usuario" placeholder="Ingresa tu usuario">
      </div>

      <div class="mb-3 text-start">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="password" placeholder="password">
      </div>

      <button type="submit" class="btn btn-login w-100 mt-2">Ingresar</button>
    </form>

    
  </div>

</body>
</html>
