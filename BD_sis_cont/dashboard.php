<?php
session_start();
require_once "conexion.php";

$usuariosTotal=0; $movimientosTotal=0; $ingresos=0.00; $egresos=0.00;

$res = $conn->query("SELECT COUNT(*) total FROM usuarios");
if ($res && $r=$res->fetch_assoc()) $usuariosTotal=(int)$r['total'];

$res = $conn->query("SELECT COUNT(*) total FROM movimientos");
if ($res && $r=$res->fetch_assoc()) $movimientosTotal=(int)$r['total'];

$res = $conn->query("SELECT 
  COALESCE(SUM(CASE WHEN tipo='Ingreso' THEN monto END),0) ingresos,
  COALESCE(SUM(CASE WHEN tipo='Egreso'  THEN monto END),0) egresos
  FROM movimientos");
if ($res && $r=$res->fetch_assoc()){ $ingresos=(float)$r['ingresos']; $egresos=(float)$r['egresos']; }
$saldo = $ingresos-$egresos;
$msg = isset($_GET['msg']) ? trim($_GET['msg']) : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"><title>Panel principal</title>
  <link rel="stylesheet" href="css/estilos.css">
  <style>
    nav{background:#1e3a8a;color:#fff;padding:14px 20px;display:flex;gap:14px;align-items:center}
    nav a{color:#fff;text-decoration:none;font-weight:600}
    nav a:hover{text-decoration:underline}
    .wrap{max-width:1100px;margin:20px auto;padding:0 16px}
    .grid{display:grid;grid-template-columns:repeat(4,1fr);gap:16px}
    .card{background:#fff;border-radius:14px;box-shadow:0 6px 16px rgba(0,0,0,.06);padding:18px}
    .card h3{margin:0 0 4px 0;font-size:16px;color:#555}
    .card .v{font-size:28px;font-weight:800;margin:4px 0}
    .saldo{font-size:22px}
    .row{display:flex;gap:12px;flex-wrap:wrap;margin-top:18px}
    .btn{display:inline-block;background:#1e3a8a;color:#fff;padding:10px 14px;border-radius:10px;text-decoration:none}
    .btn.alt{background:#0ea5e9}.btn.warn{background:#f59e0b}.btn.danger{background:#ef4444}
    .panel{background:#fff;border-radius:14px;box-shadow:0 6px 16px rgba(0,0,0,.06);padding:18px;margin-top:16px}
    .msg{background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;padding:10px 12px;border-radius:10px;margin:14px 0}
    @media(max-width:900px){.grid{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:540px){.grid{grid-template-columns:1fr}}
  </style>
</head>
<body>
  <nav>
    <a href="dashboard.php">🏠 Inicio</a>
    <a href="listar_usuarios.php">👤 Usuarios</a>
    <a href="movimientos_index.php">💸 Movimientos</a>
    <a href="logout.php" style="margin-left:auto;color:#fca5a5;">🔒 Salir</a>
  </nav>
  <div class="wrap">
    <?php if ($msg!==""): ?><div class="msg"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    <div class="grid">
      <div class="card"><h3>Usuarios</h3><div class="v"><?= number_format($usuariosTotal) ?></div><a class="btn" href="listar_usuarios.php">Ver usuarios</a></div>
      <div class="card"><h3>Movimientos</h3><div class="v"><?= number_format($movimientosTotal) ?></div><a class="btn alt" href="movimientos_index.php">Ver movimientos</a></div>
      <div class="card"><h3>Total Ingresos</h3><div class="v">$ <?= number_format($ingresos,2) ?></div><a class="btn warn" href="movimientos_index.php">Detalle</a></div>
      <div class="card"><h3>Total Egresos</h3><div class="v">$ <?= number_format($egresos,2) ?></div><a class="btn danger" href="movimientos_index.php">Detalle</a></div>
    </div>
    <div class="panel">
      <h2>Saldo actual</h2>
      <p class="saldo"><strong>$ <?= number_format($saldo,2) ?></strong></p>
      <div class="row">
        <a class="btn" href="crear_usuarios.php">➕ Nuevo usuario</a>
        <a class="btn alt" href="movimientos_create.php">➕ Nuevo movimiento</a>
        <a class="btn" href="listar_usuarios.php">📋 Listar usuarios</a>
        <a class="btn alt" href="movimientos_index.php">📋 Listar movimientos</a>
      </div>
    </div>
  </div>
</body>
</html>
