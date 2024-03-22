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
                $tabla = ModeloPersonas::listarPersonasMdl();

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
  // Function to update table based on selected option
  // Function to update table based on selected option
  function updateTable(option) {
    var table = $('#example1').DataTable();
    if ($.fn.DataTable.isDataTable('#example1')) {
      table.destroy();
    }
    $('#example1').DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
      "pageLength": 25 // Configura el número de registros por página
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');;

    table = $('#example1').DataTable();
    // Update table with new data
    table.column(5).search(option === 'Activos' ? '^Activo$' : option === 'Inactivos' ? '^Inactivo$' : '', true, false, 'cs').draw();

  }
</script>