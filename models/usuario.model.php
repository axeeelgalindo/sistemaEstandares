<?php
require_once "conexion.php";
class ModeloUsuario
{
	static public function listarUsuarioMdl()
	{
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
	static public function listarTipoMdl()
	{
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

	static public function listarSupervisoresMdl($planta_id)
	{
		try {
			$sql = "
                SELECT nombre
                FROM usuarios
                WHERE nivel_usuario = 3
                  AND activo        = 1
                  AND planta_id    = :planta_id
                ORDER BY nombre
            ";
			$stmt = Conexion::conectar()->prepare($sql);
			$stmt->bindParam(':planta_id', $planta_id, PDO::PARAM_INT);
			$stmt->execute();
			return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

		} catch (PDOException $e) {
			error_log('Error en listarSupervisoresMdl:  . $e->getMessage()');
			return [];
		} finally {
			$stmt = null;
		}
	}

	static public function CrearUsuarioMdl($datos)
	{
		try {
			$conn = Conexion::Conectar();

			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Usuario_Crear @nombre = :nombre, @email= :email, @password =:password, @nivel =:nivel, @planta_id =:planta_id";
			// Prepara la consulta
			$stmt = $conn->prepare($sql);
			// Asocia los valores a los parámetros
			$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
			$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
			$stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
			$stmt->bindParam(":nivel", $datos["nivel"], PDO::PARAM_INT);
			$stmt->bindParam(":planta_id", $datos["planta_id"], PDO::PARAM_INT);


			// Enlaza el parámetro del tipo de tabla
			$stmt->execute();

			// Recupera el resultado
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			return $result;
		} catch (PDOException $e) {
			die("Error en la consulta: " . $e->getMessage());
		}
	}

	static public function EditarUsuarioMdl($id_usuario)
	{
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
	static public function ActualizarUsuarioMdl($datos)
	{
		try {

			$conn = Conexion::Conectar();

			// Define el nombre del procedimiento almacenado y los parámetros
			$sql = "EXEC Usuario_Actualizar @nombre = :nombre, @id = :id_usuario, @email= :email, @password =:password, @nivel =:nivel, @planta_id =:planta_id";
			// Prepara la consulta
			$stmt = $conn->prepare($sql);
			// Asocia los valores a los parámetros
			$stmt->bindParam(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
			$stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
			$stmt->bindParam(":email", $datos["email"], PDO::PARAM_STR);
			$stmt->bindParam(":password", $datos["password"], PDO::PARAM_STR);
			$stmt->bindParam(":nivel", $datos["nivel"], PDO::PARAM_INT);
			if ($datos["planta_id"] === null) {
				$stmt->bindValue(":planta_id", null, PDO::PARAM_NULL);
			} else {
				$stmt->bindValue(":planta_id", $datos["planta_id"], PDO::PARAM_INT);
			}

			// Ejecuta el procedimiento almacenado
			$stmt->execute();
			// Recupera el resultado
			$result = $stmt->fetch(PDO::FETCH_ASSOC);

			return $result;
		} catch (PDOException $e) {
			die("Error en la consulta: " . $e->getMessage());
		}
	}
	static public function DeshabilitarUsuarioMdl($id_usuario)
	{
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
	static public function listarUsuarioIdMdl($mail)
	{
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

	static public function ActualizarPerfilMdl($datos)
	{
		try {
			$conn = Conexion::Conectar();

			// 1) Normalizamos los valores que pueden quedar en NULL
			if (empty($datos['password'])) {
				// si password viene vacío, lo pasamos a NULL
				$datos['password'] = null;
			}
			if (!isset($datos['planta_id']) || $datos['planta_id'] === '') {
				// si planta_id no viene o está vacío, lo pasamos a NULL
				$datos['planta_id'] = null;
			}

			// 2) Preparamos la llamada al SP
			$sql = "EXEC Usuario_Actualizar_Perfil
                    @id         = :id_usuario,
                    @nombre     = :nombre,
                    @email      = :email,
                    @password   = :password,
                    @planta_id  = :planta_id";

			$stmt = $conn->prepare($sql);

			// 3) Vinculamos los parámetros obligatorios
			$stmt->bindValue(":id_usuario", $datos["id_usuario"], PDO::PARAM_INT);
			$stmt->bindValue(":nombre", $datos["nombre"], PDO::PARAM_STR);
			$stmt->bindValue(":email", $datos["email"], PDO::PARAM_STR);

			// 4) Vinculamos password (puede ser NULL)
			if ($datos["password"] === null) {
				$stmt->bindValue(":password", null, PDO::PARAM_NULL);
			} else {
				$stmt->bindValue(":password", $datos["password"], PDO::PARAM_STR);
			}

			// 5) Vinculamos planta_id (puede ser NULL)
			if ($datos["planta_id"] === null) {
				$stmt->bindValue(":planta_id", null, PDO::PARAM_NULL);
			} else {
				$stmt->bindValue(":planta_id", $datos["planta_id"], PDO::PARAM_INT);
			}

			// 6) Ejecutamos y devolvemos resultado
			$stmt->execute();
			return $stmt->fetch(PDO::FETCH_ASSOC);

		} catch (PDOException $e) {
			die("Error en la consulta: " . $e->getMessage());
		}
	}



	static public function listarNivelMdl()
	{
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