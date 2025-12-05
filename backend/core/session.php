<?php
// backend/core/session.php

session_start();

function require_login_page() {
    if (!isset($_SESSION['usuario_id'])) {
        // Ruta absoluta desde la raíz del servidor
        header('Location: /prueba-copia/frontend/pages/login.php');
        exit();
    }
}

function require_login_api() {
    if (!isset($_SESSION['usuario_id'])) {
        header('Content-Type: application/json');
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'message' => 'No autorizado'
        ]);
        exit;
    }
}
?>
