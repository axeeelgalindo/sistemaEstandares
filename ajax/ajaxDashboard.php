<?php
// ajax/dashboardAjax.php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/../models/conexion.php';         // tu conexión
require_once __DIR__ . '/../models/dashboard.model.php'; // el modelo que acabas de crear


// 1) Decodificas el JSON de entrada
$input = json_decode(file_get_contents('php://input'), true);

// 2) Tomas planta_id del request si viene, si no usas la sesión
$planta_id = isset($input['planta_id'])
    ? intval($input['planta_id'])
    : ($_SESSION['planta_id'] ?? 0);

// 3) idem con el filtro de área
$id_area = isset($input['id_area'])
    ? intval($input['id_area'])
    : 0;

// 4) Acción
$accion = $input['accion'] ?? '';

try {
    switch ($accion) {
        //personas
        case 'Personas_Graficos_Creados_Entrenados':
            $out = ModeloDashboard::personasGraficosEntrenamientos($planta_id, $id_area);
            echo json_encode($out);
            break;
        case 'Personas_Graficos_Por_Area':
            $out = ModeloDashboard::personasGraficosPorArea($planta_id, $id_area);
            echo json_encode($out);
            break;

        case 'Personas_Graficos_Pie_Pilar':
            $out = ModeloDashboard::personasGraficosPiePilar($planta_id, $id_area);
            echo json_encode($out);
            break;

        case 'Personas_Graficos_Anual':
            $out = ModeloDashboard::personasGraficosAnual($planta_id, $id_area);
            echo json_encode($out);
            break;

        //estandares
        case 'Estandares_Graficos_Creados_Entrenados':
            $out = ModeloDashboard::getCreadosEntrenados($planta_id, $id_area);
            echo json_encode($out);
            break;

        case 'Estandares_Graficos_Pie_Pilar':
            $out = ModeloDashboard::graficosPiePilar($planta_id, $id_area);
            echo json_encode($out);
            break;

        case 'Estandares_Graficos_Barras_Creados':
            $out = ModeloDashboard::graficosBarrasCreados($planta_id, $id_area);
            echo json_encode($out);
            break;
        case 'Estandares_Graficos_Anual':
            $out = ModeloDashboard::estandaresGraficosAnual($planta_id, $id_area);
            echo json_encode($out);
            break;
        case 'Estandares_Graficos_Por_Area':
            $out = ModeloDashboard::graficosPorArea($planta_id, $id_area);
            echo json_encode($out);
            break;
        case 'Estandares_Graficos_Barras_Entrenados':
            $out = ModeloDashboard::graficosBarrasEntrenados($planta_id, $id_area);
            echo json_encode($out);
            break;

        //ADQUISICIONES
        //ADQUISICIONES
        case 'Estandares_Graficos_Entrenados_Adquiridos':
            $out = ModeloDashboard::graficosAdquisicion($planta_id, $id_area);
            echo json_encode($out);
            break;
        case 'Estandares_Graficos_Por_Area_Adquisicion':
            $out = ModeloDashboard::graficosPorAreaAdquisicion($planta_id, $id_area);
            echo json_encode($out);
            break;

        case 'Estandares_Graficos_Adquisicion_Anual':
            $out = ModeloDashboard::graficosAdquisicionAnual($planta_id, $id_area);
            echo json_encode($out);
            break;
        case 'Estandares_Graficos_Pie_Pilar_Adquisicion':
            // dump de depuración
            error_log("INPUT AJAX → " . print_r($input, true));
            error_log("SESSION planta_id → " . var_export($planta_id, true));
            error_log("AREA id_area → " . var_export($id_area, true));

            $data = ModeloDashboard::getPiePilarAdquisicion($planta_id, $id_area);
            error_log("Pie Pilar Adquisición (PHP) → " . print_r($data, true));

            echo json_encode($data);
            break;

        default:
            http_response_code(400);
            echo json_encode(['error' => 'Acción inválida']);
            break;

    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
