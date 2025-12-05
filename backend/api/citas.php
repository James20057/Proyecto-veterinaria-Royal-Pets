<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../core/session.php';
require_once __DIR__ . '/../controllers/CitaController.php';

require_login_api();

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        
        case 'list':
            $citas = CitaController::listar();
            echo json_encode([
                'success' => true,
                'message' => 'Citas obtenidas',
                'data' => $citas
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;

        case 'get':
            $id = (int)($_GET['id'] ?? 0);
            $cita = CitaController::obtener($id);
            
            if ($cita) {
                echo json_encode([
                    'success' => true,
                    'cita' => $cita
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Cita no encontrada'
                ]);
            }
            break;

        case 'create':
            [$success, $message] = CitaController::crear($_POST);
            
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => $message,
                    'data' => ['message' => $message]
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $message
                ]);
            }
            break;

        case 'update':
            [$success, $message] = CitaController::actualizar($_POST);
            
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => $message,
                    'data' => ['message' => $message]
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $message
                ]);
            }
            break;

        case 'delete':
            $id = (int)($_POST['id'] ?? 0);
            [$success, $message] = CitaController::eliminar($id);
            
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => $message
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => $message
                ]);
            }
            break;

        default:
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Acción no válida'
            ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}
?>
