<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Reportes</h1>
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
                <div class="card card-default">
              <div class="card-header">
                <h3 class="card-title">Filtros</h3>
              </div>
              <div class="card-body">
                <!-- Date -->
     

                <!-- /.form group -->
                <!-- Date range -->
                <div class="form-group">
                  <label>Date range:</label>

                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <i class="far fa-calendar-alt"></i>
                      </span>
                    </div>
                    <input type="text" class="form-control float-right" id="reservation">
                  </div>
                  <!-- /.input group -->
                </div>
                <!-- /.form group -->
                <!-- /.form group -->
              </div>
                <div class="card-footer">

                </div>
              <!-- /.card-body -->
            </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
                             <thead>
                  <tr>
                      <th >
                          Rut
                      </th>
                      <th>
                         Nombre
                      </th>
                      <th >
                       Apellido
                      </th>
                      <th >
                      Acción
                      </th>
                  </tr>
              </thead>
                  <tbody>
                  <?php
          $tabla = ModeloPersonas::listarPersonasMdl($_SESSION['planta_id']);
          foreach ($tabla as $key => $value) {
            echo '
                    <tr>
                    <td>'.nl2br($value["rut"]).'</td>
                    <td>'.nl2br($value["nombre"]).'</td>
                    <td>'.nl2br($value["apellido"]).'</td>

                    <td width="100"> <button class="btn btn-sm btn-info btnEditarSecciones" idSeccion="'.$value["rut"].'" data-toggle="modal" data-target="#modal-editar-secciones">
                                        <i class="far fa-edit"></i> Editar
                                    </button>
                        <button class="btn btn-sm btn-danger btnEliminarSecciones"  idSeccion="'.$value["rut"].'">
                                        <i class="far fa-trash-alt"></i> Eliminar
                        </button>
                        </td>
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


<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="plugins/uplot/uPlot.iife.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

<!-- Page specific script -->
<script>


