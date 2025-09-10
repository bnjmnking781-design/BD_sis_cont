<?php
$host = "localhost";
$user = "root";      //  se utilizo XAMPP
$pass = "";          // vacío en XAMPP por defecto
$db   = "BD_sis_cont";  //  aquí la BD

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("❌ Error de conexión: " . $conn->connect_error);
}
// Opcional: para acentos y ñ
$conn->set_charset("utf8mb4");

// Para pruebas puedes dejar esto activado:
echo "✅ Conectado a la base de datos $db";

