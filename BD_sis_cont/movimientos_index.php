<?php
require_once 'conexion.php';
$res = $conn->query("SELECT id, fecha, descripcion, tipo, monto FROM movimientos ORDER BY fecha DESC, id DESC");
$ok = $_GET['ok'] ?? null; $error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><title>Listado de Movimientos</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
<?php if($ok==='1'): ?><div class="alert success">Movimiento creado.</div><?php endif; ?>
<?php if($ok==='2'): ?><div class="alert success">Movimiento actualizado.</div><?php endif; ?>
<?php if($ok==='3'): ?><div class="alert success">Movimiento eliminado.</div><?php endif; ?>
<?php if($error):   ?><div class="alert error">Ocurrió un error (<?= htmlspecialchars($error) ?>).</div><?php endif; ?>

<h2>Movimientos Registrados</h2>
<a href="movimientos_create.php" class="btn-agregar-mov">+ Nuevo Movimiento</a>
<br><br>
<table class="tabla-movimientos">
  <thead>
    <tr><th>ID</th><th>Fecha</th><th>Descripción</th><th>Tipo</th><th>Monto</th><th>Acciones</th></tr>
  </thead>
  <tbody>
  <?php while($row = $res->fetch_assoc()): ?>
    <tr>
      <td><?= htmlspecialchars($row['id']) ?></td>
      <td><?= htmlspecialchars($row['fecha']) ?></td>
      <td><?= htmlspecialchars($row['descripcion']) ?></td>
      <td><?= htmlspecialchars($row['tipo']) ?></td>
      <td><?= htmlspecialchars($row['monto']) ?></td>
      <td>
        <a class="btn-editar-mov"  href="movimientos_edit.php?id=<?= $row['id'] ?>">Editar</a>
        <a class="btn-eliminar-mov" href="movimientos_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar este movimiento?')">Eliminar</a>
      </td>
    </tr>
  <?php endwhile; ?>
  </tbody>
</table>
</body>
</html>
