<?php
require_once "../models/seccion.model.php";
Class ajaxSeccion {
	public function crearSecciones(){
		$datos = array(
			            "Secciones"=>$this->Secciones	
					);
		$respuesta = ModeloSeccion::CrearSeccionesMdl($datos);
		echo $respuesta;
     //  var_dump($respuesta);
	}
	public function editarSecciones(){
    $id_Secciones = $this->id_Secciones;
    $respuesta = ModeloSeccion::EditarSeccionesMdl($id_Secciones);
    $datos = array("id_Secciones"=>$respuesta["id"],		
                    "name"=>$respuesta["name"]);

    echo json_encode($datos);
}

public function actualizarSecciones(){
    $datos = array( "id_Secciones"=>$this->id_Secciones,
                    "nombre"=>$this->nombre            
                    );
    $respuesta = ModeloSeccion::ActualizarSeccionesMdl($datos);
    echo $respuesta;
}
public function eliminarSecciones(){
    $id_Secciones = $this->id_Secciones;
    $respuesta = ModeloSeccion::EliminarSeccionesMdl($id_Secciones);
    echo $respuesta;
}
}
$tipoOperacion = $_POST["tipoOperacion"];

if($tipoOperacion == "insertarSecciones") {
	$crearNuevoSecciones = new ajaxSeccion();
	$crearNuevoSecciones -> Secciones = $_POST["NombreSecciones"];
	$crearNuevoSecciones ->crearSecciones();
}
if ($tipoOperacion == "editarSecciones") {
	$editarSecciones = new ajaxSeccion();
	$editarSecciones -> id_Secciones = $_POST["id_Secciones"];
	$editarSecciones -> editarSecciones();
}
if ($tipoOperacion == "actualizarSecciones") {
	$actualizarSecciones = new ajaxSeccion();
	$actualizarSecciones -> id_Secciones = $_POST["id_seccion"];
	$actualizarSecciones -> nombre = $_POST["NombreSecciones"];
	$actualizarSecciones -> actualizarSecciones();
}
if ($tipoOperacion == "eliminarSecciones") {
	$eliminarSecciones = new ajaxSeccion();
	$eliminarSecciones -> id_Secciones = $_POST["id_Secciones"];
	$eliminarSecciones -> eliminarSecciones();
}
?>