<?php 
session_start();
require_once 'config/cn.php';

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conexion = new cn();
    $con = $conexion->getCon();

    $usuario = $con->real_escape_string($_POST['usuario']);
    $password = $con->real_escape_string($_POST['password']);

    // Consulta del docente con usuario y contraseña
    $sql = "SELECT * FROM docente WHERE nom_usuario = '$usuario' AND ape_usuario = '$password'";

    $result = $con->query($sql);

    if ($result && $result->num_rows > 0) {
        $docente = $result->fetch_assoc();
        $_SESSION['id_docente'] = $docente['id_docente'];
        $_SESSION['nombre'] = $docente['nom_usuario'];
        $_SESSION['apellido'] = $docente['ape_usuario'];

        // Redirigir al docente
        if ($docente['nom_usuario'] == 'kiosko' && $docente['ape_usuario'] == 'itcaSA') {
            header("Location: views/solicitarconsulta.php");
            exit();
        }
        else{
        header("Location: views/vistaDocente.php"); 
        exit();
        }
  
    }
     else {
        $error = "Usuario o contraseña incorrectos.";
    }
}
?>
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

    <?php if (!empty($error)): ?>
      <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="post" action="">
      <div class="mb-3 text-start">
        <label for="usuario" class="form-label">Usuario</label>
        <input type="text" class="form-control" name="usuario" id="usuario" required>
      </div>

      <div class="mb-3 text-start">
        <label for="password" class="form-label">Contraseña</label>
        <input type="password" class="form-control" name="password" id="password" required>
      </div>

      <button type="submit" class="btn btn-login w-100 mt-2">Ingresar</button>
    </form>
  </div>

</body>
</html>
