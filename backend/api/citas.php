<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/database.php';

$action = $_GET['action'] ?? '';

try {
    switch ($action) {
        
        case 'list':
            $stmt = $conexion->query("
                SELECT c.*, s.nombre as servicio_nombre 
                FROM citas c 
                LEFT JOIN servicios s ON c.servicio_id = s.id 
                ORDER BY c.fecha_cita DESC
            ");
            $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode([
                'success' => true,
                'message' => 'Citas obtenidas',
                'data' => $citas
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            break;

        case 'get':
            $id = (int)($_GET['id'] ?? 0);
            $stmt = $conexion->prepare("
                SELECT c.*, s.nombre as servicio_nombre 
                FROM citas c 
                LEFT JOIN servicios s ON c.servicio_id = s.id 
                WHERE c.id = :id
            ");
            $stmt->execute([':id' => $id]);
            $cita = $stmt->fetch(PDO::FETCH_ASSOC);
            
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
            $nombre_cliente = trim($_POST['nombre_cliente'] ?? '');
            $email_cliente = trim($_POST['email_cliente'] ?? '');
            $telefono_cliente = trim($_POST['telefono_cliente'] ?? '');
            $nombre_mascota = trim($_POST['nombre_mascota'] ?? '');
            $tipo_mascota = trim($_POST['tipo_mascota'] ?? '');
            $servicio_id = (int)($_POST['servicio_id'] ?? 0);
            $fecha_cita = $_POST['fecha_cita'] ?? '';
            $estado = $_POST['estado'] ?? 'pendiente';
            $notas = trim($_POST['notas'] ?? '');

            if (empty($nombre_cliente) || empty($nombre_mascota) || empty($fecha_cita) || $servicio_id <= 0) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Campos obligatorios faltantes'
                ]);
                break;
            }

            $stmt = $conexion->prepare("
                INSERT INTO citas (nombre_cliente, email_cliente, telefono_cliente, nombre_mascota, tipo_mascota, servicio_id, fecha_cita, estado, notas, usuario_id, fecha_creacion)
                VALUES (:nombre_cliente, :email_cliente, :telefono_cliente, :nombre_mascota, :tipo_mascota, :servicio_id, :fecha_cita, :estado, :notas, :usuario_id, NOW())
            ");
            
            $usuario_id = $_SESSION['usuario_id'] ?? 1;
            $result = $stmt->execute([
                ':nombre_cliente' => $nombre_cliente,
                ':email_cliente' => $email_cliente,
                ':telefono_cliente' => $telefono_cliente,
                ':nombre_mascota' => $nombre_mascota,
                ':tipo_mascota' => $tipo_mascota,
                ':servicio_id' => $servicio_id,
                ':fecha_cita' => $fecha_cita,
                ':estado' => $estado,
                ':notas' => $notas,
                ':usuario_id' => $usuario_id
            ]);

            if ($result) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Cita creada correctamente',
                    'data' => ['message' => 'Cita creada correctamente']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al crear la cita'
                ]);
            }
            break;

        case 'update':
            $id = (int)($_POST['id'] ?? 0);
            $nombre_cliente = trim($_POST['nombre_cliente'] ?? '');
            $email_cliente = trim($_POST['email_cliente'] ?? '');
            $telefono_cliente = trim($_POST['telefono_cliente'] ?? '');
            $nombre_mascota = trim($_POST['nombre_mascota'] ?? '');
            $tipo_mascota = trim($_POST['tipo_mascota'] ?? '');
            $servicio_id = (int)($_POST['servicio_id'] ?? 0);
            $fecha_cita = $_POST['fecha_cita'] ?? '';
            $estado = $_POST['estado'] ?? 'pendiente';
            $notas = trim($_POST['notas'] ?? '');

            if (!$id || empty($nombre_cliente) || empty($nombre_mascota) || $servicio_id <= 0) {
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'message' => 'Datos inválidos'
                ]);
                break;
            }

            $stmt = $conexion->prepare("
                UPDATE citas
                SET nombre_cliente = :nombre_cliente, email_cliente = :email_cliente, telefono_cliente = :telefono_cliente,
                    nombre_mascota = :nombre_mascota, tipo_mascota = :tipo_mascota, servicio_id = :servicio_id,
                    fecha_cita = :fecha_cita, estado = :estado, notas = :notas
                WHERE id = :id
            ");

            $result = $stmt->execute([
                ':nombre_cliente' => $nombre_cliente,
                ':email_cliente' => $email_cliente,
                ':telefono_cliente' => $telefono_cliente,
                ':nombre_mascota' => $nombre_mascota,
                ':tipo_mascota' => $tipo_mascota,
                ':servicio_id' => $servicio_id,
                ':fecha_cita' => $fecha_cita,
                ':estado' => $estado,
                ':notas' => $notas,
                ':id' => $id
            ]);

            if ($result && $stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Cita actualizada correctamente',
                    'data' => ['message' => 'Cita actualizada correctamente']
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al actualizar la cita o no hay cambios'
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

            $stmt = $conexion->prepare("DELETE FROM citas WHERE id = :id");
            $result = $stmt->execute([':id' => $id]);

            if ($result && $stmt->rowCount() > 0) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Cita eliminada correctamente'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Error al eliminar la cita'
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