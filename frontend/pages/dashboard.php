<?php
require_once __DIR__ . '/../../backend/core/session.php';
require_once __DIR__ . '/../../backend/config/database.php';

require_login_page();

try {
    // ← YA NO pongas require_once aquí, ya lo tienes arriba
    
    // Consulta para citas pendientes
    $stmt = $conexion->query('SELECT COUNT(*) as total FROM citas WHERE estado = \'pendiente\'');
    $citas_pendientes = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Consulta para citas confirmadas
    $stmt = $conexion->query('SELECT COUNT(*) as total FROM citas WHERE estado = \'confirmada\'');
    $citas_confirmadas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Consulta para citas completadas
    $stmt = $conexion->query('SELECT COUNT(*) as total FROM citas WHERE estado = \'completada\'');
    $citas_completadas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Consulta para citas canceladas
    $stmt = $conexion->query('SELECT COUNT(*) as total FROM citas WHERE estado = \'cancelada\'');
    $citas_canceladas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Consulta para servicios
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
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom CSS -->
     <!-- En el <head>, agrega Font Awesome para los iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/dashboard-complete.css">

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
   <!-- ESTADÍSTICAS -->
    <div class="stats">
        <div class="stat-card pendiente">
            <h3>Citas Pendientes</h3>
            <div class="number" id="stat-pendientes"><?php echo $citas_pendientes; ?></div>
        </div>
        <div class="stat-card confirmada">
            <h3>Citas Confirmadas</h3>
            <div class="number" id="stat-confirmadas"><?php echo $citas_confirmadas; ?></div>
        </div>
        <div class="stat-card completada">
            <h3>Citas Completadas</h3>
            <div class="number" id="stat-completadas"><?php echo $citas_completadas; ?></div>
        </div>
        <div class="stat-card cancelada">
            <h3>Citas Canceladas</h3>
            <div class="number" id="stat-canceladas"><?php echo $citas_canceladas; ?></div>
        </div>
    </div>


    <!-- MENSAJES -->
    <div id="mensajes"></div>

    <!-- TABS DE NAVEGACIÓN -->
    <div class="nav-tabs">
        <button class="tab-btn active" data-tab="citas">Gestionar Citas</button>
        <button class="tab-btn" data-tab="servicios">Gestionar Servicios</button>
    </div>

    <!-- ==================== SECCIÓN CITAS ==================== -->
    <div id="citas" class="section active">
        <h2>Gestionar Citas</h2>
        <button class="btn btn-success" id="btnNuevaCita">+ Nueva Cita</button>
        
        <table id="tablaCitas" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Mascota</th>
                    <th>Servicio</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>

    <!-- ==================== SECCIÓN SERVICIOS ==================== -->
    <div id="servicios" class="section">
        <h2>Gestionar Servicios</h2>
        <button class="btn btn-success" id="btnNuevoServicio">+ Nuevo Servicio</button>
        
        <table id="tablaServicios" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Descripción</th>
                    <th>Precio</th>
                    <th>Duración (min)</th>
                    <th>Acciones</th>
                </tr>
            </thead>
        </table>
    </div>

</div>

<!-- ==================== MODAL CITA ==================== -->
<div class="modal fade" id="modalCita" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formCita" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloCita">Nueva Cita</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idCita" name="id">

                <h6>Información del Cliente</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre del Cliente *</label>
                        <input type="text" class="form-control" id="nombreCliente" name="nombre_cliente" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="emailCliente" name="email_cliente">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="telefonoCliente" name="telefono_cliente">
                </div>

                <h6>Información de la Mascota</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Nombre de la Mascota *</label>
                        <input type="text" class="form-control" id="nombreMascota" name="nombre_mascota" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Mascota</label>
                        <select class="form-control" id="tipoMascota" name="tipo_mascota">
                            <option value="">Seleccionar...</option>
                            <option value="Perro">Perro</option>
                            <option value="Gato">Gato</option>
                            <option value="Conejo">Conejo</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>
                </div>

                <h6>Detalles de la Cita</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Servicio *</label>
                        <select class="form-control" id="servicioId" name="servicio_id" required>
                            <option value="">Seleccionar servicio...</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Fecha y Hora *</label>
                        <input type="datetime-local" class="form-control" id="fechaCita" name="fecha_cita" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Estado</label>
                    <select class="form-control" id="estadoCita" name="estado" >
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmada">Confirmada</option>
                        <option value="completada">Completada</option>
                        <option value="cancelada">Cancelada</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Notas Adicionales</label>
                    <textarea class="form-control" id="notasCita" name="notas" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Cita</button>
            </div>
        </form>
    </div>
</div>

<!-- ==================== MODAL SERVICIO ==================== -->
<div class="modal fade" id="modalServicio" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formServicio" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloServicio">Nuevo Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="idServicio" name="id">

                <div class="mb-3">
                    <label class="form-label">Nombre del Servicio *</label>
                    <input type="text" class="form-control" id="nombreServicio" name="nombre" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea class="form-control" id="descripcionServicio" name="descripcion" rows="3"></textarea>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Precio *</label>
                        <input type="number" class="form-control" id="precioServicio" name="precio" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Duración (minutos)</label>
                        <input type="number" class="form-control" id="duracionServicio" name="duracion_minutos">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Servicio</button>
            </div>
        </form>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<!-- Custom Dashboard JS -->
<script src="../js/dashboard.js"></script>

</body>
</html>
