<?php
require_once "conexion.php";
Class ModeloArea {
	static public function listarAreaMdl() {
		try {
			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Listar_Area";
			
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
	static public function listarTipoMdl() {
		try {
			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Listar_Tipo";
			
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

	static public function CrearAreaMdl($datos) {
		try {
					$conn = Conexion::Conectar();
			
					// Define el nombre del procedimiento almacenado y los parámetros
					$sql = "EXEC Area_Crear @nombre = :nombre";			
					// Prepara la consulta
					$stmt = $conn->prepare($sql);		
					// Asocia los valores a los parámetros
					$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
					// Enlaza el parámetro del tipo de tabla
					$stmt->execute();
					
					// Recupera el resultado
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					return $result;
				} catch (PDOException $e) {
					die("Error en la consulta: " . $e->getMessage());
				}
			}
		
	static public function EditarAreaMdl($id_area) {
				try {
					$conn = Conexion::Conectar();
			
					// Define el nombre del procedimiento almacenado y los parámetros
					$sql = "EXEC Area_Editar_Detalle @id = :id";			
					// Prepara la consulta
					$stmt = $conn->prepare($sql);		
					// Asocia los valores a los parámetros
					$stmt->bindParam(":id", $id_area, PDO::PARAM_INT);
					// Ejecuta el procedimiento almacenado
					$stmt->execute();
					
					// Recupera el resultado
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					
					return $result;
				} catch (PDOException $e) {
					die("Error en la consulta: " . $e->getMessage());
				}
			}
			static public function ActualizarAreaMdl($datos) {	
				try {

					$conn = Conexion::Conectar();
			
					// Define el nombre del procedimiento almacenado y los parámetros
					$sql = "EXEC Area_Actualizar @nombre = :nombre, @id = :id_area";			
					// Prepara la consulta
					$stmt = $conn->prepare($sql);		
					// Asocia los valores a los parámetros
					$stmt->bindParam(":id_area", $datos["id_area"], PDO::PARAM_INT);
					$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);


					// Ejecuta el procedimiento almacenado
					$stmt->execute();	
					// Recupera el resultado
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					
					return $result;
				} catch (PDOException $e) {
					die("Error en la consulta: " . $e->getMessage());
				}
				}
				static public function EliminarAreaMdl($id_area) {
					try {
						$conn = Conexion::Conectar();
						// Define el nombre del procedimiento almacenado y los parámetros
						$sql = "EXEC Area_Eliminar @id = :id";			
						// Prepara la consulta
						$stmt = $conn->prepare($sql);		
						// Asocia los valores a los parámetros
						$stmt->bindParam(":id", $id_area, PDO::PARAM_INT);
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