<?php
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $id = intval($_GET['id'] ?? 0);
  if ($id<=0) { header('Location: listar_usuarios.php?error=id'); exit; }

  $stmt = $conn->prepare("SELECT id, nombre, usuario, correo FROM usuarios WHERE id=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $usuario = $stmt->get_result()->fetch_assoc();
  if (!$usuario) { header('Location: listar_usuarios.php?error=no_encontrado'); exit; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id      = intval($_POST['id'] ?? 0);
  $nombre  = trim($_POST['nombre']  ?? '');
  $usuarioF= trim($_POST['usuario'] ?? '');
  $correo  = trim($_POST['correo']  ?? '');

  if ($id<=0 || $nombre==='' || $usuarioF==='' || $correo==='') {
    header('Location: editar_usuarios.php?id='.$id.'&error=datos_invalidos'); exit;
  }

  $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, usuario=?, correo=? WHERE id=?");
  $stmt->bind_param("sssi", $nombre, $usuarioF, $correo, $id);
  if ($stmt->execute()) { header('Location: listar_usuarios.php?ok=2'); }
  else { header('Location: editar_usuarios.php?id='.$id.'&error=db'); }
  exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><title>Editar Usuario</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <h2>Editar Usuario</h2>
  <form action="editar_usuarios.php" method="POST">
    <input type="hidden" name="id" value="<?= htmlspecialchars($usuario['id']) ?>">
    <label>Nombre</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
    <label>Usuario</label>
    <input type="text" name="usuario" value="<?= htmlspecialchars($usuario['usuario']) ?>" required>
    <label>Correo</label>
    <input type="email" name="correo" value="<?= htmlspecialchars($usuario['correo']) ?>" required>
    <button type="submit" class="btn-primario">Actualizar</button>
    <a href="listar_usuarios.php">Cancelar</a>
  </form>
</body>
</html>
