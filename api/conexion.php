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

/*
 * Conexión a PostgreSQL
 *
 * Requisito: extensión pgsql activa. Verificar con: php -m | grep -i pgsql
 * Para usarla: comentar el bloque de MySQL de arriba y descomentar este.
 *
 * Diferencias con mysqli:
 * - No existe clase. Se usa la función pg_connect(), que devuelve un recurso
 *   de conexión (o false si falla).
 * - Puerto por defecto de Postgres: 5432. Usuario por defecto: postgres.
 * - Las consultas no son métodos del objeto. Se usan funciones pg_* y la
 *   conexión se pasa como primer argumento.
 * - Placeholders $1, $2 en vez de ?. Siempre pg_query_params, nunca concatenar.
 *
 * $url_db = "localhost";
 * $user = "postgres";
 * $password = "";
 * $port = 5432;
 * $db_name = "default";
 *
 * // Los valores con espacios, comillas o backslash deben ir entre comillas
 * // simples y escapados, si no la cadena de conexión se rompe.
 * $cadena = sprintf(
 *     "host='%s' port='%d' dbname='%s' user='%s' password='%s'",
 *     addcslashes($url_db, "'\\"),
 *     $port,
 *     addcslashes($db_name, "'\\"),
 *     addcslashes($user, "'\\"),
 *     addcslashes($password, "'\\")
 * );
 *
 * // Para bases remotas (Neon, Supabase, etc.) agregar al final de la cadena:
 * // sslmode='require'
 *
 * // El @ evita que PHP imprima el warning con host y usuario en pantalla.
 * $conexion = @pg_connect($cadena);
 *
 * if (!$conexion) {
 *     error_log("Fallo la conexion a PostgreSQL");
 *     die("Error de conexion");
 * }
 *
 * pg_set_client_encoding($conexion, "UTF8");
 *
 * // Ejemplo de consulta con parámetros:
 * // $resultado = pg_query_params($conexion, 'SELECT id, nombre FROM usuarios WHERE email = $1', [$email]);
 * // $usuario = pg_fetch_assoc($resultado);
 */