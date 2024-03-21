<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Personas</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Personas</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Inicio</a></li>
          </ol>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <div class="row mb-2">
                <div class="col-md-5 mr-2 card-title">
                  <h3 class="card-title">Listado de personas registradas</h3>
                </div>
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle ml-2" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Filtrar
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" onclick="updateTable('Todos')">Todos</a>
                    <a class="dropdown-item" href="#" onclick="updateTable('Activos')">Activos</a>
                    <a class="dropdown-item" href="#" onclick="updateTable('Inactivos')">Inactivos</a>
                  </div>
                </div>
              </div>
              <div class="row">
                <button class="col-md-5 btn btn-sm btn-secondary mr-2" data-toggle="modal" data-target="#modal-insertar-nuevo-personas2">
                  <i class="far fa-edit"></i> + Agregar Personas
                </button>
                <button class="col-md-5 btn btn-sm btn-primary btnAgregarPersonaExcel ml-2" style="background: #1C245A;">
                  <i class="far fa-file-excel"></i> Cargar Archivo Excel
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="buscarInactivos" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Rut</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Área base</th>
                    <th>Área secundaria</th>
                    <th>Estado</th>
                    <th>Fecha de registro</th>
                    <th>Acción</th>
                  </tr>
                </thead>
                <tbody id="tabla-body">
                  <!-- Table body content will be dynamically updated -->
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
    </div>
  </section>

  <!-- Bootstrap JS and jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <script>
    // Define sample table data
    var tabla1 = <?php echo json_encode(ModeloPersonas::listarPersonasMdl()); ?>;
    var tabla2 = <?php echo json_encode(ModeloPersonas::listarPersonasActivasMdl()); ?>;
    var tabla3 = <?php echo json_encode(ModeloPersonas::listarPersonasInactivasMdl()); ?>;

    // Function to update table based on selected option
    function updateTable(option) {
  var tableBody = document.getElementById('tabla-body');
  tableBody.innerHTML = ''; // Clear existing table content

  var data;
  if (option === 'Todos') {
    data = tabla1;
  } else if (option === 'Activos') {
    data = tabla2;
  } else if (option === 'Inactivos') {
    data = tabla3;
  }

  // Check if data is available
  if (data && data.length > 0) {
    // Update table with new data
    data.forEach(function(item) {
      var row = document.createElement('tr');
      row.innerHTML = `
        <td>${item.rut}</td>
        <td>${item.nombre}</td>
        <td>${item.apellido}</td>
        <td>${item.area}</td>
        <td>${item.area_secundaria !== undefined ? item.area_secundaria : ''}</td>
        <td>${item.Estado}</td>
        <td>${item.fecha_integracion}</td>
        <td>
          <button class="btn btn-sm btn-info btnEditarPersonas" idRut="${item.rut}" data-toggle="modal" data-target="#modal-editar-personas">
            <i class="far fa-edit"></i>
          </button>
          ${item.estado === 'Activo' ? 
            `<button class="btn btn-sm btn-danger btnEliminarPersonas" idRut="${item.rut}">
              <i class="far fa-trash-alt"></i>
            </button>` :
            `<button class="btn btn-sm btn-success btnActivarPersonas" idRut="${item.rut}">
              <i class="far fa-solid fa-check"></i>
            </button>`
          }
        </td>
      `;
      tableBody.appendChild(row);
    });
  } else {
    // Display a message when no data is available
    tableBody.innerHTML = '<tr><td colspan="8">No data available</td></tr>';
  }
}



    // Initially load tabla1 (Todos) when the page finishes loading
    document.addEventListener('DOMContentLoaded', function() {
      updateTable('Todos');
    });
  </script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>