<?php
require_once 'conexion.php';

$ok    = $_GET['ok']    ?? null;
$error = $_GET['error'] ?? null;

// Ajusta los campos a tu tabla real:
$sql = "SELECT id, nombre, usuario, correo FROM usuarios ORDER BY id DESC";
$res = $conn->query($sql);
if (!$res) {
  die("Error en consulta: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Usuarios</title>
  <link rel="stylesheet" href="css/estilo.css"><!--archivo CSS actual -->
</head>
<body>

<?php if($ok==='1'): ?><div class="alert success">Usuario creado.</div><?php endif; ?>
<?php if($ok==='2'): ?><div class="alert success">Usuario actualizado.</div><?php endif; ?>
<?php if($ok==='3'): ?><div class="alert success">Usuario eliminado.</div><?php endif; ?>
<?php if($error):   ?><div class="alert error">Ocurrió un error (<?= htmlspecialchars($error) ?>).</div><?php endif; ?>

<h2>Usuarios Registrados</h2>
<a href="crear_usuarios.php" class="btn-primario">+ Nuevo Usuario</a>
<br><br>

<table class="tabla">
  <thead>
    <tr>
      <th>ID</th>
      <th>Nombre</th>
      <th>Usuario</th>
      <th>Correo</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
  <?php while($row = $res->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['id']) ?></td>
      <td><?= htmlspecialchars($row['nombre']) ?></td>
      <td><?= htmlspecialchars($row['usuario']) ?></td>
      <td><?= htmlspecialchars($row['correo']) ?></td>
      <td>
        <a class="btn-editar"  href="editar_usuarios.php?id=<?= $row['id'] ?>">Editar</a>
        <a class="btn-eliminar" href="eliminar_usuarios.php?id=<?= $row['id'] ?>"
           onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>

<p><a href="dashboard.php">⬅ Volver al panel</a></p>
</body>
</html>
