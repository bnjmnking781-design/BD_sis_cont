<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../conexion.php';

$method = $_SERVER['REQUEST_METHOD'];

function bad($code, $msg){ http_response_code($code); echo json_encode(['error'=>$msg], JSON_UNESCAPED_UNICODE); exit; }

if ($method === 'GET') {
  $res = $conn->query("SELECT id, nombre, usuario, correo, rol FROM usuarios ORDER BY id DESC");
  $data = [];
  while($r=$res->fetch_assoc()){ $data[]=$r; }
  echo json_encode($data, JSON_UNESCAPED_UNICODE); exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if ($method === 'POST') {
  $nombre  = trim($input['nombre']  ?? '');
  $usuario = trim($input['usuario'] ?? '');
  $correo  = trim($input['correo']  ?? '');
  $rol     = trim($input['rol']     ?? 'usuario');
  $pass    = $input['password']     ?? '';

  if ($nombre===''||$usuario===''||$correo===''||$pass==='') bad(400,'Datos inválidos');
  $hash = password_hash($pass, PASSWORD_DEFAULT);
  $stmt = $conn->prepare("INSERT INTO usuarios (nombre, usuario, correo, password, rol) VALUES (?,?,?,?,?)");
  $stmt->bind_param("sssss", $nombre, $usuario, $correo, $hash, $rol);
  if(!$stmt->execute()) bad(500,'DB error');
  echo json_encode(['ok'=>true,'id'=>$conn->insert_id]); exit;
}

if ($method === 'PUT') {
  $id = intval($_GET['id'] ?? 0); if ($id<=0) bad(400,'ID inválido');
  $nombre  = trim($input['nombre']  ?? '');
  $usuario = trim($input['usuario'] ?? '');
  $correo  = trim($input['correo']  ?? '');
  $rol     = trim($input['rol']     ?? 'usuario');

  if ($nombre===''||$usuario===''||$correo==='') bad(400,'Datos inválidos');
  $stmt = $conn->prepare("UPDATE usuarios SET nombre=?, usuario=?, correo=?, rol=? WHERE id=?");
  $stmt->bind_param("ssssi", $nombre, $usuario, $correo, $rol, $id);
  if(!$stmt->execute()) bad(500,'DB error');
  echo json_encode(['ok'=>true]); exit;
}

if ($method === 'DELETE') {
  $id = intval($_GET['id'] ?? 0); if ($id<=0) bad(400,'ID inválido');
  $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
  $stmt->bind_param("i",$id);
  if(!$stmt->execute()) bad(500,'DB error');
  echo json_encode(['ok'=>true]); exit;
}

bad(405,'Método no permitido');
