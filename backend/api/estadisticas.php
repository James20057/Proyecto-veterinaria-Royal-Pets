<?php
require_once __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

try {
    $stmt = $conexion->query('SELECT COUNT(*) as total FROM citas WHERE estado = \'pendiente\'');
    $pendientes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $conexion->query('SELECT COUNT(*) as total FROM citas WHERE estado = \'confirmada\'');
    $confirmadas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $conexion->query('SELECT COUNT(*) as total FROM citas WHERE estado = \'completada\'');
    $completadas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $conexion->query('SELECT COUNT(*) as total FROM citas WHERE estado = \'cancelada\'');
    $canceladas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    echo json_encode([
        'success' => true,
        'data' => [
            'pendientes' => $pendientes,
            'confirmadas' => $confirmadas,
            'completadas' => $completadas,
            'canceladas' => $canceladas
        ]
    ]);
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
