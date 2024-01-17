<?php
require_once "conexion.php";
Class ModeloUnidad {
	static public function listarUnidadMdl() {
		try {
			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Listar_Unidad";
			
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
	static public function CrearUnidadMdl($datos) {
		try {
					$conn = Conexion::Conectar();
			
					// Define el nombre del procedimiento almacenado y los parámetros
					$sql = "EXEC Unidad_Crear @nombre = :nombre";			
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
		
	static public function EditarUnidadMdl($id_Unidad) {
				try {
					$conn = Conexion::Conectar();
			
					// Define el nombre del procedimiento almacenado y los parámetros
					$sql = "EXEC Unidad_Editar_Detalle @id = :id";			
					// Prepara la consulta
					$stmt = $conn->prepare($sql);		
					// Asocia los valores a los parámetros
					$stmt->bindParam(":id", $id_Unidad, PDO::PARAM_INT);
					// Ejecuta el procedimiento almacenado
					$stmt->execute();
					
					// Recupera el resultado
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					
					return $result;
				} catch (PDOException $e) {
					die("Error en la consulta: " . $e->getMessage());
				}
			}
			static public function ActualizarUnidadMdl($datos) {	
				try {

					$conn = Conexion::Conectar();
			
					// Define el nombre del procedimiento almacenado y los parámetros
					$sql = "EXEC Unidad_Actualizar @nombre = :nombre, @id = :id_unidad";			
					// Prepara la consulta
					$stmt = $conn->prepare($sql);		
					// Asocia los valores a los parámetros
					$stmt->bindParam(":id_unidad", $datos["id_unidad"], PDO::PARAM_INT);
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
				static public function EliminarUnidadMdl($id_Unidad) {
					try {
						$conn = Conexion::Conectar();
						// Define el nombre del procedimiento almacenado y los parámetros
						$sql = "EXEC Unidad_Eliminar @id = :id";			
						// Prepara la consulta
						$stmt = $conn->prepare($sql);		
						// Asocia los valores a los parámetros
						$stmt->bindParam(":id", $id_Unidad, PDO::PARAM_INT);
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