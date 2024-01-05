<?php
require_once "../models/planos.model.php";
Class ajaxPlanos {
	public function crearPlano(){
		$datos = array(
			           "id_seccion_planta"=>$this->id_seccion_planta,
			            "nombre"=>$this->nombre,
						"plano"=>$this->plano		
					);
		$respuesta = ModeloPlanos::CrearPlanosMdl($datos);
		echo $respuesta;
	}
	public function eliminarPlano(){
		$id_plano = $this->id_plano;
		$pdf_plano = $this->pdf_plano;
		$respuesta = ModeloPlanos::EliminarPlanosMdl($id_plano,$pdf_plano);
		echo $respuesta;
	}

	public function crearSeccionPlanta(){
		$datos = array(
			           "id_planta"=>$this->id_planta,
			            "id_seccion"=>$this->id_seccion
					);
		$respuesta = ModeloPlanos::CrearSeccionPlantaMdl($datos);
		echo $respuesta;
	}
	public function crearPlanta(){
		$datos = array(
			           "nombre_planta"=>$this->nombre_planta,
			            "id_cliente"=>$this->id_cliente,
			            "ubicacion"=>$this->ubicacion
					);
		$respuesta = ModeloPlanos::CrearPlantaMdl($datos);
		echo $respuesta;
	}
	public function editarPlano(){
		$id_plano = $this->id_plano;
		$respuesta = ModeloPlanos::EditarPlanosMdl($id_plano);
		$datos = array("id_plano"=>$respuesta["id"],		
						"name"=>$respuesta["name"],
						"documento"=>$respuesta["documento"]);
	
		echo json_encode($datos);
	}
	public function actualizarPlano(){
		$datos = array( "id_plano"=>$this->id_plano,
						"nombre"=>$this->nombre,
						"archivo"=>$this->archivo,		
						"rutaActual"=>$this->rutaActual		
						);
		$respuesta = ModeloPlanos::ActualizarPlanosMdl($datos);
		echo $respuesta;
	}
	public function eliminarSeccionPlanta(){
		$id_seccion_planta = $this->id_seccion_planta;
		$respuesta = ModeloPlanos::EliminarSeccionPlantaMdl($id_seccion_planta);
		echo $respuesta;
	}
}
$tipoOperacion = $_POST["tipoOperacion"];
if($tipoOperacion == "insertarplano") {
	$crearNuevoPlano = new ajaxPlanos();
	$crearNuevoPlano -> id_seccion_planta = $_POST["id_seccion_planta"];
	$crearNuevoPlano -> nombre = $_POST["NombrePlano"];
    $crearNuevoPlano -> plano = $_FILES["ArchivoPlano"];
	$crearNuevoPlano ->crearPlano();
}
if($tipoOperacion == "editarPlano") {
	$editarPlano = new ajaxPlanos();
	$editarPlano -> id_plano = $_POST["id_plano"];
	$editarPlano ->editarPlano();
}
if ($tipoOperacion == "eliminarPlano") {
	$eliminarPlano = new ajaxPlanos();
	$eliminarPlano -> id_plano = $_POST["id_plano"];
	$eliminarPlano -> pdf_plano = $_POST["rutaPdf"];
	$eliminarPlano -> eliminarPlano();
}
if($tipoOperacion == "insertarseccion") {
	$crearNuevoPlano = new ajaxPlanos();
	$crearNuevoPlano -> id_planta = $_POST["IdPlanta"];
	$crearNuevoPlano -> id_seccion = $_POST["Seccion"];
	$crearNuevoPlano ->crearSeccionPlanta();
}
if($tipoOperacion == "insertarPlanta") {
	$crearNuevoPlano = new ajaxPlanos();
	$crearNuevoPlano -> nombre_planta = $_POST["NombrePlanta"];
	$crearNuevoPlano -> id_cliente = $_POST["ListarCliente"];
	$crearNuevoPlano -> ubicacion = $_POST["Ubicacion"];
	$crearNuevoPlano ->crearPlanta();
}
if ($tipoOperacion == "actualizarplano") {
	$actualizarPlano = new ajaxPlanos();
	$actualizarPlano -> id_plano = $_POST["IdPlano"];
	$actualizarPlano -> nombre = $_POST["NombrePlano"];
    $actualizarPlano -> archivo = $_FILES["ArchivoPlano"];
	$actualizarPlano -> rutaActual = $_POST["Ruta1"];
	$actualizarPlano -> actualizarPlano();
}
if ($tipoOperacion == "eliminarSeccionPlanta") {
	$eliminarSeccionPlanta = new ajaxPlanos();
	$eliminarSeccionPlanta -> id_seccion_planta = $_POST["id_seccion_planta"];
	$eliminarSeccionPlanta -> eliminarSeccionPlanta();
}