<?php
require_once 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$exito = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = htmlspecialchars($_POST['nombre'] ?? '');
    $descripcion = htmlspecialchars($_POST['descripcion'] ?? '');
    $precio = $_POST['precio'] ?? '';
    $duracion_minutos = $_POST['duracion_minutos'] ?? '';

    if (empty($nombre) || empty($precio)) {
        $error = 'Por favor completa los campos obligatorios';
    } else {
        try {
            $stmt = $conexion->prepare('
                INSERT INTO servicios (nombre, descripcion, precio, duracion_minutos)
                VALUES (:nombre, :descripcion, :precio, :duracion_minutos)
            ');
            
            $stmt->execute([
                ':nombre' => $nombre,
                ':descripcion' => $descripcion,
                ':precio' => $precio,
                ':duracion_minutos' => $duracion_minutos
            ]);

            $exito = 'Servicio creado exitosamente';
            header('Refresh: 2; URL=dashboard.php');
        } catch (PDOException $e) {
            $error = 'Error al crear servicio: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Servicio - Veterinaria Royal Pets</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <header>
        <div class="header-content">
            <h1>🐾 Nuevo Servicio - Royal Pets</h1>
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
                <div class="form-group">
                    <label for="nombre">Nombre del Servicio *</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div class="form-group">
                    <label for="descripcion">Descripción</label>
                    <textarea id="descripcion" name="descripcion"></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="precio">Precio *</label>
                        <input type="number" id="precio" name="precio" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="duracion_minutos">Duración (minutos)</label>
                        <input type="number" id="duracion_minutos" name="duracion_minutos">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Crear Servicio</button>
            </form>
        </div>
    </div>
</body>
</html>
