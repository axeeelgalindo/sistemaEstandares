<?php
require_once "conexion.php";
class ModeloEstandar
{

    static public function listarEstandaresMdl($planta_id = NULL)
    {
        try {
            $conn = Conexion::Conectar();

            // Define el nombre del procedimiento almacenado y los parámetros
            $sql = "EXEC Listar_Estandares @planta_id = :planta_id";

            // Prepara la consulta
            $stmt = $conn->prepare($sql);

            // parametro
            $stmt->bindValue(':planta_id', $planta_id, PDO::PARAM_INT);

            // Ejecuta el procedimiento almacenado
            $stmt->execute();

            // Recupera el resultado
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }
    static public function CrearEstandarMdl($datos)
    {
        // Directorio donde guardo las imágenes
        $uploadDir = __DIR__ . "/../views/img/estandares/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
            error_log("🎯 Created dir: $uploadDir");
        } else {
            error_log("🎯 Dir exists: $uploadDir");
        }

        // Preparo ruta para DB y FS
        $rutaImagenBD = null;
        $rutaImagenFS = null;
        if (isset($datos["imagen_estandar"]) && $datos["imagen_estandar"]["error"] !== UPLOAD_ERR_NO_FILE) {
            $tmp = $datos["imagen_estandar"]["tmp_name"];
            $type = $datos["imagen_estandar"]["type"];
            error_log("🎯 Recibí imagen: tmp_name=$tmp, type=$type");
            list($ancho, $alto) = @getimagesize($tmp);
            $nuevoAncho = 1010;
            $nuevoAlto = 800;
            $isJpeg = $type === "image/jpeg";
            $ext = $isJpeg ? ".jpeg" : ".png";

            $origen = $isJpeg ? imagecreatefromjpeg($tmp) : imagecreatefrompng($tmp);
            $destino = imagecreatetruecolor($nuevoAncho, $nuevoAlto);

            $filename = uniqid("est_", true) . $ext;
            $rutaImagenFS = $uploadDir . $filename;
            $rutaImagenBD = "../views/img/estandares/" . $filename;

            error_log("🎯 Will save image to FS=$rutaImagenFS  BD=$rutaImagenBD");
        } else {
            error_log("🎯 No se subió imagen (error={$datos['imagen_estandar']['error']})");
        }

        try {
            // Conecto y llamo al SP
            $conn = Conexion::Conectar();
            error_log("🎯 Llamando a SP Estandar_Crear con codigo={$datos['codigo']}, planta={$datos['planta_id']}");

            $sql = "EXEC Estandar_Crear 
                  @codigo    = :codigo, 
                  @nombre    = :nombre, 
                  @tipo      = :tipo, 
                  @AreaList  = :area, 
                  @ruta      = :ruta, 
                  @planta_id = :planta_id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
            $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":tipo", $datos["tipo"], PDO::PARAM_INT);
            $stmt->bindParam(":ruta", $rutaImagenBD, PDO::PARAM_STR);
            $stmt->bindParam(":planta_id", $datos["planta_id"], PDO::PARAM_INT);

            // Aquí logueo el string de áreas
            $idString = implode(",", $datos["area"] ?? []);
            error_log("🎯 AreaList para SP: '$idString'");
            $stmt->bindParam(":area", $idString, PDO::PARAM_STR);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("🎯 SP devolvió: " . json_encode($result));

            // Si el SP devolvió OK, guardo la imagen en disco
            if ($result["resultado"] == 1 && $rutaImagenFS) {
                imagecopyresized($destino, $origen, 0, 0, 0, 0, $nuevoAncho, $nuevoAlto, $ancho, $alto);
                if ($isJpeg) {
                    imagejpeg($destino, $rutaImagenFS);
                } else {
                    imagealphablending($destino, false);
                    imagesavealpha($destino, true);
                    imagepng($destino, $rutaImagenFS);
                }
                error_log("🎯 Imagen escrita: $rutaImagenFS");
            }

            return [
                "resultado" => (int) $result["resultado"],
                // opcional: enviar mensaje de error si SP devolvió 2 o fallo interno
                "mensaje" => $result["resultado"] != 1 ? ($result["mensaje"] ?? "Error en SP") : null
            ];

        } catch (PDOException $e) {
            error_log("❌ Excepción PDO en CrearEstandarMdl: " . $e->getMessage());
            return [
                "resultado" => 3,
                "mensaje" => "Error en la base de datos: " . $e->getMessage()
            ];
        }
    }


    static public function EditarEstandarMdl($id_estandar)
    {
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
    static public function ActualizarEstandarMdl($datos)
    {
        try {
            // 1) Preparar rutas y GD sólo si hay nueva imagen
            $hasNewImage = isset($datos["imagen_estandar"]) &&
                $datos["imagen_estandar"]["error"] !== UPLOAD_ERR_NO_FILE;
            $baseDirFS = __DIR__ . "/../views/img/estandares/";
            $rutaImagenBD = $datos["rutaActual"];    // ruta a BD vieja, por defecto
            $rutaImagenFS = null;

            if ($hasNewImage) {
                list($w, $h) = getimagesize($datos["imagen_estandar"]["tmp_name"]);
                $nw = 1010;
                $nh = 800;
                $ext = $datos["imagen_estandar"]["type"] === "image/jpeg" ? ".jpeg" : ".png";
                $src = $datos["imagen_estandar"]["tmp_name"];
                $origen = $ext === ".jpeg"
                    ? imagecreatefromjpeg($src)
                    : imagecreatefrompng($src);
                $destino = imagecreatetruecolor($nw, $nh);

                $filename = uniqid("est_", true) . $ext;
                $rutaImagenFS = $baseDirFS . $filename;
                $rutaImagenBD = "views/img/estandares/" . $filename;
            }

            // 2) Ejecutar SP
            $conn = Conexion::Conectar();
            $sql = "EXEC Estandar_Actualizar
                  @id        = :id,
                  @codigo    = :codigo,
                  @nombre    = :nombre,
                  @tipo      = :tipo,
                  @AreaList  = :area,
                  @ruta      = :ruta,
                  @planta_id = :planta_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id", $datos["id_estandar"], PDO::PARAM_INT);
            $stmt->bindParam(":codigo", $datos["codigo"], PDO::PARAM_STR);
            $stmt->bindParam(":nombre", $datos["nombre"], PDO::PARAM_STR);
            $stmt->bindParam(":tipo", $datos["tipo"], PDO::PARAM_INT);
            $stmt->bindParam(":ruta", $rutaImagenBD, PDO::PARAM_STR);
            $stmt->bindParam(":planta_id", $datos["planta_id"], PDO::PARAM_INT);

            $areaList = !empty($datos["area"]) && is_array($datos["area"])
                ? implode(",", $datos["area"])
                : "";
            $stmt->bindParam(":area", $areaList, PDO::PARAM_STR);

            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $code = (int) $result["resultado"];

            // 3) Si SP devolvió 2 (OK+imagen) o 1 (OK sin imagen), grabo/desplazo la imagen
            if (($code === 2 || $code === 1) && $hasNewImage && $rutaImagenFS) {
                // borrar la vieja en disco si existe
                $oldFS = __DIR__ . "/../" . $datos["rutaActual"];
                if (file_exists($oldFS)) {
                    unlink($oldFS);
                }
                // redimensionar y guardar
                imagecopyresized(
                    $destino,
                    $origen,
                    0,
                    0,
                    0,
                    0,
                    $nw,
                    $nh,
                    $w,
                    $h
                );
                if ($ext === ".jpeg") {
                    imagejpeg($destino, $rutaImagenFS);
                } else {
                    imagealphablending($destino, false);
                    imagesavealpha($destino, true);
                    imagepng($destino, $rutaImagenFS);
                }
            }

            // 4) Preparo mensaje sólo en errores (3=No existe, 4=Código duplica)
            $mensaje = null;
            if ($code === 3) {
                $mensaje = "Estandar no existe";
            } elseif ($code === 4) {
                $mensaje = "Código de Estandar ya existe";
            }

            return [
                "resultado" => $code,
                "mensaje" => $mensaje
            ];

        } catch (PDOException $e) {
            return [
                "resultado" => 5,
                "mensaje" => "Error BD: " . $e->getMessage()
            ];
        }
    }
    static public function EliminarEstandarMdl($id_estandar, $rutaImagen)
    {
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

            if ($result["resultado"] == 1) {
                if ($rutaImagen != '') {
                    unlink($rutaImagen);
                }
            }
            return $result;
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }
    static public function CargarEstandarMdl($datos)
    {
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
    static public function listarEstandaresCargadosMdl($planta_id)
    {
        try {
            $conn = Conexion::Conectar();
            $sql = "EXEC Listar_Estandares_Cargados @planta_id = :planta_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":planta_id", $planta_id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }



    static public function SubirProcesoMdl($id_proceso)
    {
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

    static public function ValidarPersonalMdl($datos, $id_estandar)
    {

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

    static public function RevertirPersonalMdl($datos, $id_estandar)
    {

        try {
            $conn = Conexion::Conectar();
            // Recorre los datos y realiza la inserción en SQL Server

            if (isset($datos["datos"])) {
                foreach ($datos["datos"] as $item) {
                    if (isset($item["rut"])) {

                        // Define el nombre del procedimiento almacenado y los parámetros
                        $sql = "EXEC Estandar_Revertir_Personal @rut = :rut, @id_estandar_proceso = :id_estandar";
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

    static public function PersonalValidadoMdl($id_proceso)
    {
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
    static public function GraficosCreadosEntrenadosMdl()
    {
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
    static public function GraficosCreadosPersonasMdl()
    {
        try {
            $conn = Conexion::Conectar();

            // Define el nombre del procedimiento almacenado y los parámetros
            $sql = "EXEC Estandares_Graficos_Creados_Personas";

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
    static public function GraficosBarrasEntrenadosMdl($id_area)
    {
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
    static public function GraficosBarrasPersonasMdl($id_area)
    {
        try {
            $conn = Conexion::Conectar();

            // Define el nombre del procedimiento almacenado y los parámetros
            $sql = "EXEC Estandares_Graficos_Barras_Personas @id_area = :id_area";

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
    static public function GraficosBarrasCreadosMdl($id_area)
    {
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
    static public function GraficosBarrasAreasCreadosMdl($id_area)
    {
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
    static public function GraficosBarrasAreasEntrenadosMdl($id_area)
    {
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
    static public function GraficosBarrasAreasEntrenadosPersonasMdl($id_area)
    {
        try {
            $conn = Conexion::Conectar();

            // Define el nombre del procedimiento almacenado y los parámetros
            $sql = "EXEC Estandares_Graficos_Barras_Areas_Personas @id_area = :id_area";

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
    static public function GraficoPiePorPilarMdl($id_area)
    {
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
    static public function GraficoPiePorPilarPersonasMdl($id_area)
    {
        try {
            $conn = Conexion::Conectar();

            // Define el nombre del procedimiento almacenado y los parámetros
            $sql = "EXEC Estandares_Graficos_Pie_Pilar_Personas @id_area = :id_area";

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
    static public function GraficoAnualPersonasMdl($id_area)
    {
        try {
            $conn = Conexion::Conectar();

            // Define el nombre del procedimiento almacenado y los parámetros
            $sql = "EXEC Estandares_Graficos_Barras_Personas @id_area = :id_area";

            // Prepara la consulta
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id_area", $id_area, PDO::PARAM_INT);
            // Ejecuta el procedimiento almacenado
            $stmt->execute();

            // Recupera el resultado
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }
    static public function GraficoAnualPersonasCreadasMdl($id_area)
    {
        try {
            $conn = Conexion::Conectar();

            // Define el nombre del procedimiento almacenado y los parámetros
            $sql = "EXEC Estandares_Graficos_Barras_Personas_Creados @id_area = :id_area";

            // Prepara la consulta
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id_area", $id_area, PDO::PARAM_INT);
            // Ejecuta el procedimiento almacenado
            $stmt->execute();

            // Recupera el resultado
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }
    static public function GraficoAnualTotalPersonasMdl($id_area)
    {
        try {
            $conn = Conexion::Conectar();

            // Define el nombre del procedimiento almacenado y los parámetros
            $sql = "EXEC Estandares_Graficos_Barras_Personas_Total @id_area = :id_area";

            // Prepara la consulta
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(":id_area", $id_area, PDO::PARAM_INT);
            // Ejecuta el procedimiento almacenado
            $stmt->execute();

            // Recupera el resultado
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);

            return $result;
        } catch (PDOException $e) {
            die("Error en la consulta: " . $e->getMessage());
        }
    }
    static public function GraficosBarrasAreasPersonasMdl($id_area)
    {
        try {
            $conn = Conexion::Conectar();

            // Define el nombre del procedimiento almacenado y los parámetros
            $sql = "EXEC Estandares_Graficos_Barras_Areas_Personas @id_area = :id_area";

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
    static public function GraficoBarraAreaPersonasIniciadas($id_area)
    {
        try {
            $conn = Conexion::Conectar();

            // Define el nombre del procedimiento almacenado y los parámetros
            $sql = "EXEC Estandares_Graficos_Barras_Areas_Personas_Iniciadas @id_area = :id_area";

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
    static public function GraficosBarrasAreasPersonasTotalMdl($id_area)
    {
        try {
            $conn = Conexion::Conectar();

            // Define el nombre del procedimiento almacenado y los parámetros
            $sql = "EXEC Estandares_Graficos_Barras_Areas_Personas_Total @id_area = :id_area";

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
}
