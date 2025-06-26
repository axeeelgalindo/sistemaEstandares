<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');
error_reporting(E_ALL);

ob_start();

try {
    require_once "../models/porcentajes.model.php";

    // ─── 1) Datos para el gráfico de tendencia ──────────────────────────
    if (isset($_POST['accion']) && $_POST['accion'] === 'obtenerDatosGraficos') {
        if (empty($_POST['area'])) {
            throw new Exception('El área es requerida');
        }

        $filtros = [
            'area'        => !empty($_POST['area']) ? intval($_POST['area']) : null,
            'fecha'       => !empty($_POST['fecha']) ? trim($_POST['fecha']) : date('Y-m-d'),
            'supervisor'  => !empty($_POST['supervisor']) ? trim($_POST['supervisor']) : null,
            'colaborador' => !empty($_POST['colaborador']) ? trim($_POST['colaborador']) : null,
            'turno'       => !empty($_POST['turno']) ? trim($_POST['turno']) : null,
        ];

        error_log("== [Ajax] obtenerDatosGraficos – filtros: " . print_r($filtros, true));

        $datos = ModeloPorcentajes::obtenerDatosGraficosMdl($filtros);
        if (isset($datos['status']) && $datos['status'] === 'success') {
            echo json_encode($datos);
            exit;
        } else {
            throw new Exception($datos['message'] ?? 'Error al procesar los datos');
        }
    }

    // ─── 2) Datos para el gráfico “Desempeño por Colaborador” ───────────
    else if (isset($_POST['accion']) && $_POST['accion'] === 'obtenerDatosColaborador') {
        error_log("== [Ajax] obtenerDatosColaborador – POST: " . print_r($_POST, true));

        if (empty($_POST['area']) || $_POST['area'] === '0') {
            echo json_encode(['status' => 'error', 'message' => 'El área es requerida']);
            exit;
        }

        $filtros = [
            'area'        => intval($_POST['area']),
            'fecha'       => $_POST['fecha']      ?? date('Y-m-d'),
            'supervisor'  => $_POST['supervisor'] ?: null,
            'colaborador' => $_POST['colaborador']?: null,
            'turno'       => $_POST['turno']      ?: null,
        ];

        error_log("== [Ajax] obtenerDatosColaborador – filtros: " . print_r($filtros, true));

        $respuesta = ModeloPorcentajes::obtenerDatosColaboradorMdl($filtros);

        error_log("== [Ajax] obtenerDatosColaborador – Modelo devolvió: " . print_r($respuesta, true));

        echo json_encode($respuesta);
        exit;
    }

    // ─── 3) Guardar o actualizar un porcentaje individual ────────────────
    else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $respuesta = ['status' => 'error', 'message' => ''];

        try {
            $camposRequeridos = [
                'id_estandar',
                'fecha',
                'supervisor',
                'rut',
                'num_chequeos',
                'chequeos_correctos',
                'porcentaje'
            ];

            foreach ($camposRequeridos as $campo) {
                if (!isset($_POST[$campo]) || $_POST[$campo] === '') {
                    throw new Exception("El campo {$campo} es requerido");
                }
            }

            $datos = [
                'id_estandar'        => intval($_POST['id_estandar']),
                'fecha'              => $_POST['fecha'],
                'supervisor'         => $_POST['supervisor'],
                'rut'                => $_POST['rut'],
                'turno'              => $_POST['turno'],
                'num_chequeos'       => intval($_POST['num_chequeos']),
                'chequeos_correctos' => intval($_POST['chequeos_correctos']),
                'porcentaje'         => intval($_POST['porcentaje']),
                'comentarios'        => $_POST['comentarios'] ?? ''
            ];

            $existe = ModeloPorcentajes::verificarExistenciaMdl($datos['id_estandar'], $datos['rut']);
            if ($existe) {
                ModeloPorcentajes::actualizarPorcentajeMdl($datos);
                $respuesta['message'] = 'Registro actualizado correctamente';
            } else {
                ModeloPorcentajes::crearPorcentajeMdl($datos);
                $respuesta['message'] = 'Registro creado correctamente';
            }

            $respuesta['status'] = 'success';
        } catch (Exception $e) {
            $respuesta['message'] = $e->getMessage();
        }

        echo json_encode($respuesta);
        exit;
    }

    // ─── 4) Acción inválida ──────────────────────────────────────────────
    else {
        throw new Exception('Acción no válida');
    }

} catch (Exception $e) {
    error_log("Error en ajaxPorcentajes: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
} catch (Error $e) {
    error_log("Error crítico en ajaxPorcentajes: " . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Error interno del servidor']);
    exit;
}
