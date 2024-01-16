<?php
require_once "conexion.php";
Class ModeloUsuario {
	static public function listarUsuarioMdl() {
		try {
			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Listar_Usuario";
			
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

	static public function CrearUsuarioMdl($datos) {
		try {
					$conn = Conexion::Conectar();
			
					// Define el nombre del procedimiento almacenado y los parámetros
					$sql = "EXEC Usuario_Crear @nombre = :nombre, @email= :email, @password =:password, @nivel =:nivel";			
					// Prepara la consulta
					$stmt = $conn->prepare($sql);		
					// Asocia los valores a los parámetros
					$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
					$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
					$stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
					$stmt->bindParam(":nivel", $datos["nivel"], PDO::PARAM_INT);

					// Enlaza el parámetro del tipo de tabla
					$stmt->execute();
					
					// Recupera el resultado
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					return $result;
				} catch (PDOException $e) {
					die("Error en la consulta: " . $e->getMessage());
				}
			}
		
	static public function EditarUsuarioMdl($id_usuario) {
				try {
					$conn = Conexion::Conectar();
			
					// Define el nombre del procedimiento almacenado y los parámetros
					$sql = "EXEC Usuario_Editar_Detalle @id = :id";			
					// Prepara la consulta
					$stmt = $conn->prepare($sql);		
					// Asocia los valores a los parámetros
					$stmt->bindParam(":id", $id_usuario, PDO::PARAM_INT);
					// Ejecuta el procedimiento almacenado
					$stmt->execute();
					
					// Recupera el resultado
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					
					return $result;
				} catch (PDOException $e) {
					die("Error en la consulta: " . $e->getMessage());
				}
			}
			static public function ActualizarUsuarioMdl($datos) {	
				try {

					$conn = Conexion::Conectar();
			
					// Define el nombre del procedimiento almacenado y los parámetros
					$sql = "EXEC Usuario_Actualizar @nombre = :nombre, @id = :id_usuario, @email= :email, @password =:password, @nivel =:nivel";			
					// Prepara la consulta
					$stmt = $conn->prepare($sql);		
					// Asocia los valores a los parámetros
					$stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
					$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
			     	$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
					$stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
					$stmt->bindParam(":nivel", $datos["nivel"], PDO::PARAM_INT);

					// Ejecuta el procedimiento almacenado
					$stmt->execute();	
					// Recupera el resultado
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					
					return $result;
				} catch (PDOException $e) {
					die("Error en la consulta: " . $e->getMessage());
				}
				}
				static public function DeshabilitarUsuarioMdl($id_usuario) {
					try {
						$conn = Conexion::Conectar();
						// Define el nombre del procedimiento almacenado y los parámetros
						$sql = "EXEC Usuario_Deshabilitar @id = :id";			
						// Prepara la consulta
						$stmt = $conn->prepare($sql);		
						// Asocia los valores a los parámetros
						$stmt->bindParam(":id", $id_usuario, PDO::PARAM_INT);
						// Ejecuta el procedimiento almacenado
						$stmt->execute();		
						// Recupera el resultado
						$result = $stmt->fetch(PDO::FETCH_ASSOC);	

						return $result;
					} catch (PDOException $e) {
						die("Error en la consulta: " . $e->getMessage());
					}
		
				}
				static public function listarUsuarioIdMdl($mail) {
					try {
						$conn = Conexion::Conectar();
				
						// Define el nombre del procedimiento almacenado y los parámetros
						$sql = "EXEC Usuario_Listar_Id @email= :email";
						
						// Prepara la consulta
						$stmt = $conn->prepare($sql);
						$stmt->bindParam(":email", $mail, PDO::PARAM_STR);
					
						// Ejecuta el procedimiento almacenado
						$stmt->execute();
						
						// Recupera el resultado
						$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
						
						return $result;
					} catch (PDOException $e) {
						die("Error en la consulta: " . $e->getMessage());
					}
				}

				static public function ActualizarPerfilMdl($datos) {	
					try {
	
						$conn = Conexion::Conectar();
				
						// Define el nombre del procedimiento almacenado y los parámetros
						$sql = "EXEC Usuario_Actualizar_Perfil @nombre = :nombre, @id = :id_usuario, @email = :email, @password = :password";			
						// Prepara la consulta
						$stmt = $conn->prepare($sql);		
						// Asocia los valores a los parámetros
						$stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
						$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
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

					static public function listarNivelMdl() {
						try {
							$conn = Conexion::Conectar();
					
							// Define el nombre del procedimiento almacenado y los parámetros
							$sql = "EXEC Usuario_Listar_Niveles";
							
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
		
}
?>