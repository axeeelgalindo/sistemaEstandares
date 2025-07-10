<?php
//ajaxCargarArchivoExcel2.php
require '../vendor/autoload.php';
require_once "../models/conexion.php";
session_start();
ob_start(); // ← Después del session_start

error_reporting(0); // oculta warnings, notices, etc.
ini_set('display_errors', 0);


if (isset($_FILES['archivo_excel']['name'])) {
    $archivo = $_FILES['archivo_excel']['tmp_name'];

    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($archivo);
    $worksheet = $spreadsheet->getActiveSheet();
    $foundColumns = [];
    $firstRowFound = false;
    $highestRow = $worksheet->getHighestRow();
    $insertedRows = 0;
    $RegisterRows = 0;

    $erroredRows = 0;
    $totalColumn = 0;

    if ($_POST["tipoOperacion"] == "insertarPersonas" || $_POST["tipoOperacion"] == "cambiarArea") {
        $requiredColumns = [
            'A1' => 'rut',
            'B1' => 'nombre',
            'C1' => 'apellido',
            'D1' => 'area base',
            'E1' => 'area secundaria',
        ];
        $filasFallasTotal = [
            'rut' => '',
            'nombre' => '',
            'apellido' => '',
            'area base' => '',
            'area secundaria' => '',
            'error' => ''
        ];
        $totalColumn = 5;
    } else {
        $requiredColumns = [
            'A1' => 'rut',
        ];
        $filasFallasTotal = [
            'rut' => '',
            'error' => ''
        ];
        $totalColumn = 1;
    }

    $filasAreaNoExiste = [];
    $filasRutExistente = [];

    foreach ($requiredColumns as $cellCoordinate => $requiredHeader) {
        $cell = $worksheet->getCell($cellCoordinate);
        $header = trim(strtolower($cell->getValue()));

        //  echo "encabezado: ".$header;
        if ($header !== $requiredHeader) {

            //  echo " columna exc: ".$header." requerido: ".$requiredHeader;
            // Si ocurre un error, establece un código de estado HTTP personalizado (por ejemplo, 500 para errores internos del servidor).
            http_response_code(500);
            echo json_encode(['error' => "Error en formato excel, la columna $requiredHeader debe estar en la celda $cellCoordinate."]);
            exit;
        }
    }
    $isHeaderRow = true; // Variable para identificar si es la fila de encabezados

    $responseData = [
        'progress' => 0,
        'insertedRows' => 0,
        'erroredRows' => 0
    ];

    $filasFallasGeneral = [];


    $TotalFilas = 0;
    for ($rowIndex = 2; $rowIndex <= $highestRow; $rowIndex++) {
        $rowData = [];
        for ($colIndex = 1; $colIndex <= $totalColumn; $colIndex++) { // Recorrer de la columna A a la Z
            $cell = $worksheet->getCellByColumnAndRow($colIndex, $rowIndex);
            $rowData[] = $cell->getValue();
        }
        if (array_filter($rowData)) {
            if (!$firstRowFound) {
                $firstRowFound = true;
            }
            if ($firstRowFound) {
                $TotalFilas++;
            }
        }
    }

    //if (ob_get_level() == 0)
    //    ob_start();


    for ($rowIndex = 1; $rowIndex <= $highestRow; $rowIndex++) {

        if ($isHeaderRow) {
            // Saltar la primera fila (encabezados)
            $isHeaderRow = false;
            continue;
        }
        $rowData = [];
        for ($colIndex = 1; $colIndex <= $totalColumn; $colIndex++) { // Recorrer de la columna A a la Z
            $cell = $worksheet->getCellByColumnAndRow($colIndex, $rowIndex);
            $rowData[] = $cell->getValue();
        }

        // Verificar si la fila está vacía (todos los valores son nulos o en blanco)
        if (array_filter($rowData)) {
            if (!$firstRowFound) {
                $firstRowFound = true;
                if (empty($rowData[0])) {
                    echo json_encode(['progress' => 0, 'insertedRows' => 0, 'erroredRows' => 0, 'message' => 'Los encabezados deben comenzar en la primera columna de la primera fila.']);
                    exit;
                }
            }

            if ($firstRowFound) {
                $filasTotalCargadas = [];
                $conn = Conexion::Conectar();
                $planta_id = $_SESSION['planta_id'] ?? null;

                if ($_POST["tipoOperacion"] == "insertarPersonas") {
                    $sql = "EXEC InsertarDatosExcel_Iterativo @rut = ?, @nombre = ?, @apellido = ?, @area_base = ?, @area_secundaria = ?, @planta_id = ?";

                } elseif ($_POST["tipoOperacion"] == "desactivarPersonas") {
                    $sql = "EXEC DesactivarDatosExcel_Iterativo @rut = ?";
                } elseif ($_POST["tipoOperacion"] == "activarPersonas") {
                    $sql = "EXEC ActivarDatosExcel_Iterativo @rut = ?";
                } elseif ($_POST["tipoOperacion"] == "cambiarArea") {
                    $sql = "EXEC ModificarAreaDatosExcel_Iterativo @rut = ?, @nombre = ?, @apellido = ?, @area_base = ?, @area_secundaria = ?";
                }
                $params = $rowData;

                $stmt = $conn->prepare($sql);

                $stmt->bindParam(1, $params[0], PDO::PARAM_STR);
                if ($_POST["tipoOperacion"] == "insertarPersonas" || $_POST["tipoOperacion"] == "cambiarArea") {
                    $stmt->bindParam(2, $params[1], PDO::PARAM_STR);
                    $stmt->bindParam(3, $params[2], PDO::PARAM_STR);
                    $stmt->bindParam(4, $params[3], PDO::PARAM_STR);
                    $stmt->bindParam(5, $params[4], PDO::PARAM_STR);
                    $stmt->bindParam(6, $planta_id, PDO::PARAM_INT);
                }

                // Ejecuta el procedimiento almacenado
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $RegisterRows++;

                if ($result["resultado"] != 1) {
                    $erroredRows++;

                    // Convertir todos los datos a string para evitar [object Object]
                    $filasFallasTotal['rut'] = (string) $rowData[0];

                    if ($_POST["tipoOperacion"] == "insertarPersonas" || $_POST["tipoOperacion"] == "cambiarArea") {
                        $filasFallasTotal['nombre'] = (string) $rowData[1];
                        $filasFallasTotal['apellido'] = (string) $rowData[2];
                        $filasFallasTotal['area base'] = (string) $rowData[3];
                        $filasFallasTotal['area secundaria'] = (string) $rowData[4];
                    }

                    // Mensaje de error según resultado
                    switch ($result["resultado"]) {
                        case 2:
                            $filasFallasTotal['error'] = 'area base no existe';
                            break;
                        case 3:
                            $filasFallasTotal['error'] = 'rut ya existe';
                            break;
                        case 4:
                            $filasFallasTotal['error'] = 'rut no existe';
                            break;
                        case 5:
                            $filasFallasTotal['error'] = 'persona en estado desactivado';
                            break;
                        case 6:
                            $filasFallasTotal['error'] = 'persona en estado activado';
                            break;
                        case 7:
                            $filasFallasTotal['error'] = 'area secundaria no existe';
                            break;
                        default:
                            $filasFallasTotal['error'] = 'error desconocido';
                            break;
                    }

                    $filasFallasGeneral[] = $filasFallasTotal;
                } else {
                    $insertedRows++; //Ok
                }


                // Actualiza los datos de progreso
                $responseData['progress'] = ($RegisterRows / $TotalFilas) * 100;
                $responseData['insertedRows'] = $insertedRows;
                $responseData['erroredRows'] = $erroredRows;
                $filasTotalCargadas[] = $responseData;
                //echo json_encode($responseData);

                // echo "<br> Line to show.";
                //echo str_pad('', 4096) . "\n";
                ob_flush();
                flush();
                usleep(100000);
            }
        }
    }
    //21183343-k
    // 21183333-k

    //ingreso multiple
    // 21183323-k
    //21183723-k
    if (ob_get_length()) {
        ob_clean();
    }
    header_remove();
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode([
        'progress' => 100,
        'insertedRows' => $insertedRows,
        'erroredRows' => $erroredRows,
        'errores' => $filasFallasGeneral
    ]);
    exit;
}

?>