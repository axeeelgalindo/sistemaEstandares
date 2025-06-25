<?php
// Desactivar la visualización de errores en producción
ini_set('display_errors', 0);
error_reporting(0);

// Iniciar buffer de salida
ob_start();

try {
    require_once "../models/porcentajes.model.php";

    // Limpiar cualquier salida anterior
    ob_clean();
    
    // Establecer headers
    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');

    // Verificar si es una petición para obtener datos de gráficos
    if (isset($_POST['accion']) && $_POST['accion'] === 'obtenerDatosGraficos') {
        // Validar parámetros requeridos
        if (empty($_POST['area'])) {
            throw new Exception('El área es requerida');
        }

        // Preparar los filtros
        $filtros = [
            'area' => trim($_POST['area']),
            'fecha' => !empty($_POST['fecha']) ? trim($_POST['fecha']) : date('Y-m-d')
        ];

        // Registrar los filtros para debugging
        error_log("Filtros enviados al modelo: " . print_r($filtros, true));

        try {
            // Obtener datos del modelo
            $datos = ModeloPorcentajes::obtenerDatosGraficosMdl($filtros);
            
            // Si los datos son válidos, enviarlos directamente
            if (isset($datos['status']) && $datos['status'] === 'success') {
                echo json_encode($datos);
                exit;
            } else {
                throw new Exception($datos['message'] ?? 'Error al procesar los datos');
            }
        } catch (Exception $e) {
            error_log("Error completo en obtenerDatosGraficosMdl: " . $e->getMessage());
            throw $e;
        }
    }
    // Si es una petición para guardar datos
    else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $respuesta = array('status' => 'error', 'message' => '');
        
        try {
            // Validar datos recibidos
            $camposRequeridos = ['id_estandar', 'fecha', 'supervisor', 'id_colaborador', 
                                'num_chequeos', 'chequeos_correctos', 'porcentaje'];
            
            foreach ($camposRequeridos as $campo) {
                if (!isset($_POST[$campo]) || empty($_POST[$campo])) {
                    throw new Exception('El campo ' . $campo . ' es requerido');
                }
            }

            // Crear array con los datos a guardar
            $datos = array(
                "id_estandar" => intval($_POST['id_estandar']),
                "fecha" => $_POST['fecha'],
                "supervisor" => $_POST['supervisor'],
                "id_colaborador" => intval($_POST['id_colaborador']),
                "turno" => $_POST['turno'],
                "num_chequeos" => intval($_POST['num_chequeos']),
                "chequeos_correctos" => intval($_POST['chequeos_correctos']),
                "porcentaje" => intval($_POST['porcentaje']),
                "comentarios" => $_POST['comentarios'] ?? ''
            );

            // Verificar si ya existe un registro
            $existe = ModeloPorcentajes::verificarExistenciaMdl($datos['id_estandar'], $datos['id_colaborador']);
            
            if ($existe) {
                $resultado = ModeloPorcentajes::actualizarPorcentajeMdl($datos);
                $mensaje = 'Registro actualizado correctamente';
            } else {
                $resultado = ModeloPorcentajes::crearPorcentajeMdl($datos);
                $mensaje = 'Registro creado correctamente';
            }

            if ($resultado) {
                $respuesta['status'] = 'success';
                $respuesta['message'] = $mensaje;
            } else {
                throw new Exception('Error al procesar la operación en la base de datos');
            }

        } catch (Exception $e) {
            $respuesta['status'] = 'error';
            $respuesta['message'] = $e->getMessage();
        }

        echo json_encode($respuesta);
        exit;
    }
    // Agregar este nuevo bloque dentro del try principal
    else if (isset($_POST['accion']) && $_POST['accion'] === 'obtenerDatosColaborador') {
        // Validar parámetros requeridos
        if (empty($_POST['area'])) {
            throw new Exception('El área es requerida');
        }

        // Preparar los filtros
        $filtros = [
            'area' => trim($_POST['area']),
            'fecha' => !empty($_POST['fecha']) ? trim($_POST['fecha']) : date('Y-m-d'),
            'supervisor' => !empty($_POST['supervisor']) ? trim($_POST['supervisor']) : null,
            'colaborador' => !empty($_POST['colaborador']) ? intval($_POST['colaborador']) : null,
            'turno' => !empty($_POST['turno']) ? trim($_POST['turno']) : null
        ];

        try {
            $datos = ModeloPorcentajes::obtenerDatosColaboradorMdl($filtros);
            
            if (isset($datos['status']) && $datos['status'] === 'success') {
                echo json_encode($datos);
                exit;
            } else {
                throw new Exception($datos['message'] ?? 'Error al procesar los datos');
            }
        } catch (Exception $e) {
            error_log("Error en obtenerDatosColaborador: " . $e->getMessage());
            throw $e;
        }
    }
    else {
        throw new Exception('Acción no válida');
    }

} catch (Exception $e) {
    ob_clean();
    error_log("Error en ajaxPorcentajes: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
} catch (Error $e) {
    ob_clean();
    error_log("Error crítico en ajaxPorcentajes: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Error interno del servidor'
    ]);
    exit;
}