$(document).ready(function() {

    // ==================== FUNCIÓN ACTUALIZAR ESTADÍSTICAS ====================
    function actualizarEstadisticas() {
        $.ajax({
            url: '../../backend/api/estadisticas.php',
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    $('#stat-pendientes').text(resp.data.pendientes);
                    $('#stat-confirmadas').text(resp.data.confirmadas);
                    $('#stat-completadas').text(resp.data.completadas);
                    $('#stat-canceladas').text(resp.data.canceladas);
                }
            },
            error: function() {
                console.error('Error al actualizar estadísticas');
            }
        });
    }

    // ==================== DATATABLE CITAS ====================
    var tablaCitas = $('#tablaCitas').DataTable({
        ajax: {
            url: '../../backend/api/citas.php?action=list',
            dataSrc: 'data'
        },
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
        },
        columns: [
            { data: 'id' },
            { data: 'nombre_cliente' },
            { data: 'nombre_mascota' },
            { data: 'servicio_nombre' },
            { 
                data: 'fecha_cita',
                render: function(data) {
                    return new Date(data).toLocaleString('es-ES');
                }
            },
            {
                data: 'estado',
                render: function(data) {
                    let style = '';
                    let icono = '';
                    let texto = '';
                    
                    switch(data) {
                        case 'pendiente':
                            style = 'background: #fef3c7; color: #92400e; border: 2px solid #f59e0b;';
                            icono = '<i class="fas fa-clock"></i>';
                            texto = 'Pendiente';
                            break;
                        case 'confirmada':
                            style = 'background: #d1fae5; color: #065f46; border: 2px solid #10b981;';
                            icono = '<i class="fas fa-check-circle"></i>';
                            texto = 'Confirmada';
                            break;
                        case 'completada':
                            style = 'background: #dbeafe; color: #1e3a8a; border: 2px solid #3b82f6;';
                            icono = '<i class="fas fa-check-double"></i>';
                            texto = 'Completada';
                            break;
                        case 'cancelada':
                            style = 'background: #fee2e2; color: #7f1d1d; border: 2px solid #ef4444;';
                            icono = '<i class="fas fa-times-circle"></i>';
                            texto = 'Cancelada';
                            break;
                        default:
                            style = 'background: #e5e7eb; color: #374151; border: 2px solid #9ca3af;';
                            texto = data;
                    }
                    
                    return `<span style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-weight: 700; font-size: 12px; text-transform: uppercase; white-space: nowrap; ${style}">${icono} ${texto}</span>`;
                }
            },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="action-buttons">
                            <button class="btn btn-editar-cita" data-id="${row.id}" title="Editar cita">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn btn-eliminar-cita" data-id="${row.id}" title="Eliminar cita">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });

    // ==================== DATATABLE SERVICIOS ====================
    var tablaServicios = $('#tablaServicios').DataTable({
        ajax: {
            url: '../../backend/api/servicios.php?action=list',
            dataSrc: 'data'
        },
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json'
        },
        columns: [
            { data: 'id' },
            { data: 'nombre' },
            { 
                data: 'descripcion',
                render: function(data) {
                    return data ? data.substring(0, 50) + '...' : 'N/A';
                }
            },
            { 
                data: 'precio',
                render: function(data) {
                    return '$' + parseFloat(data).toLocaleString('es-CO');
                }
            },
            { data: 'duracion_minutos' },
            {
                data: null,
                orderable: false,
                render: function(data, type, row) {
                    return `
                        <div class="action-buttons">
                            <button class="btn btn-editar-servicio" data-id="${row.id}" title="Editar servicio">
                                <i class="fas fa-edit"></i> Editar
                            </button>
                            <button class="btn btn-eliminar-servicio" data-id="${row.id}" title="Eliminar servicio">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });

    // ==================== MODAL CITA ====================
    $('#btnNuevaCita').on('click', function() {
        $('#idCita').val('');
        $('#formCita')[0].reset();
        $('#tituloCita').text('Nueva Cita');
        $('#modalCita').modal('show');
    });

    $('#formCita').on('submit', function(e) {
        e.preventDefault();
        var id = $('#idCita').val();
        var action = id ? 'update' : 'create';

        $.ajax({
            url: '../../backend/api/citas.php?action=' + action,
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(resp) {
                console.log('Respuesta:', resp);
                if (resp.success) {
                    $('#modalCita').modal('hide');
                    tablaCitas.ajax.reload(null, false);
                    actualizarEstadisticas();
                    mostrarMensaje('success', resp.message || resp.data.message || 'Operación exitosa');
                    $('#formCita')[0].reset();
                } else {
                    mostrarMensaje('error', resp.message || 'Ocurrió un error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error, xhr.responseText);
                mostrarMensaje('error', 'Error en la petición AJAX: ' + error);
            }
        });
    });

    // EDITAR CITA
    $('#tablaCitas').on('click', '.btn-editar-cita', function() {
        var id = $(this).data('id');
        console.log('Editando cita con ID:', id);

        $.ajax({
            url: '../../backend/api/citas.php?action=get&id=' + id,
            dataType: 'json',
            success: function(resp) {
                console.log('Respuesta del servidor:', resp);
                if (resp.success) {
                    var c = resp.cita;  
                    $('#idCita').val(c.id);
                    $('#nombreCliente').val(c.nombre_cliente);
                    $('#emailCliente').val(c.email_cliente);
                    $('#telefonoCliente').val(c.telefono_cliente);
                    $('#nombreMascota').val(c.nombre_mascota);
                    $('#tipoMascota').val(c.tipo_mascota);
                    $('#servicioId').val(c.servicio_id);
                    $('#fechaCita').val(c.fecha_cita.replace(' ', 'T').substring(0, 16));
                    $('#estadoCita').val(c.estado);
                    $('#notasCita').val(c.notas);
                    $('#tituloCita').text('Editar Cita');
                    $('#modalCita').modal('show');
                    console.log('Modal abierto para editar cita');
                } else {
                    mostrarMensaje('error', resp.message || 'No se encontró la cita');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', error);
                console.error('Respuesta:', xhr.responseText);
                mostrarMensaje('error', 'Error en la petición AJAX: ' + error);
            }
        });
    });

    // ELIMINAR CITA
    $('#tablaCitas').on('click', '.btn-eliminar-cita', function() {
        if (!confirm('¿Seguro que desea eliminar esta cita?')) return;

        var id = $(this).data('id');

        $.ajax({
            url: '../../backend/api/citas.php?action=delete',
            method: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    tablaCitas.ajax.reload(null, false);
                    actualizarEstadisticas();
                    mostrarMensaje('success', 'Cita eliminada correctamente');
                } else {
                    mostrarMensaje('error', resp.message || 'No se pudo eliminar');
                }
            },
            error: function() {
                mostrarMensaje('error', 'Error en la petición AJAX');
            }
        });
    });

    // ==================== MODAL SERVICIO ====================
    $('#btnNuevoServicio').on('click', function() {
        $('#idServicio').val('');
        $('#formServicio')[0].reset();
        $('#tituloServicio').text('Nuevo Servicio');
        $('#modalServicio').modal('show');
    });

    $('#formServicio').on('submit', function(e) {
        e.preventDefault();
        var id = $('#idServicio').val();
        var action = id ? 'update' : 'create';

        $.ajax({
            url: '../../backend/api/servicios.php?action=' + action,
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(resp) {
                console.log('Respuesta:', resp);
                if (resp.success) {
                    $('#modalServicio').modal('hide');
                    tablaServicios.ajax.reload(null, false);
                    tablaCitas.ajax.reload(null, false);
                    mostrarMensaje('success', resp.message || resp.data.message || 'Operación exitosa');
                    $('#formServicio')[0].reset();
                } else {
                    mostrarMensaje('error', resp.message || 'Ocurrió un error');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error, xhr.responseText);
                mostrarMensaje('error', 'Error en la petición AJAX: ' + error);
            }
        });
    });

    // EDITAR SERVICIO
    $('#tablaServicios').on('click', '.btn-editar-servicio', function() {
        var id = $(this).data('id');
        console.log('Editando servicio con ID:', id);

        $.ajax({
            url: '../../backend/api/servicios.php?action=get&id=' + id,
            dataType: 'json',
            success: function(resp) {
                console.log('Respuesta del servidor:', resp);
                if (resp.success) {
                    var s = resp.servicio;  
                    $('#idServicio').val(s.id);
                    $('#nombreServicio').val(s.nombre);
                    $('#descripcionServicio').val(s.descripcion);
                    $('#precioServicio').val(s.precio);
                    $('#duracionServicio').val(s.duracion_minutos);
                    $('#tituloServicio').text('Editar Servicio');
                    $('#modalServicio').modal('show');
                    console.log('Modal abierto para editar servicio');
                } else {
                    mostrarMensaje('error', resp.message || 'No se encontró el servicio');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', error);
                console.error('Respuesta:', xhr.responseText);
                mostrarMensaje('error', 'Error en la petición AJAX: ' + error);
            }
        });
    });

    // ELIMINAR SERVICIO
    $('#tablaServicios').on('click', '.btn-eliminar-servicio', function() {
        if (!confirm('¿Seguro que desea eliminar este servicio?')) return;

        var id = $(this).data('id');

        $.ajax({
            url: '../../backend/api/servicios.php?action=delete',
            method: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    tablaServicios.ajax.reload(null, false);
                    mostrarMensaje('success', 'Servicio eliminado correctamente');
                } else {
                    mostrarMensaje('error', resp.message || 'No se pudo eliminar');
                }
            },
            error: function() {
                mostrarMensaje('error', 'Error en la petición AJAX');
            }
        });
    });

    // ==================== CARGAR SERVICIOS EN SELECT ====================
    function cargarServicios() {
        $.ajax({
            url: '../../backend/api/servicios.php?action=list',
            dataType: 'json',
            success: function(resp) {
                if (resp.success) {
                    var select = $('#servicioId');
                    select.empty();
                    select.append('<option value="">Seleccionar servicio...</option>');
                    $.each(resp.data, function(i, servicio) {
                        select.append(`<option value="${servicio.id}">${servicio.nombre} - $${parseFloat(servicio.precio).toLocaleString('es-CO')}</option>`);
                    });
                }
            }
        });
    }

    cargarServicios();

    // ==================== FUNCIÓN MOSTRAR MENSAJES ====================
    function mostrarMensaje(tipo, mensaje) {
        var clase = tipo === 'success' ? 'alert-success' : 'alert-danger';
        var html = `<div class="alert ${clase} alert-dismissible fade show" role="alert">
                        ${mensaje}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>`;
        $('#mensajes').html(html);
        setTimeout(function() {
            $('#mensajes').html('');
        }, 5000);
    }

    // ==================== TAB SWITCHING ====================
    $('.tab-btn').on('click', function() {
        $('.tab-btn').removeClass('active');
        $('.section').removeClass('active');
        $(this).addClass('active');
        $('#' + $(this).data('tab')).addClass('active');
        
        // Redimensionar DataTables cuando cambia de tab
        if ($(this).data('tab') === 'citas') {
            tablaCitas.columns.adjust().draw();
        } else if ($(this).data('tab') === 'servicios') {
            tablaServicios.columns.adjust().draw();
        }
    });

});
