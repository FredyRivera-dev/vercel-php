<?php 

// Protección: si alguien visita este archivo directamente (p. ej. /api/conexion.php),
// se responde 404 y no se ejecuta nada. Solo funciona incluido desde otro archivo
// que primero haya definido APP_ENTRY (ver index.php).
if (!defined('APP_ENTRY')) {
    http_response_code(404);
    exit;
}

$url_db = "localhost";
$user = "root";
$password = "";
$port = 3306;
$db_name = "default";

$conexion = new mysqli($url_db, $user, $password, $db_name, $port);

if($conexion -> connect_error){
    die("Error de conexion: ". $conexion -> connect_error);
}

$conexion -> set_charset("utf8mb4");
