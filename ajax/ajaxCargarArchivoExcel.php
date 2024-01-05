<?php
require '../vendor/autoload.php'; // Reemplaza esto con la ubicación de la biblioteca PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_FILES['archivo_excel']['name'])) {
    $archivo = $_FILES['archivo_excel']['tmp_name'];
    $response = array();
    // Carga el archivo Excel
    $spreadsheet = IOFactory::load($archivo);
    // Selecciona la hoja de trabajo
    $sheet = $spreadsheet->getActiveSheet();

    // Obtener las filas y columnas máximas de la hoja
    $maxRows = $sheet->getHighestRow();
    $maxCols = $sheet->getHighestColumn();

    // Convierte la letra de columna máxima a un índice numérico
    $maxColIndex = PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($maxCols);

    for ($row = 1; $row <= $maxRows; $row++) {
        $datosFila = array();
          for ($colIndex = 1; $colIndex <= $maxColIndex; $colIndex++) {
            $cellValue = $sheet->getCellByColumnAndRow($colIndex, $row)->getValue();
            $datosFila[] = $cellValue;
        }
        $datosCompletos[] = $datosFila;
    }

    // tienes el arreglo $datosCompletos con todos los datos del archivo Excel.
    $datosAreas = array(); // Un nuevo arreglo para almacenar los datos de la posición 4.
    $datosSinArea = array(); // Un arreglo para almacenar los datos sin área.

    foreach ($datosCompletos as $fila) {
        $datosAreas[] = $fila[3]; // Suponiendo que la posición 4 corresponde al índice 3 (PHP usa índices basados en 0).
    }

    // Ahora, $datosAreas contiene todos los datos de la posición 4.

    // Conectarse a la base de datos SQL Server
    $serverName = "DESKTOP-2R85171\SQLEXPRESS";
            $connectionOptions = array(
                "Database" => "db_estandares",
                "Uid" => "sa",
                "PWD" => "sa"
            );

    $conn = sqlsrv_connect($serverName, $connectionOptions);
    if (!$conn) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Consultar la tabla "areas" en la base de datos para verificar si los datos existen
    $sql = "SELECT id, nombre FROM areas WHERE nombre IN ('" . implode("','", $datosAreas) . "')";

    $stmt = sqlsrv_query($conn, $sql);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Crear un arreglo asociativo para mapear los nombres de áreas a sus IDs correspondientes
    $areasExistentes = array();
    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $areasExistentes[$row['nombre']] = $row['id'];
    }

/*  echo "<pre>";,mj
var_dump($areasExistentes);
echo "<pre>"; */

    // Iterar nuevamente por los datos originales y actualizar la posición 4 con el nombre del área desde el mapeo
    foreach ($datosCompletos as $fila) {
        $nombreArea = $fila[3]; // Suponiendo que la posición 4 corresponde al índice 3.
        if (!isset($areasExistentes[$nombreArea])) {
            // Si el nombre del área no existe en el mapeo, agrega la fila completa al arreglo de datos sin área
            $datosSinArea[] = $fila;
        }
    }

    // Ahora, $datosSinArea contiene los datos sin área y puedes imprimirlos
    foreach ($datosSinArea as $filaSinArea) {
        // Imprime o realiza cualquier acción que necesites con los datos sin área
        print_r($filaSinArea);
    }


        // Leer los encabezados de las columnas desde la primera fila
    for ($col = 'A'; $col <= 'I'; $col++) {
        $header = $sheet->getCell($col . '1')->getValue();
        $headers[$col] = $header;
    }
    // Verifica si las tres columnas requeridas existen
    $columnNames = array('rut','nombre', 'apellido', 'area');
   // $headerRow = $sheet->getCellByColumnAndRow(1, 1)->getValue();
    // Obtener todas las celdas de la fila de encabezados
    $headerRowCells = $sheet->getRowIterator(1)->current()->getCellIterator();
   // var_dump($headerRowCells);
    // Inicializar un arreglo para almacenar los nombres de las columnas en minúsculas
    $headerNames = array();

    // Recorrer todas las celdas de encabezado
    foreach ($headerRowCells as $cell) {
        // Obtener el valor de la celda y convertirlo a minúsculas
        $headerName = strtolower($cell->getValue());
        $headerNames[] = $headerName;
    }
   // var_dump($headerNames);

    // Verificar si los nombres de columna coinciden con los valores en $columnNames
    if (count(array_diff($columnNames, $headerNames)) !== 0) {
        $response['error'] = 'El archivo Excel debe contener las columnas:Rut, Nombre, Apellido, Área.';
    } else {
         // Ruta del archivo temporal
          $rutaArchivo = $archivo;
          //$rutaArchivo = $csvFileName;

            $conn = sqlsrv_connect($serverName, $connectionOptions);
            if (!$conn) {
                die(print_r(sqlsrv_errors(), true));
            }
            // Definir el parámetro de salida
            $filasAfectadas = 0;
         //   echo $rutaArchivo;
            $sql = "{CALL InsertarDatosDesdeExcel (?,?)}"; // Llamada al procedimiento almacenado
            $params = array(
            array($rutaArchivo, SQLSRV_PARAM_IN),
            array(&$filasAfectadas, SQLSRV_PARAM_OUT)); // Parámetro de salida

            $stmt = sqlsrv_query($conn, $sql, $params);

        if ($stmt !== false) {
            // La llamada al procedimiento se realizó con éxito
          //  echo "Número de filas afectadas: " . $filasAfectadas;
          $response['success'] = 1;
        } else {
            // Hubo un error en la llamada al procedimiento almacenado
            $errorInfo = sqlsrv_errors(SQLSRV_ERR_ERRORS);
              
           $error2;
            foreach ($errorInfo as $error) {
      //         echo "SQLSTATE: " . $error['SQLSTATE'] . "<br />";
        //        echo "Code: " . $error['code'] . "<br />";
               // echo "Message: " . $error['message'] . "<br />"; 
                $error2 = "Message: " . $error['SQLSTATE'] . " Code:".$error['code']."";
            } 
           $response['error'] = "Problemas en archivo Excel, verifique filas, " .$error2 ;

        }
          // Cierra la conexión
          sqlsrv_close($conn);
    }
    echo json_encode($response);
}
?>
