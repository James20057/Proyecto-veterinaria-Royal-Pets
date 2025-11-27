<?php
require_once 'config.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

try {
    $stmt = $conexion->query('SELECT COUNT(*) as total FROM citas WHERE estado = \'pendiente\'');
    $citas_pendientes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $conexion->query('SELECT COUNT(*) as total FROM citas WHERE estado = \'confirmada\'');
    $citas_confirmadas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $conexion->query('SELECT COUNT(*) as total FROM servicios');
    $total_servicios = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
} catch (PDOException $e) {
    die('Error: ' . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Veterinaria Royal Pets</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
    <header>
        <div class="header-content">
            <h1>🐾 Dashboard - Royal Pets</h1>
            <div class="user-info">
                <span>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></span>
                <a href="logout.php" class="logout-btn">Cerrar sesión</a>
            </div>
        </div>
    </header>

    <div class="container">
        <div class="stats">
            <div class="stat-card pendiente">
                <h3>Citas Pendientes</h3>
                <div class="number"><?php echo $citas_pendientes; ?></div>
            </div>
            <div class="stat-card confirmada">
                <h3>Citas Confirmadas</h3>
                <div class="number"><?php echo $citas_confirmadas; ?></div>
            </div>
            <div class="stat-card servicios">
                <h3>Servicios Disponibles</h3>
                <div class="number"><?php echo $total_servicios; ?></div>
            </div>
        </div>

        <div class="nav-tabs">
            <button class="tab-btn active" data-tab="citas">Gestionar Citas</button>
            <button class="tab-btn" data-tab="servicios">Gestionar Servicios</button>
        </div>

        <div id="citas" class="section active">
            <h2>Gestionar Citas</h2>
            <a href="nueva-cita.php" class="btn-nuevo">+ Nueva Cita</a>
            <table>
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Mascota</th>
                        <th>Servicio</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $conexion->prepare('
                            SELECT c.*, s.nombre as servicio_nombre 
                            FROM citas c 
                            LEFT JOIN servicios s ON c.servicio_id = s.id 
                            ORDER BY c.fecha_cita DESC 
                            LIMIT 10
                        ');
                        $stmt->execute();
                        $citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($citas) {
                            foreach ($citas as $cita) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($cita['nombre_cliente']) . '</td>';
                                echo '<td>' . htmlspecialchars($cita['nombre_mascota']) . '</td>';
                                echo '<td>' . htmlspecialchars($cita['servicio_nombre'] ?? 'N/A') . '</td>';
                                echo '<td>' . date('d/m/Y H:i', strtotime($cita['fecha_cita'])) . '</td>';
                                echo '<td><span class="badge ' . $cita['estado'] . '">' . ucfirst($cita['estado']) . '</span></td>';
                                echo '<td class="actions">';
                                echo '<a href="editar-cita.php?id=' . $cita['id'] . '" class="btn btn-warning">Editar</a>';
                                echo '<a href="eliminar-cita.php?id=' . $cita['id'] . '" class="btn btn-danger" onclick="return confirm(\'¿Estás seguro?\')">Eliminar</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="6" class="no-data">No hay citas registradas</td></tr>';
                        }
                    } catch (PDOException $e) {
                        echo '<tr><td colspan="6" class="no-data">Error: ' . $e->getMessage() . '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div id="servicios" class="section">
            <h2>Gestionar Servicios</h2>
           <a href="nuevo-servicio.php" class="btn-nuevo">+ Nuevo Servicio</a>
            <table>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Duración (min)</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    try {
                        $stmt = $conexion->query('SELECT * FROM servicios ORDER BY nombre');
                        $servicios = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if ($servicios) {
                            foreach ($servicios as $servicio) {
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($servicio['nombre']) . '</td>';
                                echo '<td>' . htmlspecialchars(substr($servicio['descripcion'], 0, 50)) . '...</td>';
                                echo '<td>$' . number_format($servicio['precio'], 0, ',', '.') . '</td>';
                                echo '<td>' . $servicio['duracion_minutos'] . '</td>';
                                echo '<td class="actions">';
                                echo '<a href="editar-servicio.php?id=' . $servicio['id'] . '" class="btn btn-warning">Editar</a>';
                                echo '<a href="eliminar-servicio.php?id=' . $servicio['id'] . '" class="btn btn-danger" onclick="return confirm(\'¿Estás seguro?\')">Eliminar</a>';
                                echo '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" class="no-data">No hay servicios registrados</td></tr>';
                        }
                    } catch (PDOException $e) {
                        echo '<tr><td colspan="5" class="no-data">Error: ' . $e->getMessage() . '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
