<?php
// backend/controllers/CitaController.php

require_once __DIR__ . '/../models/Cita.php';

class CitaController {

    public static function listar() {
        return Cita::all();
    }

    public static function obtener($id) {
        return Cita::find($id);
    }

    public static function crear($data) {
        if (trim($data['nombre_cliente'] ?? '') === '') {
            return [false, 'El nombre del cliente es obligatorio'];
        }
        if (trim($data['nombre_mascota'] ?? '') === '') {
            return [false, 'El nombre de la mascota es obligatorio'];
        }
        if (empty($data['fecha_cita'])) {
            return [false, 'La fecha de la cita es obligatoria'];
        }
        if (empty($data['servicio_id'])) {
            return [false, 'Debe seleccionar un servicio'];
        }
        Cita::create($data);
        return [true, 'Cita creada correctamente'];
    }

    public static function actualizar($data) {
        if (empty($data['id'])) {
            return [false, 'ID de cita no proporcionado'];
        }
        if (trim($data['nombre_cliente'] ?? '') === '') {
            return [false, 'El nombre del cliente es obligatorio'];
        }
        if (trim($data['nombre_mascota'] ?? '') === '') {
            return [false, 'El nombre de la mascota es obligatorio'];
        }
        Cita::update($data);
        return [true, 'Cita actualizada correctamente'];
    }

    public static function eliminar($id) {
        if (!$id) {
            return [false, 'ID inválido'];
        }
        Cita::delete($id);
        return [true, 'Cita eliminada correctamente'];
    }
}
?>
