
<?php
require_once "conexion.php";
Class ModeloLogin {

	static public function mdlValidarLogin($datos) {
		try {
			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Validar_Usuario @email = :email, @password = :password";
			
			// Prepara la consulta
			$stmt = $conn->prepare($sql);
			
			// Asocia los valores a los parámetros
			$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
			$stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
			
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