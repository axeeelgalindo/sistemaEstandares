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
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <div class="row m-1">
              <h3 class="col-md-7 col-sm-12 card-title">Listado de personas registradas</h3>
            </div>
            <div class="row m-1">
              <button class="col-md-2 col-sm-12 btn btn-sm btn-secondary mr-1 ml-1" data-toggle="modal" data-target="#modal-insertar-nuevo-personas2">
                <i class="far fa-edit"></i> + Agregar Personas
              </button>
              <button class="col-md-2 col-sm-12 btn btn-sm btn-primary btnAgregarPersonaExcel ml-1" style="background: #1C245A;">
                <i class="far fa-file-excel"></i> Cargar Archivo Excel
              </button>
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

          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>
                    Rut
                  </th>
                  <th>
                    Nombre
                  </th>
                  <th>
                    Apellido
                  </th>
                  <th>
                    Área base
                  </th>
                  <th>
                    Área secundaria
                  </th>
                  <th>
                    Estado
                  </th>
                  <th>
                    Fecha de registro
                  </th>
                  <th style="width: 20%;">
                    Acción
                  </th>
                </tr>
              </thead>
              <tbody>
                <?php
                $tabla1 = ModeloPersonas::listarPersonasMdl();
                $tabla2 = ModeloPersonas::listarPersonasActivasMdl();
                $tabla3 = ModeloPersonas::listarPersonasInactivasMdl();

                $tabla = $tabla1;

                foreach ($tabla as $key => $value) {
                  echo '
                    <tr>
                    <td>' . nl2br($value["rut"]) . '</td>
                    <td>' . nl2br($value["nombre"]) . '</td>
                    <td>' . nl2br($value["apellido"]) . '</td>
                    <td>' . nl2br($value["area"]) . '</td>
                    <td>' . nl2br($value["area secundaria"]) . '</td>
                    <td>' . nl2br($value["Estado"]) . '</td>
                    <td>' . nl2br($value["fecha_integracion"]) . '</td>      

                    <td width="100"> <button class="btn btn-sm btn-info btnEditarPersonas" idRut="' . $value["rut"] . '" data-toggle="modal" data-target="#modal-editar-personas">
                                        <i class="far fa-edit"></i> 
                                    </button>';
                  if ($value["Estado"] == 'Activo') {
                    echo '<button class="btn btn-sm btn-danger btnEliminarPersonas"  idRut="' . $value["rut"] . '">
                                        <i class="far fa-trash-alt"></i> 
                        </button>';
                  } else {
                    echo '                                
                                      <button class="btn btn-sm btn-success btnActivarPersonas"  idRut="' . $value["rut"] . '">
                                      <i class="far fa-solid fa-check"></i>
                      </button>';
                  }

                  echo '  </td>
                    </tr>
            ';
                }
                ?>

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

  <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->

<script>
  // Define sample table data
  var tabla1 = <?php echo json_encode(ModeloPersonas::listarPersonasMdl()); ?>;
  var tabla2 = <?php echo json_encode(ModeloPersonas::listarPersonasActivasMdl()); ?>;
  var tabla3 = <?php echo json_encode(ModeloPersonas::listarPersonasInactivasMdl()); ?>;
  $(document).ready(function() {
    $('#example1').DataTable({
        "paging": true, // Enable pagination
        "pageLength": 25 // Set number of items per page
    });
});
function updateTable(option) {
    var tableBody = document.getElementById('example1').getElementsByTagName('tbody')[0];
    tableBody.innerHTML = ''; // Clear existing table content
  
    var data;
    if (option === 'Todos') {
      data = tabla1;
    } else if (option === 'Activos') {
      data = tabla2;
    } else if (option === 'Inactivos') {
      data = tabla3;
    }
    
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
          ${item.Estado === 'Activo' ? 
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
  }
  
</script>