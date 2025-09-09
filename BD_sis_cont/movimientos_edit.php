<?php
require_once 'conexion.php';
$id = intval($_GET['id'] ?? 0);
if ($id<=0) { header("Location: movimientos_index.php?error=id"); exit; }

if ($_SERVER["REQUEST_METHOD"] === "GET") {
  $stmt = $conn->prepare("SELECT id, fecha, descripcion, tipo, monto FROM movimientos WHERE id=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $mov = $stmt->get_result()->fetch_assoc();
  if (!$mov) { header("Location: movimientos_index.php?error=no_encontrado"); exit; }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $fecha = $_POST['fecha'] ?? '';
  $descripcion = trim($_POST['descripcion'] ?? '');
  $tipo  = $_POST['tipo'] ?? '';
  $monto = $_POST['monto'] ?? '';
  if ($fecha==='' || $descripcion==='' || !in_array($tipo, ['Ingreso','Egreso']) || $monto==='') {
    header("Location: movimientos_edit.php?id=$id&error=datos_invalidos"); exit;
  }
  $stmt = $conn->prepare("UPDATE movimientos SET fecha=?, descripcion=?, tipo=?, monto=? WHERE id=?");
  $stmt->bind_param("sssdi", $fecha, $descripcion, $tipo, $monto, $id);
  if ($stmt->execute()) { header("Location: movimientos_index.php?ok=2"); }
  else { header("Location: movimientos_edit.php?id=$id&error=db"); }
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><title>Editar Movimiento</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <h1>Editar Movimiento</h1>
  <?php if(isset($_GET['error'])): ?><div class="alert error">Error: <?= htmlspecialchars($_GET['error']) ?></div><?php endif; ?>
  <form method="post">
    Fecha: <input type="date" name="fecha" value="<?= htmlspecialchars($mov['fecha']) ?>" required><br><br>
    Descripción: <input type="text" name="descripcion" value="<?= htmlspecialchars($mov['descripcion']) ?>" required><br><br>
    Tipo:
    <select name="tipo" required>
      <option value="Ingreso" <?= $mov['tipo']==="Ingreso" ? "selected" : "" ?>>Ingreso</option>
      <option value="Egreso"  <?= $mov['tipo']==="Egreso"  ? "selected" : "" ?>>Egreso</option>
    </select><br><br>
    Monto: <input type="number" step="0.01" name="monto" value="<?= htmlspecialchars($mov['monto']) ?>" required><br><br>
    <button type="submit" class="btn-editar-mov">Actualizar</button>
    <a href="movimientos_index.php">⬅ Volver</a>
  </form>
</body>
</html>
