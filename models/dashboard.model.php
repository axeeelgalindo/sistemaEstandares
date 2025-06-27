<?php
require_once "conexion.php";

class ModeloDashboard
{

    // PERSONAS GRAFICOS
    static public function personasGraficosEntrenamientos($planta_id, $id_area)
    {
        $sql = "EXEC dbo.Personas_Graficos_Creados_Entrenados
              @planta_id = :planta_id,
              @id_area   = :id_area";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->bindParam(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function personasGraficosPorArea($planta_id, $area_id = null)
    {
        $sql = "EXEC dbo.Personas_Graficos_Por_Area @planta_id = :planta, @area_id = :area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta', (int) $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':area', $area_id ? (int) $area_id : null, $area_id ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    // PERSONAS GRAFICOS
    // PERSONAS GRAFICOS

    // ESTANDARES GRAFICOS
    // ESTANDARES GRAFICOS
    static public function getCreadosEntrenados($planta_id, $id_area)
    {
        $conn = Conexion::Conectar();
        $sql = "EXEC Estandares_Graficos_Creados_Entrenados @planta_id = :planta_id, @id_area = :id_area";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(":planta_id", $planta_id, PDO::PARAM_INT);
        $stmt->bindParam(":id_area", $id_area, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    static public function estandaresGraficosAnual($planta_id, $id_area)
    {
        $sql = "EXEC Estandares_Graficos_Anual @planta_id = :planta_id, @id_area = :id_area";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->bindParam(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function graficosPorArea($planta_id, $id_area)
    {
        $sql = "EXEC dbo.Estandares_Graficos_Por_Area
            @planta_id = :planta_id,
            @id_area   = :id_area";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->bindParam(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function graficosPiePilar($planta_id, $id_area)
    {
        $sql = "EXEC dbo.Estandares_Graficos_Pie_Pilar 
               @planta_id = :planta_id,
               @id_area   = :id_area";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->bindParam(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // ESTANDARES GRAFICOS
    // ESTANDARES GRAFICOS

    static public function graficosBarrasCreados($planta_id, $id_area)
    {
        $sql = "EXEC Estandares_Graficos_Barras_Creados @planta_id = :planta_id, @id_area = :id_area";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->bindParam(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function graficosBarrasEntrenados($planta_id, $id_area)
    {
        $sql = "EXEC Estandares_Graficos_Barras_Entrenados @planta_id = :planta_id, @id_area = :id_area";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->bindParam(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindParam(':id_area', $id_area, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
