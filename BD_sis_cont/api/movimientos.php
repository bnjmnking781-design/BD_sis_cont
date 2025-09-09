<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../conexion.php';

$method = $_SERVER['REQUEST_METHOD'];
function bad($c,$m){ http_response_code($c); echo json_encode(['error'=>$m], JSON_UNESCAPED_UNICODE); exit; }

if ($method==='GET') {
  $res = $conn->query("SELECT id, fecha, descripcion, tipo, monto FROM movimientos ORDER BY fecha DESC, id DESC");
  $data=[]; while($r=$res->fetch_assoc()) $data[]=$r;
  echo json_encode($data, JSON_UNESCAPED_UNICODE); exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if ($method==='POST') {
  $f = $input['fecha'] ?? ''; $d = trim($input['descripcion'] ?? '');
  $t = $input['tipo'] ?? '';  $m = $input['monto'] ?? null;
  if ($f===''||$d===''||!in_array($t,['Ingreso','Egreso'])||$m===null) bad(400,'Datos inválidos');
  $stmt=$conn->prepare("INSERT INTO movimientos (fecha, descripcion, tipo, monto) VALUES (?,?,?,?)");
  $stmt->bind_param("sssd",$f,$d,$t,$m);
  if(!$stmt->execute()) bad(500,'DB error');
  echo json_encode(['ok'=>true,'id'=>$conn->insert_id]); exit;
}

if ($method==='PUT') {
  $id=intval($_GET['id'] ?? 0); if($id<=0) bad(400,'ID inválido');
  $f = $input['fecha'] ?? ''; $d = trim($input['descripcion'] ?? '');
  $t = $input['tipo'] ?? '';  $m = $input['monto'] ?? null;
  if ($f===''||$d===''||!in_array($t,['Ingreso','Egreso'])||$m===null) bad(400,'Datos inválidos');
  $stmt=$conn->prepare("UPDATE movimientos SET fecha=?, descripcion=?, tipo=?, monto=? WHERE id=?");
  $stmt->bind_param("sssdi",$f,$d,$t,$m,$id);
  if(!$stmt->execute()) bad(500,'DB error');
  echo json_encode(['ok'=>true]); exit;
}

if ($method==='DELETE') {
  $id=intval($_GET['id'] ?? 0); if($id<=0) bad(400,'ID inválido');
  $stmt=$conn->prepare("DELETE FROM movimientos WHERE id=?");
  $stmt->bind_param("i",$id);
  if(!$stmt->execute()) bad(500,'DB error');
  echo json_encode(['ok'=>true]); exit;
}

bad(405,'Método no permitido');
