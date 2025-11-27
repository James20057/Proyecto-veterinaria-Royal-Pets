<?php
require_once 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_cliente = htmlspecialchars($_POST['nombre_cliente'] ?? '');
    $email_cliente = htmlspecialchars($_POST['email_cliente'] ?? '');
    $telefono_cliente = htmlspecialchars($_POST['telefono_cliente'] ?? '');
    $nombre_mascota = htmlspecialchars($_POST['nombre_mascota'] ?? '');
    $tipo_mascota = htmlspecialchars($_POST['tipo_mascota'] ?? '');
    $servicio_id = $_POST['servicio_id'] ?? '';
    $fecha_cita = $_POST['fecha_cita'] ?? '';
    $notas = htmlspecialchars($_POST['notas'] ?? '');

    if (empty($nombre_cliente) || empty($nombre_mascota) || empty($fecha_cita) || empty($servicio_id)) {
        $error = 'Por favor completa los campos obligatorios';
    } else {
        try {
            $stmt = $conexion->prepare('
                INSERT INTO citas (nombre_cliente, email_cliente, telefono_cliente, nombre_mascota, tipo_mascota, servicio_id, fecha_cita, notas, estado)
                VALUES (:nombre_cliente, :email_cliente, :telefono_cliente, :nombre_mascota, :tipo_mascota, :servicio_id, :fecha_cita, :notas, \'pendiente\')
            ');
            
            $stmt->execute([
                ':nombre_cliente' => $nombre_cliente,
                ':email_cliente' => $email_cliente,
                ':telefono_cliente' => $telefono_cliente,
                ':nombre_mascota' => $nombre_mascota,
                ':tipo_mascota' => $tipo_mascota,
                ':servicio_id' => $servicio_id,
                ':fecha_cita' => $fecha_cita,
                ':notas' => $notas
            ]);

            $exito = 'Cita creada exitosamente';
            header('Refresh: 2; URL=dashboard.php');
        } catch (PDOException $e) {
            $error = 'Error al crear la cita: ' . $e->getMessage();
        }
    }
}

// Obtener servicios
try {
    $stmt = $conexion->query('SELECT * FROM servicios ORDER BY nombre');
    $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = 'Error al obtener servicios: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Cita - Veterinaria Royal Pets</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <header>
        <div class="header-content">
            <h1>🐾 Nueva Cita - Royal Pets</h1>
            <a href="logout.php" class="logout-btn">Cerrar sesión</a>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <a href="dashboard.php" class="btn btn-secondary">← Volver</a>

            <?php if (!empty($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (!empty($exito)): ?>
                <div class="success-message"><?php echo $exito; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <h2>Información del Cliente</h2>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre_cliente">Nombre del Cliente *</label>
                        <input type="text" id="nombre_cliente" name="nombre_cliente" required>
                    </div>

                    <div class="form-group">
                        <label for="email_cliente">Email</label>
                        <input type="email" id="email_cliente" name="email_cliente">
                    </div>
                </div>

                <div class="form-group">
                    <label for="telefono_cliente">Teléfono</label>
                    <input type="tel" id="telefono_cliente" name="telefono_cliente">
                </div>

                <h2>Información de la Mascota</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre_mascota">Nombre de la Mascota *</label>
                        <input type="text" id="nombre_mascota" name="nombre_mascota" required>
                    </div>

                    <div class="form-group">
                        <label for="tipo_mascota">Tipo de Mascota</label>
                        <select id="tipo_mascota" name="tipo_mascota">
                            <option value="">Seleccionar...</option>
                            <option value="Perro">Perro</option>
                            <option value="Gato">Gato</option>
                            <option value="Conejo">Conejo</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>

                <h2>Detalles de la Cita</h2>

                <div class="form-row">
                    <div class="form-group">
                        <label for="servicio_id">Servicio *</label>
                        <select id="servicio_id" name="servicio_id" required>
                            <option value="">Seleccionar servicio...</option>
                            <?php foreach ($servicios as $servicio): ?>
                                <option value="<?php echo $servicio['id']; ?>">
                                    <?php echo htmlspecialchars($servicio['nombre']); ?> - $<?php echo number_format($servicio['precio'], 0, ',', '.'); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="fecha_cita">Fecha y Hora *</label>
                        <input type="datetime-local" id="fecha_cita" name="fecha_cita" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="notas">Notas Adicionales</label>
                    <textarea id="notas" name="notas"></textarea>
                </div>

              <div class="centrado">
                <button class="btn btn-primary">Crear nueva Cita</button>
            </div>
            </form>
        </div>
    </div>
</body>
</html>
