 <?php
require_once 'conexion.php';
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $fecha = $_POST['fecha'] ?? '';
  $descripcion = trim($_POST['descripcion'] ?? '');
  $tipo  = $_POST['tipo'] ?? '';
  $monto = $_POST['monto'] ?? '';

  if ($fecha==='' || $descripcion==='' || !in_array($tipo, ['Ingreso','Egreso']) || $monto==='') {
    header("Location: movimientos_create.php?error=datos_invalidos"); exit;
  }

  $stmt = $conn->prepare("INSERT INTO movimientos (fecha, descripcion, tipo, monto) VALUES (?,?,?,?)");
  $stmt->bind_param("sssd", $fecha, $descripcion, $tipo, $monto);
  if ($stmt->execute()) { header("Location: movimientos_index.php?ok=1"); exit; }
  else { header("Location: movimientos_create.php?error=db"); exit; }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><title>Nuevo Movimiento</title>
  <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
  <h1>Registrar Movimiento</h1>
  <?php if(isset($_GET['error'])): ?><div class="alert error">Error: <?= htmlspecialchars($_GET['error']) ?></div><?php endif; ?>
  <form method="post">
    Fecha: <input type="date" name="fecha" required><br><br>
    Descripción: <input type="text" name="descripcion" required><br><br>
    Tipo:
    <select name="tipo" required>
      <option value="Ingreso">Ingreso</option>
      <option value="Egreso">Egreso</option>
    </select><br><br>
    Monto: <input type="number" step="0.01" name="monto" required><br><br>
    <button type="submit" class="btn-agregar-mov">Guardar</button>
    <a href="movimientos_index.php">⬅ Volver</a>
  </form>
</body>
</html>
