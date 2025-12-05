<?php
// backend/models/Cita.php

class Cita {

    public static function all() {
        global $conexion;  // ✅ CAMBIO AQUÍ
        $stmt = $conexion->query("
            SELECT c.*, s.nombre as servicio_nombre 
            FROM citas c 
            LEFT JOIN servicios s ON c.servicio_id = s.id 
            ORDER BY c.fecha_cita DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        global $conexion; 
        $stmt = $conexion->prepare("
            SELECT c.*, s.nombre as servicio_nombre 
            FROM citas c 
            LEFT JOIN servicios s ON c.servicio_id = s.id 
            WHERE c.id = :id
        ");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        global $conexion;  
        $stmt = $conexion->prepare("
            INSERT INTO citas (nombre_cliente, email_cliente, telefono_cliente, nombre_mascota, tipo_mascota, servicio_id, fecha_cita, notas, estado, usuario_id)
            VALUES (:nombre_cliente, :email_cliente, :telefono_cliente, :nombre_mascota, :tipo_mascota, :servicio_id, :fecha_cita, :notas, :estado, :usuario_id)
        ");
        return $stmt->execute([
            ':nombre_cliente' => $data['nombre_cliente'] ?? null,
            ':email_cliente' => $data['email_cliente'] ?? null,
            ':telefono_cliente' => $data['telefono_cliente'] ?? null,
            ':nombre_mascota' => $data['nombre_mascota'] ?? null,
            ':tipo_mascota' => $data['tipo_mascota'] ?? null,
            ':servicio_id' => $data['servicio_id'] ?? null,
            ':fecha_cita' => $data['fecha_cita'] ?? null,
            ':notas' => $data['notas'] ?? null,
            ':estado' => $data['estado'] ?? 'pendiente',
            ':usuario_id' => $_SESSION['usuario_id'] ?? null,
        ]);
    }

    public static function update($data) {
        global $conexion; 
        $stmt = $conexion->prepare("
            UPDATE citas
            SET nombre_cliente = :nombre_cliente, email_cliente = :email_cliente, telefono_cliente = :telefono_cliente,
                nombre_mascota = :nombre_mascota, tipo_mascota = :tipo_mascota, servicio_id = :servicio_id, 
                fecha_cita = :fecha_cita, estado = :estado, notas = :notas
            WHERE id = :id
        ");
        return $stmt->execute([
            ':nombre_cliente' => $data['nombre_cliente'] ?? null,
            ':email_cliente' => $data['email_cliente'] ?? null,
            ':telefono_cliente' => $data['telefono_cliente'] ?? null,
            ':nombre_mascota' => $data['nombre_mascota'] ?? null,
            ':tipo_mascota' => $data['tipo_mascota'] ?? null,
            ':servicio_id' => $data['servicio_id'] ?? null,
            ':fecha_cita' => $data['fecha_cita'] ?? null,
            ':estado' => $data['estado'] ?? 'pendiente',
            ':notas' => $data['notas'] ?? null,
            ':id' => $data['id'],
        ]);
    }

    public static function delete($id) {
        global $conexion;  
        $stmt = $conexion->prepare("DELETE FROM citas WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>
