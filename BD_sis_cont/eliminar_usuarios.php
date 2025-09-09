<?php
require_once 'conexion.php';
$id = intval($_GET['id'] ?? 0);
if ($id<=0) { header('Location: listar_usuarios.php?error=id'); exit; }

$stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) { header('Location: listar_usuarios.php?ok=3'); }
else { header('Location: listar_usuarios.php?error=db'); }
exit;
