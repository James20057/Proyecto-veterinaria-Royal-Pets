<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        
        case 'list':
            $stmt = $conexion->query("SELECT * FROM servicios ORDER BY id DESC");
            $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'success' => true,
                'message' => 'Servicios obtenidos',
                'data' => $servicios
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;

        case 'get':
            $id = (int)($_GET['id'] ?? 0);
            $stmt = $conexion->prepare("SELECT * FROM servicios WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $servicio = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($servicio) {
                echo json_encode([
                    'success' => true,
                    'servicio' => $servicio
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'message' => 'Servicio no encontrado'
                ]);
            }
            break;

        case 'create':
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $precio = (float)($_POST['precio'] ?? 0);
            $duracion_minutos = (int)($_POST['duracion_minutos'] ?? 0);

            if (empty($nombre) || $precio <= 0) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Nombre y precio válido son obligatorios'
                ]);
                break;
            }

            $stmt = $conexion->prepare("
                INSERT INTO servicios (nombre, descripcion, precio, duracion_minutos, fecha_creacion)
                VALUES (:nombre, :descripcion, :precio, :duracion_minutos, NOW())
            ");
            
            $result = $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':duracion_minutos' => $duracion_minutos
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Servicio creado correctamente',
                    'data' => ['message' => 'Servicio creado correctamente']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al crear el servicio'
                ]);
            }
            break;

        case 'update':
            $id = (int)($_POST['id'] ?? 0);
            $nombre = trim($_POST['nombre'] ?? '');
            $descripcion = trim($_POST['descripcion'] ?? '');
            $precio = (float)($_POST['precio'] ?? 0);
            $duracion_minutos = (int)($_POST['duracion_minutos'] ?? 0);

            if (!$id || empty($nombre) || $precio <= 0) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID, nombre y precio válido son obligatorios'
                ]);
                break;
            }

            $stmt = $conexion->prepare("
                UPDATE servicios
                SET nombre = :nombre, descripcion = :descripcion, precio = :precio, duracion_minutos = :duracion_minutos
                WHERE id = :id
            ");

            $result = $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':duracion_minutos' => $duracion_minutos,
                ':id' => $id
            ]);

            if ($result && $stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Servicio actualizado correctamente',
                    'data' => ['message' => 'Servicio actualizado correctamente']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al actualizar el servicio o no hay cambios'
                ]);
            }
            break;

        case 'delete':
            $id = (int)($_POST['id'] ?? 0);
            
            if (!$id) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'ID inválido'
                ]);
                break;
            }

            $stmt = $conexion->prepare("DELETE FROM servicios WHERE id = :id");
            $result = $stmt->execute([':id' => $id]);

            if ($result && $stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Servicio eliminado correctamente'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al eliminar el servicio'
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
<?php