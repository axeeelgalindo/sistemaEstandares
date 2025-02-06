<?php
require_once "conexion.php";

class ModeloPorcentajes {
    
    // Verificar si existe un registro
    static public function verificarExistenciaMdl($idEstandar, $idColaborador) {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT id FROM porcentajes 
                WHERE id_estandar = :id_estandar 
                AND id_colaborador = :id_colaborador");
            
            $stmt->bindParam(":id_estandar", $idEstandar, PDO::PARAM_INT);
            $stmt->bindParam(":id_colaborador", $idColaborador, PDO::PARAM_INT);
            
            $stmt->execute();
            return $stmt->fetch() !== false;
            
        } catch (PDOException $e) {
            error_log("Error en verificarExistenciaMdl: " . $e->getMessage());
            return false;
        } finally {
            $stmt = null;
        }
    }
    
    // Crear nuevo registro
    static public function crearPorcentajeMdl($datos) {
        try {
            $stmt = Conexion::conectar()->prepare("EXEC Porcentaje_Gestionar 
                @id_estandar = :id_estandar,
                @fecha = :fecha,
                @supervisor = :supervisor,
                @id_colaborador = :id_colaborador,
                @turno = :turno,
                @num_chequeos = :num_chequeos,
                @chequeos_correctos = :chequeos_correctos,
                @porcentaje = :porcentaje,
                @comentarios = :comentarios");

            $stmt->bindParam(":id_estandar", $datos["id_estandar"], PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
            $stmt->bindParam(":supervisor", $datos["supervisor"], PDO::PARAM_STR);
            $stmt->bindParam(":id_colaborador", $datos["id_colaborador"], PDO::PARAM_INT);
            $stmt->bindParam(":turno", $datos["turno"], PDO::PARAM_STR);
            $stmt->bindParam(":num_chequeos", $datos["num_chequeos"], PDO::PARAM_INT);
            $stmt->bindParam(":chequeos_correctos", $datos["chequeos_correctos"], PDO::PARAM_INT);
            $stmt->bindParam(":porcentaje", $datos["porcentaje"], PDO::PARAM_INT);
            $stmt->bindParam(":comentarios", $datos["comentarios"], PDO::PARAM_STR);

            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return isset($resultado['resultado']) && $resultado['resultado'] == 1;

        } catch (PDOException $e) {
            error_log("Error en crearPorcentajeMdl: " . $e->getMessage());
            return false;
        } finally {
            $stmt = null;
        }
    }
    
    // Actualizar registro existente
    static public function actualizarPorcentajeMdl($datos) {
        try {
            $stmt = Conexion::conectar()->prepare("UPDATE porcentajes SET 
                fecha = :fecha,
                supervisor = :supervisor,
                id_colaborador = :id_colaborador,
                turno = :turno,
                num_chequeos = :num_chequeos,
                chequeos_correctos = :chequeos_correctos,
                porcentaje = :porcentaje,
                comentarios = :comentarios,
                fecha_registro = GETDATE()
                WHERE id_estandar = :id_estandar");
            
            $stmt->bindParam(":id_estandar", $datos["id_estandar"], PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
            $stmt->bindParam(":supervisor", $datos["supervisor"], PDO::PARAM_STR);
            $stmt->bindParam(":id_colaborador", $datos["id_colaborador"], PDO::PARAM_INT);
            $stmt->bindParam(":turno", $datos["turno"], PDO::PARAM_STR);
            $stmt->bindParam(":num_chequeos", $datos["num_chequeos"], PDO::PARAM_INT);
            $stmt->bindParam(":chequeos_correctos", $datos["chequeos_correctos"], PDO::PARAM_INT);
            $stmt->bindParam(":porcentaje", $datos["porcentaje"], PDO::PARAM_INT);
            $stmt->bindParam(":comentarios", $datos["comentarios"], PDO::PARAM_STR);
            
            if (!$stmt->execute()) {
                error_log("Error en actualizarPorcentajeMdl: " . print_r($stmt->errorInfo(), true));
                throw new PDOException($stmt->errorInfo()[2]);
            }
            
            return true;
            
        } catch (PDOException $e) {
            error_log("Error en actualizarPorcentajeMdl: " . $e->getMessage());
            throw $e;
        } finally {
            $stmt = null;
        }
    }

    // Obtener datos para los gráficos
    static public function obtenerDatosGraficosMdl($filtros) {
        try {
            $conn = Conexion::conectar();
            
            $sql = "EXEC SP_ObtenerPorcentajesSemana 
                    @FechaSeleccionada = :fecha,
                    @AreaSeleccionada = :area";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fecha', $filtros['fecha'], PDO::PARAM_STR);
            $stmt->bindParam(':area', $filtros['area'], PDO::PARAM_STR);
            
            if (!$stmt->execute()) {
                throw new Exception("Error al ejecutar el procedimiento: " . print_r($stmt->errorInfo(), true));
            }

            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Inicializar arrays para ambos turnos
            $porcentajesSemana = [
                'Día' => array_fill(0, 7, 0),
                'Noche' => array_fill(0, 7, 0)
            ];
            
            $totalRegistros = 0;
            $sumaPorcentajes = 0;
            $contadorRegistros = 0;

            // Procesar los resultados
            foreach ($resultados as $row) {
                $diaSemana = intval($row['dia_semana']) - 1; // Convertir a índice 0-based
                $turno = $row['turno'];
                $porcentaje = floatval($row['porcentaje_promedio']);
                $registros = intval($row['total_registros']);
                
                // Asignar porcentaje al día y turno correspondiente
                if (isset($porcentajesSemana[$turno])) {
                    $porcentajesSemana[$turno][$diaSemana] = round($porcentaje, 1);
                    
                    // Acumular para el promedio general solo si hay registros
                    if ($registros > 0) {
                        $sumaPorcentajes += ($porcentaje * $registros);
                        $contadorRegistros += $registros;
                    }
                }
                
                $totalRegistros += $registros;
            }

            // Calcular el porcentaje general ponderado
            $porcentajeGeneral = $contadorRegistros > 0 
                ? round($sumaPorcentajes / $contadorRegistros, 1) 
                : 0;

            return [
                'status' => 'success',
                'data' => [
                    'porcentajesSemana' => $porcentajesSemana,
                    'porcentajeGeneral' => $porcentajeGeneral,
                    'totalRegistros' => $totalRegistros,
                    'debug' => [
                        'resultados' => $resultados,
                        'fecha' => $filtros['fecha'],
                        'area' => $filtros['area']
                    ]
                ]
            ];

        } catch (Exception $e) {
            error_log("Error en obtenerDatosGraficosMdl: " . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Error al procesar los datos: ' . $e->getMessage()
            ];
        }
    }
    // Agregar este nuevo método en la clase ModeloPorcentajes
static public function obtenerDatosColaboradorMdl($filtros) {
    try {
        $conn = Conexion::conectar();
        
        // Si no hay filtros específicos, obtener todos los colaboradores del área
        if (empty($filtros['supervisor']) && empty($filtros['colaborador']) && empty($filtros['turno'])) {
            $sql = "EXEC SP_ObtenerDesempenoColaborador 
                    @FechaSeleccionada = :fecha,
                    @AreaSeleccionada = :area,
                    @Supervisor = NULL,
                    @Colaborador = NULL,
                    @Turno = NULL";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fecha', $filtros['fecha'], PDO::PARAM_STR);
            $stmt->bindParam(':area', $filtros['area'], PDO::PARAM_STR);
        } else {
            $sql = "EXEC SP_ObtenerDesempenoColaborador 
                    @FechaSeleccionada = :fecha,
                    @AreaSeleccionada = :area,
                    @Supervisor = :supervisor,
                    @Colaborador = :colaborador,
                    @Turno = :turno";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fecha', $filtros['fecha'], PDO::PARAM_STR);
            $stmt->bindParam(':area', $filtros['area'], PDO::PARAM_STR);
            $stmt->bindParam(':supervisor', $filtros['supervisor'], PDO::PARAM_STR);
            $stmt->bindParam(':colaborador', $filtros['colaborador'], PDO::PARAM_INT);
            $stmt->bindParam(':turno', $filtros['turno'], PDO::PARAM_STR);
        }
        
        if (!$stmt->execute()) {
            throw new Exception("Error al ejecutar el procedimiento");
        }

        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        return [
            'status' => 'success',
            'data' => $resultados
        ];

    } catch (Exception $e) {
        error_log("Error en obtenerDatosColaboradorMdl: " . $e->getMessage());
        return [
            'status' => 'error',
            'message' => 'Error al procesar los datos: ' . $e->getMessage()
        ];
    }
}
}
