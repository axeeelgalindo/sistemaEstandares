<?php
class Conexion {
	static public function Conectar() {
		//$serverName = "DESKTOP-2R85171\SQLEXPRESS"; // Reemplaza con el nombre del servidor SQL Server
	//	$serverName = "CB-NUC-01\SQLEXPRESS"; // Reemplaza con el nombre del servidor SQL Server     
		$databaseName = "db_estandares"; // Reemplaza con el nombre de tu base de datos
		$username = "sa"; // Reemplaza con el nombre de usuario de SQL Server
		$password = "smarteyes2024"; // Reemplaza con la contraseña de SQL Server
		$serverName = "192.168.0.128";
		
		try {
			$conn = new PDO("sqlsrv:Server=$serverName;Database=$databaseName", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$conn->setAttribute(PDO::SQLSRV_ATTR_ENCODING, PDO::SQLSRV_ENCODING_UTF8); // Para UTF-8
			
			return $conn;
		} catch (PDOException $e) {
			die("Error de conexión: " . $e->getMessage());
		}
	}
	

}




?>