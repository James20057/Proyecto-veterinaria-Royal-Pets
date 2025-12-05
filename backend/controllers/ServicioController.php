<?php
// backend/controllers/ServicioController.php

require_once __DIR__ . '/../models/Servicio.php';

class ServicioController {

    public static function listar() {
        return Servicio::all();
    }

    public static function obtener($id) {
        return Servicio::find($id);
    }

    public static function crear($data) {
        if (trim($data['nombre'] ?? '') === '') {
            return [false, 'El nombre es obligatorio'];
        }
        if (empty($data['precio']) || $data['precio'] <= 0) {
            return [false, 'El precio es obligatorio y debe ser mayor a 0'];
        }
        Servicio::create($data);
        return [true, 'Servicio creado correctamente'];
    }

    public static function actualizar($data) {
        if (empty($data['id']) || trim($data['nombre'] ?? '') === '') {
            return [false, 'Datos inválidos'];
        }
        if (empty($data['precio']) || $data['precio'] <= 0) {
            return [false, 'El precio debe ser mayor a 0'];
        }
        Servicio::update($data);
        return [true, 'Servicio actualizado correctamente'];
    }

    public static function eliminar($id) {
        if (!$id) {
            return [false, 'ID inválido'];
        }
        Servicio::delete($id);
        return [true, 'Servicio eliminado correctamente'];
    }
}
?>
