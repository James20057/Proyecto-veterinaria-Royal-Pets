<?php
require_once 'config.php';

try {
    echo "✅ Conexión exitosa a PostgreSQL!";
} catch (PDOException $e) {
    echo "❌ Error de conexión: " . $e->getMessage();
}
?>
