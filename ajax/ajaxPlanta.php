<?php
require_once "../models/seccion.model.php";
Class ajaxPlanta {
	public function CargarSeccion(){
        //echo  $this->id_planta;
		$id_planta = $this->id_planta;
		$respuesta = ModeloSeccion::listarSeccionMdl($id_planta);
		echo json_encode($respuesta);
	}
    public function CargarSeccionSelect(){
        //echo  $this->id_planta;
		$id_planta = $this->id_planta;
		$respuesta = ModeloSeccion::listarSeccionTotalMdl($id_planta);
		echo json_encode($respuesta);
	}
    public function CargarPlanos(){
        //echo  $this->id_planta;
		$id_planta = $this->id_planta;
		$respuesta = ModeloSeccion::listarPlanosMdl($id_planta);
		echo json_encode($respuesta);
	}
    public function eliminarPlanta(){
        $id_planta = $this->id_planta;
        $respuesta = ModeloSeccion::EliminarPlantaMdl($id_planta);
        echo $respuesta;
    }
}

	$tipoOperacion = $_POST["tipoOperacion"];
    if ($tipoOperacion == "CargarSeccion") {
        $cargarSeccion = new ajaxPlanta();
        $cargarSeccion -> id_planta = $_POST["id_planta"];
        $cargarSeccion -> CargarSeccion();
    }
    if ($tipoOperacion == "CargarSeccionSelect") {
        $cargarSeccion = new ajaxPlanta();
        $cargarSeccion -> id_planta = $_POST["id_planta"];
        $cargarSeccion -> CargarSeccionSelect();
    }
    if ($tipoOperacion == "CargarPlanos") {
        $cargarPlanos = new ajaxPlanta();
        $cargarPlanos -> id_planta = $_POST["id_planta"];
        $cargarPlanos -> CargarPlanos();
    }
    if ($tipoOperacion == "eliminarPlanta") {
        $eliminarPlanta = new ajaxPlanta();
        $eliminarPlanta -> id_planta = $_POST["id_planta"];
        $eliminarPlanta -> eliminarPlanta();
    }