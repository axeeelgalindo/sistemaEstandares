<?php
class ControllerEnrutamiento {
static	public function enrutamiento() {
		$ruta = $_GET["ruta"];
		if ($ruta == "dashboard" || 
		 $ruta == "personas" || 
		 $ruta == "estandareseditar" || 
		 $ruta == "estandaresgestion" || 
		 $ruta == "porcentajeseditar" || 
		 $ruta == "porcentajesvisualizar" || 
		 $ruta == "usuarios" || 
		 $ruta == "usuarioajustes" || 
		 $ruta == "nivelesusuario" || 
		 $ruta == "areas" ||
		 $ruta == "plantas" ||  
		 $ruta == "reportes" || 
		 $ruta == "unidades" || 
         $ruta == "salir" 
		) {
			include "views/modulos/".$ruta.".php";
		} else {
			
				include "views/modulos/error404.php";
			}
		}
	}
?>