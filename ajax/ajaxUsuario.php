<?php
session_start();
require_once "../models/usuario.model.php";
Class ajaxUsuario {
	public function crearUsuario(){
		$datos = array(		          
			            "nombre"=>$this->nombre,
			            "email"=>$this->email,	
			            "password"=>$this->password,	            
			            "nivel"=>$this->nivel	            
					);
   $respuesta = ModeloUsuario::CrearUsuarioMdl($datos);
    echo json_encode($respuesta);
	}
	public function editarUsuario(){
    $id_usuario = $this->id_usuario;
    $respuesta = ModeloUsuario::EditarUsuarioMdl($id_usuario);
    echo json_encode($respuesta);
}

public function actualizarUsuario(){
    $datos = array( 
                    "id_usuario"=>$this->id_usuario,	              
                    "nombre"=>$this->nombre,
					"email"=>$this->email,	
					"password"=>$this->password,	            
					"nivel"=>$this->nivel
                    );
    $respuesta = ModeloUsuario::ActualizarUsuarioMdl($datos);
    echo json_encode($respuesta);
}
public function deshabilitarUsuario(){
    $id_usuario = $this->id_usuario;
    $respuesta = ModeloUsuario::DeshabilitarUsuarioMdl($id_usuario);
	echo json_encode($respuesta);
}

public function actualizarPerfil(){
    $datos = array( 
                    "id_usuario"=>$this->id_usuario,	              
                    "nombre"=>$this->nombre,
                    "email"=>$this->email,
                    "password"=>$this->password
                    );
    $respuesta = ModeloUsuario::ActualizarPerfilMdl($datos);

	if( $this->email_old != $this->email){
		if($respuesta["resultado"] == 1){
			$_SESSION["nombre"] = $this->email;
		}
	}
    echo json_encode($respuesta);
}

}
$tipoOperacion = $_POST["tipoOperacion"];

if($tipoOperacion == "insertarUsuario") {
	$crearNuevoUsuario = new ajaxUsuario();
	$crearNuevoUsuario -> nombre = $_POST["nombre"];
	$crearNuevoUsuario -> email = $_POST["email"];
	$crearNuevoUsuario -> password = $_POST["password"];
	$crearNuevoUsuario -> nivel = $_POST["nivel"];
	$crearNuevoUsuario ->crearUsuario();
}
if ($tipoOperacion == "editarUsuario") {
	$editarUsuario = new ajaxUsuario();
	$editarUsuario -> id_usuario = $_POST["id_usuario"];
	$editarUsuario -> editarUsuario();
}
if ($tipoOperacion == "actualizarUsuario") {
	$actualizarUsuario = new ajaxUsuario();
	$actualizarUsuario -> id_usuario = $_POST["id_usuario"];
	$actualizarUsuario -> nombre = $_POST["nombre"];
	$actualizarUsuario -> email = $_POST["email"];
	$actualizarUsuario -> password = $_POST["password"];
	$actualizarUsuario -> nivel = $_POST["nivel"];
	$actualizarUsuario -> actualizarUsuario();
}
if ($tipoOperacion == "deshabilitarUsuario") {
	$eliminarUsuario = new ajaxUsuario();
	$eliminarUsuario -> id_usuario = $_POST["id_usuario"];
	$eliminarUsuario -> deshabilitarUsuario();
}
if ($tipoOperacion == "actualizarPerfil") {
	$actualizarPerfil = new ajaxUsuario();
	$actualizarPerfil -> id_usuario = $_POST["id_usuario"];
	$actualizarPerfil -> nombre = $_POST["nombre"];
	$actualizarPerfil -> email = $_POST["email"];
	$actualizarPerfil -> email_old = $_POST["email_old"];
	$actualizarPerfil -> password = $_POST["password"];
	$actualizarPerfil -> actualizarPerfil();
}
?>