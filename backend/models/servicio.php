<?php
// backend/models/Servicio.php

class Servicio {

    public static function all() {
        global $conexion;  
        $stmt = $conexion->query("SELECT * FROM servicios ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        global $conexion;  
        $stmt = $conexion->prepare("SELECT * FROM servicios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        global $conexion;  
        $stmt = $conexion->prepare("
            INSERT INTO servicios (nombre, descripcion, precio, duracion_minutos)
            VALUES (:nombre, :descripcion, :precio, :duracion_minutos)
        ");
        return $stmt->execute([
            ':nombre' => $data['nombre'] ?? null,
            ':descripcion' => $data['descripcion'] ?? null,
            ':precio' => $data['precio'] ?? null,
            ':duracion_minutos' => $data['duracion_minutos'] ?? null,
        ]);
    }

    public static function update($data) {
        global $conexion;  
        $stmt = $conexion->prepare("
            UPDATE servicios
            SET nombre = :nombre, descripcion = :descripcion, precio = :precio, duracion_minutos = :duracion_minutos
            WHERE id = :id
        ");
        return $stmt->execute([
            ':nombre' => $data['nombre'] ?? null,
            ':descripcion' => $data['descripcion'] ?? null,
            ':precio' => $data['precio'] ?? null,
            ':duracion_minutos' => $data['duracion_minutos'] ?? null,
            ':id' => $data['id'],
        ]);
    }

    public static function delete($id) {
        global $conexion;  
        $stmt = $conexion->prepare("DELETE FROM servicios WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>
