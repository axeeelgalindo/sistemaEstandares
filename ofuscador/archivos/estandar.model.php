<?php
require_once "conexion.php";
Class ModeloEstandar {

	static public function listarEstandaresMdl() {
		try {
			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Listar_Estandares";
			
			// Prepara la consulta
			$stmt = $conn->prepare($sql);
						
			// Ejecuta el procedimiento almacenado
			$stmt->execute();
			
			// Recupera el resultado
			$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			return $result;
		} catch (PDOException $e) {
			die("Error en la consulta: " . $e->getMessage());
		}
    }
	static public function CrearEstandarMdl($datos) {
//var_dump( $datos);

		try {
            if ($datos["imagen_estandar"]["error"] == 4) {
                $rutaImagen = null;
            }else{
            list($ancho, $alto) = getimagesize($datos["imagen_estandar"]["tmp_name"]);
            $nuevoAncho =1010;
            $nuevoAlto = 800;
            $directorio = "../views/img/estandares";
            if($datos["imagen_estandar"]["type"] == "image/jpeg"){
                $rutaImagen = $directorio."/".md5($datos["imagen_estandar"]["tmp_name"]).".jpeg";
                $origen = imagecreatefromjpeg($datos["imagen_estandar"]["tmp_name"]);
                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            }
            if($datos["imagen_estandar"]["type"] == "image/png"){
                $rutaImagen = $directorio."/".md5($datos["imagen_estandar"]["name"]).".png";
                $origen = imagecreatefrompng($datos["imagen_estandar"]["tmp_name"]);
                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            }
        }

			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Estandar_Crear @codigo = :codigo, @nombre = :nombre, @tipo = :tipo, @AreaList = :area, @ruta = :ruta";			
			// Prepara la consulta
			$stmt = $conn->prepare($sql);		
			// Asocia los valores a los parámetros
			$stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
			$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
			$stmt->bindParam(":tipo", $datos["tipo"], PDO::PARAM_INT);
			//$stmt->bindParam(":area", $datos["area"], PDO::PARAM_INT);
			$stmt->bindParam(":ruta", $rutaImagen, PDO::PARAM_STR);
            // Enlaza el parámetro del tipo de tabla
            $ids = [];
            foreach ($datos["area"] as $key => $value) {
                $ids[] = $value;
            }
            $idString = implode(",", $ids);
			$stmt->bindParam(":area", $idString, PDO::PARAM_STR);

			// Ejecuta el procedimiento almacenado
			$stmt->execute();
			
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

		if($result["resultado"] == 1){
                if($datos["imagen_estandar"]["type"] == "image/jpeg"){
                            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                            imagejpeg($destino, $rutaImagen);
                        }
                        if($datos["imagen_estandar"]["type"] == "image/png"){
                            imagealphablending($destino, FALSE);
                            imagesavealpha($destino, TRUE);
                            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                            imagepng($destino, $rutaImagen);
                        }



            } 
			return $result;
		} catch (PDOException $e) {
			die("Error en la consulta: " . $e->getMessage());
		}
	}

	static public function EditarEstandarMdl($id_estandar) {
		try {


			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Estandar_Editar_Detalle @id = :id";			
			// Prepara la consulta
			$stmt = $conn->prepare($sql);		
			// Asocia los valores a los parámetros
			$stmt->bindParam(":id", $id_estandar, PDO::PARAM_INT);
			// Ejecuta el procedimiento almacenado
			$stmt->execute();
			
			// Recupera el resultado
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			return $result;
		} catch (PDOException $e) {
			die("Error en la consulta: " . $e->getMessage());
		}
	}
	static public function ActualizarEstandarMdl($datos) {	
		try {
//var_dump($datos);

            if ($datos["imagen_estandar"]["error"] == 4) {
                $rutaImagen = null;
            }else{
            list($ancho, $alto) = getimagesize($datos["imagen_estandar"]["tmp_name"]);
            $nuevoAncho =1010;
            $nuevoAlto = 800;
            $directorio = "../views/img/estandares";
            if($datos["imagen_estandar"]["type"] == "image/jpeg"){
                $rutaImagen = $directorio."/".md5($datos["imagen_estandar"]["tmp_name"]).".jpeg";
                $origen = imagecreatefromjpeg($datos["imagen_estandar"]["tmp_name"]);
                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            }
            if($datos["imagen_estandar"]["type"] == "image/png"){
                $rutaImagen = $directorio."/".md5($datos["imagen_estandar"]["name"]).".png";
                $origen = imagecreatefrompng($datos["imagen_estandar"]["tmp_name"]);
                $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);
            }
        }
			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Estandar_Actualizar @codigo = :codigo, @nombre = :nombre, @tipo = :tipo, @AreaList = :area, @ruta = :ruta,@id = :id_estandar";			
			// Prepara la consulta
			$stmt = $conn->prepare($sql);		
			// Asocia los valores a los parámetros
			$stmt->bindParam(":id_estandar", $datos["id_estandar"], PDO::PARAM_INT);
            $stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
			$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
			$stmt->bindParam(":tipo", $datos["tipo"], PDO::PARAM_INT);
			$stmt->bindParam(":ruta", $rutaImagen, PDO::PARAM_STR);
            $ids = [];
            foreach ($datos["area"] as $key => $value) {
                $ids[] = $value[0];
            }
            $idString = implode(",", $ids);
			$stmt->bindParam(":area", $idString, PDO::PARAM_STR);

			// Ejecuta el procedimiento almacenado
			$stmt->execute();	
			// Recupera el resultado
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

            if($result["resultado"] == 2){
                if(!is_null($datos["imagen_estandar"]))
                {
		           unlink($datos["rutaActual"]);

                        if($datos["imagen_estandar"]["type"] == "image/jpeg"){
                                    imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                                    imagejpeg($destino, $rutaImagen);
                                }
                        if($datos["imagen_estandar"]["type"] == "image/png"){
                            imagealphablending($destino, FALSE);
                            imagesavealpha($destino, TRUE);
                            imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                            imagepng($destino, $rutaImagen);
                        }
                }

            }
			
			return $result;
		} catch (PDOException $e) {
			die("Error en la consulta: " . $e->getMessage());
		}
		}
		static public function EliminarEstandarMdl($id_estandar,$rutaImagen) {
			try {
				$conn = Conexion::Conectar();
				// Define el nombre del procedimiento almacenado y los parámetros
				$sql = "EXEC Estandar_Eliminar @id = :id";			
				// Prepara la consulta
				$stmt = $conn->prepare($sql);		
				// Asocia los valores a los parámetros
				$stmt->bindParam(":id", $id_estandar, PDO::PARAM_INT);
				// Ejecuta el procedimiento almacenado
				$stmt->execute();		
				// Recupera el resultado
				$result = $stmt->fetch(PDO::FETCH_ASSOC);	

                if($result["resultado"] == 1){
                    if ($rutaImagen!='') {
                        unlink($rutaImagen);
                        }
                }
				return $result;
			} catch (PDOException $e) {
				die("Error en la consulta: " . $e->getMessage());
			}

		}



        static public function CargarEstandarMdl($datos) {
            try {
                $conn = Conexion::Conectar();
        
                // Define el nombre del procedimiento almacenado y los parámetros
                $sql = "EXEC Estandar_Cargar @estandar = :estandar, @area = :area";			
                // Prepara la consulta
                $stmt = $conn->prepare($sql);		
                // Asocia los valores a los parámetros
                $stmt->bindParam(":estandar", $datos["estandar"], PDO::PARAM_INT);
                $stmt->bindParam(":area", $datos["area"], PDO::PARAM_INT);
    
                // Ejecuta el procedimiento almacenado
                $stmt->execute();
                
                // Recupera el resultado
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
            } catch (PDOException $e) {
                die("Error en la consulta: " . $e->getMessage());
            }
        }

        static public function listarEstandaresCargadosMdl() {
            try {
                $conn = Conexion::Conectar();
        
                // Define el nombre del procedimiento almacenado y los parámetros
                $sql = "EXEC Listar_Estandares_Cargados";
                
                // Prepara la consulta
                $stmt = $conn->prepare($sql);
                            
                // Ejecuta el procedimiento almacenado
                $stmt->execute();
                
                // Recupera el resultado
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                die("Error en la consulta: " . $e->getMessage());
            }
        }


        static public function SubirProcesoMdl($id_proceso) {
            try {
                $conn = Conexion::Conectar();
        
                // Define el nombre del procedimiento almacenado y los parámetros
                $sql = "EXEC Estandar_SubirProceso @id_proceso = :id_proceso";			
                // Prepara la consulta
                $stmt = $conn->prepare($sql);		
                // Asocia los valores a los parámetros
                $stmt->bindParam(":id_proceso", $id_proceso, PDO::PARAM_INT);
                // Ejecuta el procedimiento almacenado
                $stmt->execute();
                
                // Recupera el resultado
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                die("Error en la consulta: " . $e->getMessage());
            }
        }

        
        static public function ValidarPersonalMdl($datos,$id_estandar) {

            try {
                $conn = Conexion::Conectar();
               // Recorre los datos y realiza la inserción en SQL Server

               if (isset($datos["datos"])) {
                foreach ($datos["datos"] as $item) {
                    if (isset($item["rut"])) {

                        // Define el nombre del procedimiento almacenado y los parámetros
                        $sql = "EXEC Estandar_Validar_Personal @rut = :rut, @id_estandar_proceso = :id_estandar";			
                        // Prepara la consulta
                        $stmt = $conn->prepare($sql);		
                        // Asocia los valores a los parámetros
                        $stmt->bindParam(":id_estandar", $id_estandar, PDO::PARAM_INT);
                        $stmt->bindParam(":rut", $item["rut"], PDO::PARAM_STR);
                        // Ejecuta el procedimiento almacenado
                        $stmt->execute();
                    }
                }
            }

                // Cierra la conexión a la base de datos
                $conn = null;

                // Envía una respuesta al cliente (puede ser un mensaje de éxito)
                return "ok";
            } catch (PDOException $e) {
                die("Error en la consulta: " . $e->getMessage());
            }
        }
        static public function PersonalValidadoMdl($id_proceso) {
            try {
                $conn = Conexion::Conectar();
        
                // Define el nombre del procedimiento almacenado y los parámetros
                $sql = "EXEC Estandar_PersonalValidado @id_proceso = :id_proceso";			
                // Prepara la consulta
                $stmt = $conn->prepare($sql);		
                // Asocia los valores a los parámetros
                $stmt->bindParam(":id_proceso", $id_proceso, PDO::PARAM_INT);
                // Ejecuta el procedimiento almacenado
                $stmt->execute();
                
                // Recupera el resultado
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                die("Error en la consulta: " . $e->getMessage());
            }
        }

        static public function GraficosCreadosEntrenadosMdl() {
            try {
                $conn = Conexion::Conectar();
        
                // Define el nombre del procedimiento almacenado y los parámetros
                $sql = "EXEC Estandares_Graficos_Creados_Entrenados";
                
                // Prepara la consulta
                $stmt = $conn->prepare($sql);
                            
                // Ejecuta el procedimiento almacenado
                $stmt->execute();
                
                // Recupera el resultado
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                die("Error en la consulta: " . $e->getMessage());
            }
        }
        static public function GraficosBarrasEntrenadosMdl($id_area) {
            try {
                $conn = Conexion::Conectar();
        
                // Define el nombre del procedimiento almacenado y los parámetros
                $sql = "EXEC Estandares_Graficos_Barras_Entrenados @id_area = :id_area";
                
                // Prepara la consulta
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":id_area", $id_area, PDO::PARAM_INT);     
                // Ejecuta el procedimiento almacenado
                $stmt->execute();
                
                // Recupera el resultado
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                die("Error en la consulta: " . $e->getMessage());
            }
        }
        static public function GraficosBarrasCreadosMdl($id_area) {
            try {
                $conn = Conexion::Conectar();
        
                // Define el nombre del procedimiento almacenado y los parámetros
                $sql = "EXEC Estandares_Graficos_Barras_Creados @id_area = :id_area";
                
                // Prepara la consulta
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":id_area", $id_area, PDO::PARAM_INT);      
                // Ejecuta el procedimiento almacenado
                $stmt->execute();
                
                // Recupera el resultado
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                die("Error en la consulta: " . $e->getMessage());
            }
        }
        static public function GraficosBarrasAreasCreadosMdl($id_area) {
            try {
                $conn = Conexion::Conectar();
        
                // Define el nombre del procedimiento almacenado y los parámetros
                $sql = "EXEC Estandares_Graficos_Barras_Areas_Creados @id_area = :id_area";
                
                // Prepara la consulta
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":id_area", $id_area, PDO::PARAM_INT);
                            
                // Ejecuta el procedimiento almacenado
                $stmt->execute();
                
                // Recupera el resultado
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                die("Error en la consulta: " . $e->getMessage());
            }
        }
        static public function GraficosBarrasAreasEntrenadosMdl($id_area) {
            try {
                $conn = Conexion::Conectar();
        
                // Define el nombre del procedimiento almacenado y los parámetros
                $sql = "EXEC Estandares_Graficos_Barras_Areas_Entrenados @id_area = :id_area";
                
                // Prepara la consulta
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":id_area", $id_area, PDO::PARAM_INT);
                            
                // Ejecuta el procedimiento almacenado
                $stmt->execute();
                
                // Recupera el resultado
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                die("Error en la consulta: " . $e->getMessage());
            }
        }
        static public function GraficoPiePorPilarMdl($id_area) {
            try {
                $conn = Conexion::Conectar();
        
                // Define el nombre del procedimiento almacenado y los parámetros
                $sql = "EXEC Estandares_Graficos_Pie_Pilar @id_area = :id_area";
                
                // Prepara la consulta
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(":id_area", $id_area, PDO::PARAM_INT); 
                // Ejecuta el procedimiento almacenado
                $stmt->execute();
                
                // Recupera el resultado
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                
                return $result;
            } catch (PDOException $e) {
                die("Error en la consulta: " . $e->getMessage());
            }
        }

}
?>