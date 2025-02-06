<?php
require_once "../models/area.model.php";

class ajaxArea {
    public $nombre;
    public $id_area;

    // Centralized function for error responses
    private function sendError($message) {
        echo json_encode(array("error" => $message));
        exit; // Stop further execution
    }

    // Create new area
    public function crearArea() {
        if (!empty($this->nombre)) {
            $datos = array(
                "nombre" => htmlspecialchars($this->nombre) // Sanitize input
            );
            $respuesta = ModeloArea::CrearAreaMdl($datos);
            echo json_encode($respuesta);
        } else {
            $this->sendError("El nombre del área es requerido.");
        }
    }

    // Edit area
    public function editarArea() {
        if (isset($this->id_area)) {
            $respuesta = ModeloArea::EditarAreaMdl($this->id_area);
            echo json_encode($respuesta);
        } else {
            $this->sendError("El ID del área es requerido.");
        }
    }

    // Update existing area
    public function actualizarArea() {
        if (!empty($this->id_area) && !empty($this->nombre)) {
            $datos = array(
                "id_area" => intval($this->id_area),  // Ensure it's an integer
                "nombre" => htmlspecialchars($this->nombre) // Sanitize input
            );
            $respuesta = ModeloArea::ActualizarAreaMdl($datos);
            echo json_encode($respuesta);
        } else {
            $this->sendError("El ID y el nombre del área son requeridos.");
        }
    }

    // Delete area
    public function eliminarArea() {
        if (isset($this->id_area)) {
            $respuesta = ModeloArea::EliminarAreaMdl($this->id_area);
            echo json_encode($respuesta);
        } else {
            $this->sendError("El ID del área es requerido.");
        }
    }
}

// Detect operation type
if (isset($_POST["tipoOperacion"])) {
    $tipoOperacion = $_POST["tipoOperacion"];
    
    $ajaxArea = new ajaxArea();

    switch ($tipoOperacion) {
        case "insertarArea":
            $ajaxArea->nombre = $_POST["nombre"] ?? ''; // Validate if name is set
            $ajaxArea->crearArea();
            break;
        case "editarArea":
            $ajaxArea->id_area = $_POST["id_area"] ?? null; // Validate if ID is set
            $ajaxArea->editarArea();
            break;
        case "actualizarArea":
            $ajaxArea->id_area = $_POST["id_area"] ?? null; // Validate if ID is set
            $ajaxArea->nombre = $_POST["nombre"] ?? ''; // Validate if name is set
            $ajaxArea->actualizarArea();
            break;
        case "eliminarArea";
		}
	}
