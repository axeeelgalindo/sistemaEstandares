<?php
require_once "../models/planta.model.php";

class ajaxPlanta {
    public $id_planta;
    public $nombre;
    public function crearPlanta() {
        $nombre = trim($this->nombre);
        // Verificar si ya existe una planta con ese nombre
        $plantas = ModeloPlanta::listarPlantas();
        foreach ($plantas as $p) {
            if (strtolower($p["nombre"]) == strtolower($nombre)) {
                echo json_encode(["resultado" => 2]); // ya existe
                return;
            }
        }
        $exito = ModeloPlanta::agregarPlanta($nombre);
        echo json_encode(["resultado" => $exito ? 1 : 0]);
    }

    public function editarPlanta() {
        $id = $this->id_planta;
        $respuesta = ModeloPlanta::obtenerPlantaPorId($id);
        echo json_encode($respuesta);
    }

    public function actualizarPlanta() {
        $id = $this->id_planta;
        $nombre = trim($this->nombre);

        $plantaActual = ModeloPlanta::obtenerPlantaPorId($id);
        if (!$plantaActual) {
            echo json_encode(["resultado" => 3]); // no existe
            return;
        }

        // Verificar duplicado en otro ID
        $plantas = ModeloPlanta::listarPlantas();
        foreach ($plantas as $p) {
            if ($p["id"] != $id && strtolower($p["nombre"]) == strtolower($nombre)) {
                echo json_encode(["resultado" => 2]); // ya existe con otro ID
                return;
            }
        }

        $exito = ModeloPlanta::actualizarPlanta($id, $nombre);
        echo json_encode(["resultado" => $exito ? 1 : 0]);
    }

    public function eliminarPlanta() {
        $id = $this->id_planta;

        $planta = ModeloPlanta::obtenerPlantaPorId($id);
        if (!$planta) {
            echo json_encode(["resultado" => 2]); // no existe
            return;
        }

        // Aquí puedes hacer una validación extra si está relacionada a secciones, etc.
        // if (ModeloSeccion::tieneDependencias($id)) {
        //     echo json_encode(["resultado" => 3]);
        //     return;
        // }

        $exito = ModeloPlanta::eliminarPlanta($id);
        echo json_encode(["resultado" => $exito ? 1 : 0]);
    }
}


// ==== EJECUCIÓN POR TIPO DE OPERACIÓN ====
$tipoOperacion = $_POST["tipoOperacion"] ?? null;

if ($tipoOperacion == "insertarPlanta") {
    $crear = new ajaxPlanta();
    $crear->nombre = $_POST["nombre"];
    $crear->crearPlanta();
}

if ($tipoOperacion == "editarPlanta") {
    $editar = new ajaxPlanta();
    $editar->id_planta = $_POST["id_planta"];
    $editar->editarPlanta();
}

if ($tipoOperacion == "actualizarPlanta") {
    $actualizar = new ajaxPlanta();
    $actualizar->id_planta = $_POST["id_planta"];
    $actualizar->nombre = $_POST["nombre"];
    $actualizar->actualizarPlanta();
}

if ($tipoOperacion == "eliminarPlanta") {
    $eliminar = new ajaxPlanta();
    $eliminar->id_planta = $_POST["id_planta"];
    $eliminar->eliminarPlanta();
}
