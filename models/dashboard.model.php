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
        $sql = "EXEC dbo.Personas_Graficos_Por_Area
                @planta_id = :planta,
                @area_id   = :area";

        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta', (int) $planta_id, PDO::PARAM_INT);
        if ($area_id === null || $area_id === 0) {
            $stmt->bindValue(':area', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':area', (int) $area_id, PDO::PARAM_INT);
        }
        $stmt->execute();

        // <<< aquí
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    public static function personasGraficosAnual($planta_id, $area_id = null)
    {
        $sql = "EXEC dbo.Personas_Graficos_Anual
            @planta_id = :planta_id,
            @area_id   = :area_id";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', (int) $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':area_id', $area_id ?: 0, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function personasGraficosPiePilar($planta_id, $area_id = null)
    {
        $sql = "EXEC dbo.Personas_Graficos_Pie_Pilar
            @planta_id = :planta,
            @area_id   = :area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta', (int) $planta_id, PDO::PARAM_INT);
        if ($area_id) {
            $stmt->bindValue(':area', (int) $area_id, PDO::PARAM_INT);
        } else {
            $stmt->bindValue(':area', null, PDO::PARAM_NULL);
        }
        $stmt->execute();

        // ---> cámbialo por:
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

    //ADQUISICION
    public static function graficosAdquisicion($planta_id, $id_area = null)
    {
        $sql = "EXEC dbo.Estandares_Graficos_Entrenados_Adquiridos
             @planta_id = :planta_id,
             @id_area   = :id_area";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', (int) $planta_id, PDO::PARAM_INT);
        if (empty($id_area) || $id_area === 0) {
            $stmt->bindValue(':id_area', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':id_area', (int) $id_area, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function graficosPorAreaAdquisicion($planta_id, $id_area = null)
    {
        $sql = "EXEC dbo.Estandares_Graficos_Por_Area_Adquisicion
             @planta_id = :planta_id,
             @id_area   = :id_area";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', (int) $planta_id, PDO::PARAM_INT);
        if (empty($id_area) || $id_area === 0) {
            $stmt->bindValue(':id_area', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':id_area', (int) $id_area, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function graficosAdquisicionAnual($planta_id, $id_area = null)
    {
        $sql = "EXEC dbo.Estandares_Graficos_Adquisicion_Anual
             @planta_id = :planta_id,
             @id_area   = :id_area";
        $stmt = Conexion::Conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', (int) $planta_id, PDO::PARAM_INT);
        if (empty($id_area) || $id_area === 0) {
            $stmt->bindValue(':id_area', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':id_area', (int) $id_area, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getPiePilarAdquisicion(int $planta_id, ?int $id_area): array
    {
        $db = Conexion::conectar();
        $sql = "
            EXEC dbo.Estandares_Graficos_Pie_Pilar_Adquisicion
              @planta_id = :planta,
              @id_area   = :area
            ";
        $stmt = $db->prepare($sql);

        // planta_id siempre INT
        $stmt->bindValue(':planta', (int) $planta_id, PDO::PARAM_INT);

        // id_area = 0 ó null → pasamos NULL al SP
        if (empty($id_area) || $id_area === 0) {
            $stmt->bindValue(':area', null, PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':area', (int) $id_area, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
