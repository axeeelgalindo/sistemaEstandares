<?php
// ajax/dashboardAjax.php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once __DIR__ . '/../models/conexion.php';         // conexión a la base
require_once __DIR__ . '/../models/dashboard.model.php';  // tu modelo Dashboard

// 1) Leer y decodificar JSON de entrada
$input = json_decode(file_get_contents('php://input'), true);

// 2) Planta: si viene en el payload usamos ese, si no tomamos de sesión o 0
$planta_id = isset($input['planta_id'])
    ? intval($input['planta_id'])
    : (isset($_SESSION['planta_id']) ? intval($_SESSION['planta_id']) : 0);

// 3) Área y año
$id_area = isset($input['id_area']) ? intval($input['id_area']) : 0;
$anio    = isset($input['anio'])    ? intval($input['anio'])    : null;

// 4) Acción solicitada
$accion = isset($input['accion']) ? $input['accion'] : '';

try {
    switch ($accion) {
        // ── PERSONAS ────────────────────────────────────
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

        // ── ESTÁNDARES ──────────────────────────────────
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
            $out = ModeloDashboard::estandaresGraficosAnual($planta_id, $id_area, $anio);
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

        // ── ADQUISICIÓN ────────────────────────────────
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
            $out = ModeloDashboard::getPiePilarAdquisicion($planta_id, $id_area);
            echo json_encode($out);
            break;

        // ── ACCIÓN INVÁLIDA ────────────────────────────
        default:
            http_response_code(400);
            echo json_encode(['error' => "Acción inválida: {$accion}"]);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
