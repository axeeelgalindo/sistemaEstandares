<?php
require_once "../models/unidades.model.php";
Class ajaxUnidad {
	public function crearUnidad(){
		$datos = array(		          
			            "nombre"=>$this->nombre	            
					);
   $respuesta = ModeloUnidad::CrearUnidadMdl($datos);
    echo json_encode($respuesta);
	}
	public function editarUnidad(){
    $id_unidad = $this->id_unidad;
    $respuesta = ModeloUnidad::EditarUnidadMdl($id_unidad);
    echo json_encode($respuesta);
}

public function actualizarUnidad(){
    $datos = array( 
                    "id_unidad"=>$this->id_unidad,	              
                    "nombre"=>$this->nombre
                    );
    $respuesta = ModeloUnidad::ActualizarUnidadMdl($datos);
    echo json_encode($respuesta);
}
public function eliminarUnidad(){
    $id_unidad = $this->id_unidad;
    $respuesta = ModeloUnidad::EliminarUnidadMdl($id_unidad);
	echo json_encode($respuesta);
}

}
$tipoOperacion = $_POST["tipoOperacion"];

if($tipoOperacion == "insertarUnidad") {
	$crearNuevoUnidad = new ajaxUnidad();
	$crearNuevoUnidad -> nombre = $_POST["nombre"];
	$crearNuevoUnidad ->crearUnidad();
}
if ($tipoOperacion == "editarUnidad") {
	$editarUnidad = new ajaxUnidad();
	$editarUnidad -> id_unidad = $_POST["id_unidad"];
	$editarUnidad -> editarUnidad();
}
if ($tipoOperacion == "actualizarUnidad") {
	$actualizarUnidad = new ajaxUnidad();
	$actualizarUnidad -> id_unidad = $_POST["id_unidad"];
	$actualizarUnidad -> nombre = $_POST["nombre"];
	$actualizarUnidad -> actualizarUnidad();
}
if ($tipoOperacion == "eliminarUnidad") {
	$eliminarUnidad = new ajaxUnidad();
	$eliminarUnidad -> id_unidad = $_POST["id_unidad"];
	$eliminarUnidad -> eliminarUnidad();
}
?>