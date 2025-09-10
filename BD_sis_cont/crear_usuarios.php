<?php
require_once 'conexion.php';
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nombre  = trim($_POST['nombre']  ?? '');
  $email  = trim($_POST['email']   ?? '');
  $usuario = trim($_POST['usuario'] ?? ''); // si manejas "usuario"
  $rol     = trim($_POST['rol']     ?? 'usuario');
  $password_plano = $_POST['password'] ?? '';

  if ($nombre==='' || $email==='' || $password_plano==='') {
    $mensaje = "Faltan datos obligatorios.";
  } else {
    $password = password_hash($password_plano, PASSWORD_DEFAULT);
    // Ajusta columnas según la tabla 
    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, usuario, correo, password, rol) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $nombre, $usuario, $email, $password, $rol);
    if ($stmt->execute()) { header("Location: listar_usuarios.php?ok=1"); exit; }
    else { $mensaje = "Error: ".$conn->error; }
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><title>Crear Usuario</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <h2>Nuevo Usuario</h2>
  <?php if($mensaje!==""): ?><div class="alert error"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
  <form method="POST">
    <label>Nombre:</label>
    <input type="text" name="nombre" required>

    <label>Usuario (alias):</label>
    <input type="text" name="usuario" required>

    <label>Correo:</label>
    <input type="email" name="email" required>

    <label>Contraseña:</label>
    <input type="password" name="password" required>

    <label>Rol:</label>
    <select name="rol" required>
      <option value="usuario">Usuario</option>
      <option value="admin">Administrador</option>
    </select>

    <button type="submit" class="btn-primario">Guardar</button>
    <a href="listar_usuarios.php">Cancelar</a>
  </form>
</body>
</html>

