<?php
require_once "conexion.php";

class ModeloPorcentajes
{

    // Verificar si existe un registro
    static public function verificarExistenciaMdl($idEstandar, $rut)
    {
        try {
            $stmt = Conexion::conectar()->prepare("SELECT id FROM porcentajes 
                WHERE id_estandar = :id_estandar 
                AND rut = :rut");

            $stmt->bindParam(":id_estandar", $idEstandar, PDO::PARAM_INT);
            $stmt->bindParam(':rut', $rut, PDO::PARAM_STR);


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
    static public function crearPorcentajeMdl($datos)
    {
        try {
            $stmt = Conexion::conectar()->prepare("EXEC Porcentaje_Gestionar 
                @id_estandar = :id_estandar,
                @fecha = :fecha,
                @supervisor = :supervisor,
                @rut                = :rut,
                @turno = :turno,
                @num_chequeos = :num_chequeos,
                @chequeos_correctos = :chequeos_correctos,
                @porcentaje = :porcentaje,
                @adquirido          = :adquirido, 
                @comentarios = :comentarios");

            $stmt->bindParam(":id_estandar", $datos["id_estandar"], PDO::PARAM_INT);
            $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
            $stmt->bindParam(":supervisor", $datos["supervisor"], PDO::PARAM_STR);
            $stmt->bindParam(':rut', $datos['rut'], PDO::PARAM_STR);
            $stmt->bindParam(":turno", $datos["turno"], PDO::PARAM_STR);
            $stmt->bindParam(":num_chequeos", $datos["num_chequeos"], PDO::PARAM_INT);
            $stmt->bindParam(":chequeos_correctos", $datos["chequeos_correctos"], PDO::PARAM_INT);
            $stmt->bindParam(":porcentaje", $datos["porcentaje"], PDO::PARAM_INT);
            $stmt->bindValue(':adquirido', 1, PDO::PARAM_BOOL);
            $stmt->bindParam(":comentarios", $datos["comentarios"], PDO::PARAM_STR);

            // 1) Ejecutamos
            $stmt->execute();

            // 2) Leemos el primer row
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // 3) Volcamos en el log para inspección
            error_log("SP Porcentaje_Gestionar devolvió: " . print_r($row, true));

            // 4) Validamos que venga el campo 'resultado'
            if (!isset($row['resultado'])) {
                throw new Exception("SP Porcentaje_Gestionar no devolvió resultado");
            }

            // 5) Si fue éxito
            if ($row['resultado'] == 1) {
                return true;
            }

            // 6) Si falló (resultado == 0), capturamos sus detalles
            $num = $row['ErrorNumber'] ?? 'desconocido';
            $msg = $row['ErrorMessage'] ?? 'sin mensaje';
            throw new Exception("SP Error #{$num}: {$msg}");
        } catch (PDOException $e) {
            // Si PDO itself falló (p.ej. sintaxis SQL, conexión, etc)
            error_log("PDOException en crearPorcentajeMdl: " . $e->getMessage());
            throw new Exception("PDOException: " . $e->getMessage());
        }
    }

    // Actualizar registro existente
    static public function actualizarPorcentajeMdl($datos)
    {
        try {
            $stmt = Conexion::conectar()->prepare("UPDATE porcentajes SET 
                fecha = :fecha,
                supervisor = :supervisor,
                rut = :rut,
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
            $stmt->bindParam(":rut", $datos["rut"], PDO::PARAM_STR);
            $stmt->bindParam(":turno", $datos["turno"], PDO::PARAM_STR);
            $stmt->bindParam(":num_chequeos", $datos["num_chequeos"], PDO::PARAM_INT);
            $stmt->bindParam(":chequeos_correctos", $datos["chequeos_correctos"], PDO::PARAM_INT);
            $stmt->bindParam(":porcentaje", $datos["porcentaje"], PDO::PARAM_INT);
            $stmt->bindParam(":comentarios", $datos["comentarios"], PDO::PARAM_STR);

            if (!$stmt->execute()) {
                $info = $stmt->errorInfo();
                error_log("Error en actualizarPorcentajeMdl: " . print_r($info, true));
                throw new PDOException($info[2]);
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
    static public function obtenerDatosGraficosMdl(array $filtros)
    {
        try {
            $conn = Conexion::conectar();

            $sql = "EXEC SP_ObtenerPorcentajesSemana
                        @FechaSeleccionada = :fecha,
                        @AreaSeleccionada  = :area,
                        @Supervisor        = :supervisor,
                        @Colaborador       = :colaborador,
                        @Turno             = :turno,
                        @planta_id         = :planta_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':fecha', $filtros['fecha'], PDO::PARAM_STR);
            $stmt->bindParam(':area', $filtros['area'], PDO::PARAM_INT);
            $stmt->bindParam(':supervisor', $filtros['supervisor'], PDO::PARAM_STR);
            $stmt->bindParam(':colaborador', $filtros['colaborador'], PDO::PARAM_STR);
            $stmt->bindParam(':turno', $filtros['turno'], PDO::PARAM_STR);
            $stmt->bindParam(':planta_id', $_SESSION['planta_id'], PDO::PARAM_INT);


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
    static public function obtenerDatosColaboradorMdl($filtros)
    {
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
                $stmt->bindParam(':area', $filtros['area'], PDO::PARAM_INT);
            } else {
                $sql = "EXEC SP_ObtenerDesempenoColaborador 
                    @FechaSeleccionada = :fecha,
                    @AreaSeleccionada = :area,
                    @Supervisor = :supervisor,
                    @Colaborador = :colaborador,
                    @Turno = :turno";

                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':fecha', $filtros['fecha'], PDO::PARAM_STR);
                $stmt->bindParam(':area', $filtros['area'], PDO::PARAM_INT);
                $stmt->bindParam(':supervisor', $filtros['supervisor'], PDO::PARAM_STR);
                $stmt->bindParam(':colaborador', $filtros['colaborador'], PDO::PARAM_STR);
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
