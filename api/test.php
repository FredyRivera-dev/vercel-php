<?php

// Esto sirve para validar que las extensiones necesarias
// para conectarse a PostgreSQL estén disponibles en Vercel.

header('Content-Type: application/json');
echo json_encode([
    'php' => PHP_VERSION,
    'pdo_pgsql' => extension_loaded('pdo_pgsql'),
    'pgsql' => extension_loaded('pgsql'),
]);