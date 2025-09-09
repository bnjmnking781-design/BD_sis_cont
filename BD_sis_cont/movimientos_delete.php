<?php
require_once 'conexion.php';
$id = intval($_GET['id'] ?? 0);
if ($id<=0) { header("Location: movimientos_index.php?error=id"); exit; }

$stmt = $conn->prepare("DELETE FROM movimientos WHERE id=?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) { header("Location: movimientos_index.php?ok=3"); }
else { header("Location: movimientos_index.php?error=db"); }
exit;
