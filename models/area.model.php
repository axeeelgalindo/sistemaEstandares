<?php
require_once "conexion.php";

class ModeloArea {
    // List all areas
    static public function listarAreaMdl() {
        try {
            $conn = Conexion::Conectar();
            $sql = "EXEC Listar_Area";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Error en la consulta: " . $e->getMessage()];
        }
    }

    // List types
    static public function listarTipoMdl() {
        try {
            $conn = Conexion::Conectar();
            $sql = "EXEC Listar_Tipo";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Error en la consulta: " . $e->getMessage()];
        }
    }

    // Create a new area
    static public function CrearAreaMdl($datos) {
        try {
            $conn = Conexion::Conectar();
            $sql = "EXEC Area_Crear @nombre = :nombre";            
            $stmt = $conn->prepare($sql);		
            $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Error en la consulta: " . $e->getMessage()];
        }
    }

    // Edit an area by ID
    static public function EditarAreaMdl($id_area) {
        try {
            $conn = Conexion::Conectar();
            $sql = "EXEC Area_Editar_Detalle @id = :id";			
            $stmt = $conn->prepare($sql);		
            $stmt->bindParam(":id", $id_area, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Error en la consulta: " . $e->getMessage()];
        }
    }

    // Update an existing area
    static public function ActualizarAreaMdl($datos) {	
        try {
            $conn = Conexion::Conectar();
            $sql = "EXEC Area_Actualizar @nombre = :nombre, @id = :id_area";			
            $stmt = $conn->prepare($sql);		
            $stmt->bindParam(":id_area", $datos["id_area"], PDO::PARAM_INT);
            $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
            $stmt->execute();	
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Error en la consulta: " . $e->getMessage()];
        }
    }


    static public function EliminarAreaMdl($id_area){
        try {
            //Conexion a base de datos
            $conn = Conexion::Conectar();
            //Consulta SQL
            $sql = "EXEC Area_Eliminar @id = id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $id_area, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Error en la consulta: " . $e->getMessage()];
        }

            
            
    }
}

