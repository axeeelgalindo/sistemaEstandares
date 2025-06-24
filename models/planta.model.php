<?php
require_once __DIR__ . '/conexion.php';

class ModeloPlanta {
    // Listar todas las plantas
    static public function listarPlantas() {
        $stmt = Conexion::Conectar()->prepare("SELECT * FROM plantas ORDER BY nombre ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Agregar una nueva planta
    static public function agregarPlanta($nombre) {
        $stmt = Conexion::Conectar()->prepare("INSERT INTO plantas (nombre) VALUES (:nombre)");
        $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // Obtener una planta por ID
    static public function obtenerPlantaPorId($id) {
        $stmt = Conexion::Conectar()->prepare("SELECT * FROM plantas WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Actualizar el nombre de una planta
    static public function actualizarPlanta($id, $nombre) {
        $stmt = Conexion::Conectar()->prepare("UPDATE plantas SET nombre = :nombre WHERE id = :id");
        $stmt->bindParam(":nombre", $nombre, PDO::PARAM_STR);
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Eliminar una planta
    static public function eliminarPlanta($id) {
        $stmt = Conexion::Conectar()->prepare("DELETE FROM plantas WHERE id = :id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
