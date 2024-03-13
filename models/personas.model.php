<?php
require_once "conexion.php";
Class ModeloPersonas {
	static public function listarPersonasMdl() {
		try {
			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Listar_Personas_ACT";
			
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
	static public function CrearPersonasMdl($datos) {
		try {
			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Persona_Crear @rut = :rut, @nombre = :nombre, @apellido = :apellido, @area = :area, @area_secundaria = :area_secundaria";			
			// Prepara la consulta
			$stmt = $conn->prepare($sql);		
			// Asocia los valores a los parámetros
			$stmt->bindParam(":rut", $datos["rut"], PDO::PARAM_STR);
			$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
			$stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
			$stmt->bindParam(":area", $datos["area"], PDO::PARAM_INT);
			$stmt->bindParam(":area_secundaria", $datos["area_secundaria"], PDO::PARAM_INT);

			// Ejecuta el procedimiento almacenado
			$stmt->execute();
			
			// Recupera el resultado
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			return $result;
		} catch (PDOException $e) {
			die("Error en la consulta: " . $e->getMessage());
		}
	}

	static public function EditarPersonasMdl($id_personas) {
		try {
			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Persona_Editar_Detalle @rut = :rut";			
			// Prepara la consulta
			$stmt = $conn->prepare($sql);		
			// Asocia los valores a los parámetros
			$stmt->bindParam(":rut", $id_personas, PDO::PARAM_STR);
			// Ejecuta el procedimiento almacenado
			$stmt->execute();
			
			// Recupera el resultado
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			return $result;
		} catch (PDOException $e) {
			die("Error en la consulta: " . $e->getMessage());
		}
	}

	static public function ActualizarPersonasMdl($datos) {	

		try {
			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Persona_Actualizar @rut = :rut, @nombre = :nombre, @apellido = :apellido, @area = :area, @area_secundaria = :area_secundaria";			
			// Prepara la consulta
			$stmt = $conn->prepare($sql);		
			// Asocia los valores a los parámetros
			$stmt->bindParam(":rut", $datos["id_personas"], PDO::PARAM_STR);
			$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
			$stmt->bindParam(":apellido", $datos["apellido"], PDO::PARAM_STR);
			$stmt->bindParam(":area", $datos["area"], PDO::PARAM_INT);
			$stmt->bindParam(":area_secundaria", $datos["area_secundaria"], PDO::PARAM_INT);

			// Ejecuta el procedimiento almacenado
			$stmt->execute();
			
			// Recupera el resultado
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			return $result;
		} catch (PDOException $e) {
			die("Error en la consulta: " . $e->getMessage());
		}
		}

		static public function EliminarPersonasMdl($id_personas) {
			try {
				$conn = Conexion::Conectar();
		
				// Define el nombre del procedimiento almacenado y los parámetros
				$sql = "EXEC Persona_Eliminar @rut = :rut";			
				// Prepara la consulta
				$stmt = $conn->prepare($sql);		
				// Asocia los valores a los parámetros
				$stmt->bindParam(":rut", $id_personas, PDO::PARAM_STR);
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