<?php
require_once "conexion.php";
Class ModeloNivelusuario {
	static public function listarNivelUsuarioMdl() {
		try {
			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC NivelUsuario_Listar";
			
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
	static public function listarNivelEstadosMdl() {
		try {
			$conn = Conexion::Conectar();
	
			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC NivelUsuario_Estados_Listar";
			
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

	static public function CrearNivelusuarioMdl($datos) {
		try {
					$conn = Conexion::Conectar();
			
					// Define el nombre del procedimiento almacenado y los parámetros
					$sql = "EXEC Nivelusuario_Crear @nombre = :nombre, @email= :email, @password =:password, @nivel =:nivel";			
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
		
	static public function EditarNivelusuarioMdl($id_usuario) {
				try {
					$conn = Conexion::Conectar();
			
					// Define el nombre del procedimiento almacenado y los parámetros
					$sql = "EXEC Nivelusuario_Editar_Detalle @id = :id";			
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
			static public function ActualizarNivelusuarioMdl($datos) {	
				try {

					$conn = Conexion::Conectar();
			
					// Define el nombre del procedimiento almacenado y los parámetros
					$sql = "EXEC Nivelusuario_Actualizar @nombre = :nombre, @id = :id_usuario, @email= :email, @password =:password, @nivel =:nivel";			
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
				static public function DeshabilitarNivelusuarioMdl($id_usuario) {
					try {
						$conn = Conexion::Conectar();
						// Define el nombre del procedimiento almacenado y los parámetros
						$sql = "EXEC Nivelusuario_Deshabilitar @id = :id";			
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
				static public function listarNivelusuarioIdMdl($mail) {
					try {
						$conn = Conexion::Conectar();
				
						// Define el nombre del procedimiento almacenado y los parámetros
						$sql = "EXEC Nivelusuario_Listar_Id @email= :email";
						
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
						$sql = "EXEC Nivelusuario_Actualizar_Perfil @nombre = :nombre, @id = :id_usuario, @email = :email, @password = :password";			
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



                    static public function listarMenuNivelUsuarioMdl($id) {
                        try {
                            $conn = Conexion::Conectar();
                    
                            // Define el nombre del procedimiento almacenado y los parámetros
                            $sql = "EXEC NivelUsuario_Listar_Menu @id = :id";
                            
                            // Prepara la consulta
                            $stmt = $conn->prepare($sql);
					    	$stmt->bindParam(":id", $id, PDO::PARAM_INT);
                                        
                            // Ejecuta el procedimiento almacenado
                            $stmt->execute();
                            
                            // Recupera el resultado
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            return $result;
                        } catch (PDOException $e) {
                            die("Error en la consulta: " . $e->getMessage());
                        }
                    }

                    static public function ActualizarEstadoNivelMdl($datos) {	
                        try {
        
                            $conn = Conexion::Conectar();
                    
                            // Define el nombre del procedimiento almacenado y los parámetros
                            $sql = "EXEC NivelUsuario_Actualizar_Nivel @id_menu_estandar = :id_menu_estandar, @valor_select = :valor_select";			
                            // Prepara la consulta
                            $stmt = $conn->prepare($sql);		
                            // Asocia los valores a los parámetros
                            $stmt->bindParam(":id_menu_estandar", $datos["id_menu_estandar"], PDO::PARAM_INT);
                            $stmt->bindParam(":valor_select", $datos["valor_select"], PDO::PARAM_INT);
        
                            // Ejecuta el procedimiento almacenado
                            $stmt->execute();	
                            // Recupera el resultado
                            $result = $stmt->fetch(PDO::FETCH_ASSOC);               
                            return $result;
                        } catch (PDOException $e) {
                            die("Error en la consulta: " . $e->getMessage());
                        }
                        }

                        static public function listarMenuNivelMdl($email) {	
                            try {
            
                                $conn = Conexion::Conectar();
                        
                                // Define el nombre del procedimiento almacenado y los parámetros
                                $sql = "EXEC NivelUsuario_Listar_Nivel_Menu @email = :email";			
                                // Prepara la consulta
                                $stmt = $conn->prepare($sql);		
                                // Asocia los valores a los parámetros
                                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
                     
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