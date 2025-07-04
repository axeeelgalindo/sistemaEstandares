<?php
require_once "conexion.php";

class ModeloDashboard
{
    // ── PERSONAS ─────────────────────────────────────────────────────────
    public static function personasGraficosEntrenamientos(int $planta_id, int $id_area): array
    {
        $sql = "EXEC dbo.Personas_Graficos_Creados_Entrenados
              @planta_id = :planta_id,
              @id_area   = :id_area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':id_area',   $id_area,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function personasGraficosPorArea(int $planta_id, int $area_id = 0): array
    {
        $sql = "EXEC dbo.Personas_Graficos_Por_Area
                @planta_id = :planta,
                @area_id   = :area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':area',   $area_id,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function personasGraficosAnual(int $planta_id, int $area_id = 0): array
    {
        $sql = "EXEC dbo.Personas_Graficos_Anual
                @planta_id = :planta_id,
                @area_id   = :area_id";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':area_id',   $area_id,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function personasGraficosPiePilar(int $planta_id, int $area_id = 0): array
    {
        $sql = "EXEC dbo.Personas_Graficos_Pie_Pilar
                @planta_id = :planta,
                @area_id   = :area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':area',   $area_id,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── ESTÁNDARES ────────────────────────────────────────────────────────
    public static function getCreadosEntrenados(int $planta_id, int $id_area): array
    {
        $sql = "EXEC dbo.Estandares_Graficos_Creados_Entrenados
                @planta_id = :planta_id,
                @id_area   = :id_area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':id_area',   $id_area,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function estandaresGraficosAnual(int $planta_id, int $id_area = 0, int $anio = null): array
    {
        $sql = "EXEC dbo.Estandares_Graficos_Anual
                @planta_id = :planta_id,
                @id_area   = :id_area";
        if ($anio !== null) {
            $sql .= ", @anio = :anio";
        }
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':id_area',   $id_area,   PDO::PARAM_INT);
        if ($anio !== null) {
            $stmt->bindValue(':anio', $anio, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function graficosPorArea(int $planta_id, int $id_area = 0): array
    {
        $sql = "EXEC dbo.Estandares_Graficos_Por_Area
                @planta_id = :planta_id,
                @id_area   = :id_area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':id_area',   $id_area,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function graficosPiePilar(int $planta_id, int $id_area = 0): array
    {
        $sql = "EXEC dbo.Estandares_Graficos_Pie_Pilar
                @planta_id = :planta_id,
                @id_area   = :id_area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':id_area',   $id_area,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function graficosBarrasCreados(int $planta_id, int $id_area = 0): array
    {
        $sql = "EXEC dbo.Estandares_Graficos_Barras_Creados
                @planta_id = :planta_id,
                @id_area   = :id_area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':id_area',   $id_area,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function graficosBarrasEntrenados(int $planta_id, int $id_area = 0): array
    {
        $sql = "EXEC dbo.Estandares_Graficos_Barras_Entrenados
                @planta_id = :planta_id,
                @id_area   = :id_area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':id_area',   $id_area,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ── ADQUISICIÓN ───────────────────────────────────────────────────────
    public static function graficosAdquisicion(int $planta_id, int $id_area = 0): array
    {
        $sql = "EXEC dbo.Estandares_Graficos_Entrenados_Adquiridos
                @planta_id = :planta_id,
                @id_area   = :id_area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':id_area',   $id_area,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function graficosPorAreaAdquisicion(int $planta_id, int $id_area = 0): array
    {
        $sql = "EXEC dbo.Estandares_Graficos_Por_Area_Adquisicion
                @planta_id = :planta_id,
                @id_area   = :id_area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':id_area',   $id_area,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function graficosAdquisicionAnual(int $planta_id, int $id_area = 0): array
    {
        $sql = "EXEC dbo.Estandares_Graficos_Adquisicion_Anual
                @planta_id = :planta_id,
                @id_area   = :id_area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta_id', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':id_area',   $id_area,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPiePilarAdquisicion(int $planta_id, int $id_area = 0): array
    {
        $sql = "EXEC dbo.Estandares_Graficos_Pie_Pilar_Adquisicion
                @planta_id = :planta,
                @id_area   = :area";
        $stmt = Conexion::conectar()->prepare($sql);
        $stmt->bindValue(':planta', $planta_id, PDO::PARAM_INT);
        $stmt->bindValue(':area',   $id_area,   PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
