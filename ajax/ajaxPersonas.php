<?php
require_once "../models/personas.model.php";
class ajaxPersonas
{

	public $id_personas;
	public $rut;
	public $nombre;
	public $apellido;
	public $area;
	public $area_secundaria;
	public $planta_id;

	public function listarPersonas()
	{
		$lista = ModeloPersonas::listarPersonasMdl($this->planta_id);
		echo json_encode($lista);
		exit;
	}
	public function crearPersonas()
	{
		$datos = array(
			"rut" => $this->rut,
			"nombre" => $this->nombre,
			"apellido" => $this->apellido,
			"area" => $this->area,
			"area_secundaria" => $this->area_secundaria,
			"planta_id" => $this->planta_id
		);
		$respuesta = ModeloPersonas::CrearPersonasMdl($datos);
		echo json_encode($respuesta);
	}
	public function editarPersonas()
	{
		$id_personas = $this->id_personas;
		$respuesta = ModeloPersonas::EditarPersonasMdl($id_personas);
		$datos = array(
			"rut" => $respuesta["rut"],
			"nombre" => $respuesta["nombre"],
			"apellido" => $respuesta["apellido"],
			"area" => $respuesta["area"],
			"area_secundaria" => $respuesta["area_secundaria"],
		);

		echo json_encode($datos);
	}

	public function actualizarPersonas()
	{
		$datos = array(
			"id_personas" => $this->id_personas,
			//  "rut"=>$this->rut,
			"nombre" => $this->nombre,
			"apellido" => $this->apellido,
			"area" => $this->area,
			"area_secundaria" => $this->area_secundaria
		);
		$respuesta = ModeloPersonas::ActualizarPersonasMdl($datos);
		echo json_encode($respuesta);
	}
	public function eliminarPersonas()
	{
		$id_personas = $this->id_personas;
		$respuesta = ModeloPersonas::EliminarPersonasMdl($id_personas);
		echo json_encode($respuesta);
	}
}
$ajax = new ajaxPersonas();
$tipoOperacion = $_POST["tipoOperacion"];

if ($tipoOperacion === 'listarPersonas') {
  $ajax->planta_id = intval($_POST['planta_id'] ?? 0);
  $ajax->listarPersonas();
}

if ($tipoOperacion == "insertarPersonas") {
	$crearNuevoPersonas = new ajaxPersonas();
	$crearNuevoPersonas->rut = $_POST["rut"];
	$crearNuevoPersonas->nombre = $_POST["nombre"];
	$crearNuevoPersonas->apellido = $_POST["apellido"];
	$crearNuevoPersonas->area = $_POST["area"];
	$crearNuevoPersonas->area_secundaria = $_POST["areaSecundaria"];
	$crearNuevoPersonas->planta_id = $_POST["planta_id"];
	$crearNuevoPersonas->crearPersonas();
}
if ($tipoOperacion == "editarPersonas") {
	$editarPersonas = new ajaxPersonas();
	$editarPersonas->id_personas = $_POST["id_personas"];
	$editarPersonas->editarPersonas();
}
if ($tipoOperacion == "actualizarPersonas") {
	$actualizarPersonas = new ajaxPersonas();
	$actualizarPersonas->id_personas = $_POST["id_personas"];
	//	$actualizarPersonas -> rut = $_POST["rut"];
	$actualizarPersonas->nombre = $_POST["nombre"];
	$actualizarPersonas->apellido = $_POST["apellido"];
	$actualizarPersonas->area = $_POST["area"];
	$actualizarPersonas->area_secundaria = $_POST["areaSecundaria"];
	$actualizarPersonas->actualizarPersonas();
}
if ($tipoOperacion == "eliminarPersonas") {
	$eliminarPersonas = new ajaxPersonas();
	$eliminarPersonas->id_personas = $_POST["id_personas"];
	$eliminarPersonas->eliminarPersonas();
}
?>