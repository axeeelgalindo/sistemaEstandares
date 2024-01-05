<?php
require_once "../models/estandar.model.php";
Class ajaxEstandar {
	public function crearEstandar(){
		$datos = array(
			            "codigo"=>$this->codigo,
			            "nombre"=>$this->nombre,
			            "tipo"=>$this->tipo,
			            "area"=>$this->area,
                        "imagen_estandar"=>$this->imagen_estandar
					);
   $respuesta = ModeloEstandar::CrearEstandarMdl($datos);
    echo json_encode($respuesta);
	}
	public function editarEstandar(){
    $id_estandar = $this->id_estandar;
    $respuesta = ModeloEstandar::EditarEstandarMdl($id_estandar);
    echo json_encode($respuesta);
}

public function SubirProceso(){
    $id_proceso1 = $this->id_proceso;
	$id_proceso = intval($id_proceso1);
    $respuesta = ModeloEstandar::SubirProcesoMdl($id_proceso);
    echo json_encode($respuesta);
}
public function PersonalValidado(){
    $id_proceso1 = $this->id_proceso;
	$id_proceso = intval($id_proceso1);
    $respuesta = ModeloEstandar::PersonalValidadoMdl($id_proceso);
    echo json_encode($respuesta);
}

public function actualizarEstandar(){
    $datos = array( 
                    "id_estandar"=>$this->id_estandar,	
                    "codigo"=>$this->codigo,
                    "nombre"=>$this->nombre,
                    "tipo"=>$this->tipo,
                    "area"=>$this->area,
                    "imagen_estandar"=>$this->imagen_estandar,		
                    "rutaActual"=>$this->rutaActual	        
                    );
    $respuesta = ModeloEstandar::ActualizarEstandarMdl($datos);
    echo json_encode($respuesta);
}
public function eliminarEstandar(){
    $id_estandar = $this->id_estandar;
		$ruta = $this->imagen_estandar;
    $respuesta = ModeloEstandar::EliminarEstandarMdl($id_estandar,$ruta);
	echo json_encode($respuesta);
}

public function cargarEstandar(){
    $datos = array(
                    "estandar"=>$this->estandar,
                    "area"=>$this->area
                );
$respuesta = ModeloEstandar::CargarEstandarMdl($datos);
echo json_encode($respuesta);
}

public function validarPersonal(){
 $datos = $this->datos;
 $id_estandar = $this->id_estandar;
$respuesta = ModeloEstandar::ValidarPersonalMdl($datos,$id_estandar);
echo json_encode($respuesta);
}
public function CargarEstandares(){
   $respuesta = ModeloEstandar::listarEstandaresCargadosMdl();
   echo json_encode($respuesta);
   }
   public function GraficosCreadosEntrenados(){
	$respuesta = ModeloEstandar::GraficosCreadosEntrenadosMdl();
	echo json_encode($respuesta);
	}
	public function GraficosBarrasEntrenados(){
		$id_area = $this->id_area;
		$respuesta = ModeloEstandar::GraficosBarrasEntrenadosMdl($id_area);
		echo json_encode($respuesta);
	}
	public function GraficosBarrasCreados(){
		$id_area = $this->id_area;
			$respuesta = ModeloEstandar::GraficosBarrasCreadosMdl($id_area );
			echo json_encode($respuesta);
	}
	public function GraficosBarrasAreasCreados(){
     $id_area = $this->id_area;
		$respuesta = ModeloEstandar::GraficosBarrasAreasCreadosMdl($id_area);
		echo json_encode($respuesta);
}
public function GraficosBarrasAreasEntrenados(){
	$id_area = $this->id_area;
	   $respuesta = ModeloEstandar::GraficosBarrasAreasEntrenadosMdl($id_area);
	   echo json_encode($respuesta);
}
public function GraficoPiePorPilar(){
	$id_area = $this->id_area;
	$respuesta = ModeloEstandar::GraficoPiePorPilarMdl($id_area);
	echo json_encode($respuesta);
}
}
$tipoOperacion = $_POST["tipoOperacion"];

if($tipoOperacion == "insertarEstandar") {
	$crearNuevoEstandar = new ajaxEstandar();
	$crearNuevoEstandar -> codigo = $_POST["codigo"];
	$crearNuevoEstandar -> nombre = $_POST["nombre"];
	$crearNuevoEstandar -> tipo = $_POST["tipo"];
	$crearNuevoEstandar -> area = $_POST["area"];
    $crearNuevoEstandar -> imagen_estandar = $_FILES["ArchivoEstandar"];
	$crearNuevoEstandar ->crearEstandar();
}
if ($tipoOperacion == "editarEstandar") {
	$editarEstandar = new ajaxEstandar();
	$editarEstandar -> id_estandar = $_POST["id_estandar"];
	$editarEstandar -> editarEstandar();
}
if ($tipoOperacion == "actualizarEstandar") {
	$actualizarEstandar = new ajaxEstandar();
	$actualizarEstandar -> id_estandar = $_POST["id_estandar"];
	$actualizarEstandar -> codigo = $_POST["codigo"];
	$actualizarEstandar -> nombre = $_POST["nombre"];
	$actualizarEstandar -> tipo = $_POST["tipo"];
	$actualizarEstandar -> area = $_POST["area"];
    $actualizarEstandar -> imagen_estandar = $_FILES["ArchivoEstandar"];
	$actualizarEstandar -> rutaActual = $_POST["rutaActual"];
	$actualizarEstandar -> actualizarEstandar();
}
if ($tipoOperacion == "eliminarEstandar") {
	$eliminarEstandar = new ajaxEstandar();
	$eliminarEstandar -> id_estandar = $_POST["id_estandar"];
    $eliminarEstandar -> imagen_estandar = $_POST["rutaImagen"];
	$eliminarEstandar -> eliminarEstandar();
}

if($tipoOperacion == "cargarEstandar") {
	$cargarEstandar = new ajaxEstandar();
	$cargarEstandar -> estandar = $_POST["estandar"];
	$cargarEstandar -> area = $_POST["area"];
	$cargarEstandar ->cargarEstandar();
}
if ($tipoOperacion == "SubirProceso") {
	$SubirProceso = new ajaxEstandar();
	$SubirProceso -> id_proceso = $_POST["id_proceso"];
	$SubirProceso -> SubirProceso();
}

if ($tipoOperacion == "ValidarPersonal") {
	$ValidarPersonal = new ajaxEstandar();
	$ValidarPersonal -> datos = $_POST["datos"];
	$ValidarPersonal -> id_estandar = $_POST["id_estandar"];
	$ValidarPersonal -> validarPersonal();
}
if ($tipoOperacion == "CargarEstandares") {
	$CargarEstandares = new ajaxEstandar();
	$CargarEstandares -> CargarEstandares();
}
if ($tipoOperacion == "PersonalValidado") {
	$SubirProceso = new ajaxEstandar();
	$SubirProceso -> id_proceso = $_POST["id_proceso"];
	$SubirProceso -> PersonalValidado();
}
if ($tipoOperacion == "GraficoCreados_Entrenados") {
	$GraficosCreadosEntrenados = new ajaxEstandar();
	$GraficosCreadosEntrenados -> GraficosCreadosEntrenados();
}
if ($tipoOperacion == "GraficoBarras_Entrenados") {
	$GraficosBarrasEntrenados = new ajaxEstandar();
	$GraficosBarrasEntrenados -> id_area = $_POST["id_area"];
	$GraficosBarrasEntrenados -> GraficosBarrasEntrenados();
}
if ($tipoOperacion == "GraficoBarras_Creados") {
	$GraficosBarrasCreados = new ajaxEstandar();
	$GraficosBarrasCreados -> id_area = $_POST["id_area"];
	$GraficosBarrasCreados -> GraficosBarrasCreados();
}
if ($tipoOperacion == "GraficoBarras_Areas_Creados") {
	$GraficosBarrasAreasCreados = new ajaxEstandar();
	$GraficosBarrasAreasCreados -> id_area = $_POST["id_area"];
	$GraficosBarrasAreasCreados -> GraficosBarrasAreasCreados();
}
if ($tipoOperacion == "GraficoBarras_Areas_Entrenados") {
	$GraficosBarrasAreasEntrenados = new ajaxEstandar();
	$GraficosBarrasAreasEntrenados -> id_area = $_POST["id_area"];
	$GraficosBarrasAreasEntrenados -> GraficosBarrasAreasEntrenados();
}
if ($tipoOperacion == "GraficoPie_Por_Pilar") {
	$GraficoPiePorPilar = new ajaxEstandar();
	$GraficoPiePorPilar -> id_area = $_POST["id_area"];
	$GraficoPiePorPilar -> GraficoPiePorPilar();
}
?>