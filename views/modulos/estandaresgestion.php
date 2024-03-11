<!-- Content Header (Page header) -->
<section class="content-header">
  <link rel="stylesheet" type="text/css" href="https://cFn.datatables.net/1.10.24/css/jquery.dataTables.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Entrenamiento</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Inicio</a></li>
          <li class="breadcrumb-item active">Entrenamiento</li>
        </ol>
      </div>
    </div>
  </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">

  <!-- Default box -->
  <div class="card">
    <div class="card-header">
      <h2 class="col-10  card-title">Listado de estandares</h2>
  <!--     <button class="btn btn-sm btn-primary btnFiltrar col-2" data-toggle="modal" data-target="#modal-cargados-filtrar">
        <i class="fas fa-filter">Filtrar</i>
      </button> -->
    </div>
    <div class="card-body ">
      <table id="example1" class="table table-striped projects">
        <thead>
          <tr>
            <th style="width: 5%">
              Código
            </th>
            <th style="width: 20%">
              Tipo de Estandar
            </th>
            <th style="width: 10%">
              Nombre Estandar
            </th>
            <th style="width: 10%">
              Area
            </th>
   <th style="width: 10%">
              Fecha Entrenamiento
            </th>
            <th>
              Total Personas / Entrenadas
            </th>
            <th>
              % De Entrenamiento
            </th>
            <th style="width: 20%">
              Acción
            </th>
          </tr>
        </thead>

        <tbody>
          <?php
          $tabla = ModeloEstandar::listarEstandaresCargadosMdl();
          foreach ($tabla as $key => $value) {
            echo '
            <tr>
            <td>' . nl2br($value["codigo"]) . ' </td>
            <td>' . nl2br($value["tipo"]) . '</td>
            <td> ' . nl2br($value["nombre"]) . '</td>
            <td>' . nl2br($value["area"]) . '</td>
                   <td>' . nl2br($value["fecha_inicio"]) . '</td>
            <td>' . nl2br($value["total_personas"]) . '/' . nl2br($value["total_personas_entrenadas"]) . ' </td>
                      <td class="project_progress">
                          <div class="progress progress-sm">
                              <div class="progress-bar bg-green" role="progressbar" aria-valuenow="' . nl2br($value["porcentaje_entrenado"]) . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . nl2br($value["porcentaje_entrenado"]) . '%">
                              </div>
                          </div>
                          <small>
                          ' . nl2br($value["porcentaje_entrenado"]) . '% Entrenado
                          </small>
                      </td>
          
                      <td class="project-actions text-right">
                      <button class="btn btn-sm btn-default btnVerEstandar" Url="' . $value["url_pdf"] . '" data-toggle="modal" data-target="#modal-ver-estandar">
            <i class="far fa-solid fa-eye"> </i> 
            </button>   
                          <button class="btn btn-sm btn-primary btnSubirEstandar" IdProceso="'.$value["id"].'" data-toggle="modal" data-target="#modal-cargar-personas-estandar">
                          <i class="fas fa-rocket"></i> Entrenar
                          </button>
                          <button class="btn btn-success btn-sm btnEstandarValidado" IdProceso="'.$value["id"].'"  data-toggle="modal" data-target="#modal-cargados-personas-estandar"   >
                          <i class="fas fa-user-check"></i> Entrenados               
                          </button>
                      </td>
                  </tr>     
                 ';
          } ?>
        </tbody>
      </table>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</section>
<!-- /.content -->
<!-- jQuery -->

<script>
  $(document).ready(function() {
    // Listener para el botón de aplicar filtro
    $('#applyFilter').click(function() {
      // Recoge los checkboxes seleccionados
      var selectedAreas = $('input[name="area[]"]:checked').map(function() {
        return $(this).next('label').text(); // Obtiene el nombre de los datos Area
      }).get();

      var selectedTypes = $('input[name="tipo[]"]:checked').map(function() {
        return $(this).next('label').text(); // Obtiene el nombre de los datos Tipo de Estandar
      }).get();

      //Vacía la tabla antes de reiniciarla
      var table = $('#example1').DataTable();
      if ($.fn.DataTable.isDataTable('#example1')) {
        table.destroy();
      }

      // Inicializa la tabla
      $('#example1').DataTable({
        "paging": false,
        "ordering": true,
        "info": false,
        "searching": true
      });

      table = $('#example1').DataTable();

      // Aplica los filtros con los datos recogidos
      table.column(3).search(selectedAreas.join('|'), true, false).draw();
      table.column(1).search(selectedTypes.join('|'), true, false).draw();
    });
  });
</script>
