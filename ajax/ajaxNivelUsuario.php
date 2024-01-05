<?php
session_start();
require_once "../models/nivelusuario.model.php";
Class ajaxNivelUsuario {
	public function ModificarEstadoNivel(){
		$datos = array(		          
			            "id_menu_estandar"=>$this->id_menu_estandar,
			            "valor_select"=>$this->valor_select            
					);
   $respuesta = ModeloNivelusuario::ActualizarEstadoNivelMdl($datos);
    echo json_encode($respuesta);
	}
}
$tipoOperacion = $_POST["tipoOperacion"];
if($tipoOperacion == "ModificarEstadoNivel") {
	$ActualizarEstadoNivel = new ajaxNivelUsuario();
	$ActualizarEstadoNivel -> id_menu_estandar = $_POST["id_menu_estandar"];
	$ActualizarEstadoNivel -> valor_select = $_POST["valor_select"];
	$ActualizarEstadoNivel ->ModificarEstadoNivel();
}

?>