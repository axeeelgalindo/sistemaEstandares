<?php
require_once "../models/area.model.php";

class ajaxArea
{
    public $id_area;
    public $nombre;
    public $planta_id;
    public function crearArea()
    {
        $datos = array(
            "nombre" => $this->nombre,
            "planta_id" => $this->planta_id
        );
        $respuesta = ModeloArea::CrearAreaMdl($datos);
        echo json_encode($respuesta);
    }

    public function editarArea()
    {
        $id_area = $this->id_area;
        $respuesta = ModeloArea::EditarAreaMdl($id_area);
        echo json_encode($respuesta);
    }

    public function actualizarArea()
    {
        $datos = array(
            "id_area" => $this->id_area,
            "nombre" => $this->nombre,
            "planta_id" => $this->planta_id


        );
        $respuesta = ModeloArea::ActualizarAreaMdl($datos);
        echo json_encode($respuesta);
    }
    public function eliminarArea()
    {
        $id_area = $this->id_area;
        $respuesta = ModeloArea::EliminarAreaMdl($id_area);
        echo json_encode($respuesta);
    }
}
$tipoOperacion = $_POST["tipoOperacion"];

if ($tipoOperacion == "insertarArea") {
    $crearNuevoArea = new ajaxArea();
    $crearNuevoArea->nombre = $_POST["nombre"];
    $crearNuevoArea->planta_id = isset($_POST["planta_id"]) && $_POST["planta_id"] !== "" ? $_POST["planta_id"] : null;
    $crearNuevoArea->crearArea();
}
if ($tipoOperacion == "editarArea") {
    $editarArea = new ajaxArea();
    $editarArea->id_area = $_POST["id_area"];
    $editarArea->editarArea();
}
if ($tipoOperacion == "actualizarArea") {
    $actualizarArea = new ajaxArea();
    $actualizarArea->id_area = $_POST["id_area"];
    $actualizarArea->nombre = $_POST["nombre"];
    $actualizarArea->planta_id = isset($_POST["planta_id"]) && $_POST["planta_id"] !== ""
                                ? $_POST["planta_id"]
                                : null;
    $actualizarArea->actualizarArea();
}

?>