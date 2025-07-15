--use db_estandares_Espejo;

SET NOCOUNT ON;
DECLARE 
  @rut VARCHAR(20) = '08764546-0';  -- Reemplaza por el RUT deseado

SELECT
  p.rut,
  p.nombre + ' ' + p.apellido    AS NombreCompleto,
  sp.id_estandar                 AS IdEstandar,
  e.nombre                       AS NombreEstandar,
  ep.fecha_entrenamiento         AS FechaEntrenamiento,
  a1.nombre                      AS Area,
  a2.nombre                      AS Subarea,
  et.tipo                        AS TipoEstandar,
  eps.estado                     AS EstatusAdopcion
FROM dbo.personas AS p
  INNER JOIN dbo.estandares_personas AS ep
    ON ep.rut_persona = p.rut
  INNER JOIN dbo.estandares_proceso AS sp
    ON ep.id_estandares_proceso = sp.id
  INNER JOIN dbo.estandares AS e
    ON sp.id_estandar = e.id
  LEFT JOIN dbo.estandares_tipos AS et
    ON e.tipo = et.id
  LEFT JOIN dbo.estandares_personas_estados AS eps
    ON ep.id_estado_proceso_personas = eps.id
  LEFT JOIN dbo.areas AS a1
    ON p.area = a1.id
  LEFT JOIN dbo.areas AS a2
    ON p.area_secundaria = a2.id
WHERE 
  p.rut = @rut
  AND ep.fecha_entrenamiento IS NOT NULL    -- <-- aquí filtras solo entrenamientos hechos
ORDER BY ep.fecha_entrenamiento DESC;
