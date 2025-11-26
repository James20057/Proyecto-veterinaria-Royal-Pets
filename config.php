<?php
// config.php - Configuración de conexión a PostgreSQL

define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'veterinaria_royal_pets');
define('DB_USER', 'postgres');
define('DB_PASSWORD', 'contra123');

try {
    $conexion = new PDO(
        "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME,
        DB_USER,
        DB_PASSWORD,
        array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
    );
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}

session_start();
?>
