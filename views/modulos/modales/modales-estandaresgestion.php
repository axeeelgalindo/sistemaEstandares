  <!-- Modal para ingresar personas -->
  <div class="modal fade" id="modal-cargar-estandar" style="overflow-y: scroll;">
    <div class="modal-dialog modal-l">
      <div class="modal-content">
        <form id="cargarEstandar" enctype="multipart/form-data">

          <div class="modal-header">
            <h4 class="modal-title">Cargar Estandar</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <section class="content">
              <div class="container-fluid">
                <div class="row">
                  <!-- left column -->
                  <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                      <!-- /.card-header -->
                      <!-- form start -->
                      <div class="card-body">

                        <div class="form-group">
                          <label>Seleecione Estandar:</label>
                          <select class="form-control" name="estandar" required>

                            <?php
                            $tabla2 = ModeloEstandar::listarEstandaresMdl();
                            echo '<option value="" selected>Seleccione Tipo de estandar</option>';
                            foreach ($tabla2 as $key => $value) {
                              echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                            }
                            echo '</select>';

                            ?>
                        </div>
                        <div class="form-group">
                          <label>Área:</label>
                          <select class="form-control" name="area" required>

                            <?php
                            $tabla2 = ModeloArea::listarAreaMdl();
                            echo '<option value="" selected>Seleccione Área</option>';
                            foreach ($tabla2 as $key => $value) {
                              echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                            }
                            echo '</select>';

                            ?>
                        </div>
                      </div>
                      <!-- /.card-body -->
                      <input type="hidden" name="tipoOperacion" value="cargarEstandar">
                    </div>
                    <!-- /.card -->
                    <!--/.col (right) -->
                  </div>
                  <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Cargar</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <style>
    .TablaPersonal tr:hover {
      cursor: pointer;
    }
  </style>

  <div class="modal fade" id="modal-cargar-personas-estandar" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form id="editarEstandar" enctype="multipart/form-data">

          <div class="modal-header">
            <h4 class="modal-title">Validar personal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <section class="content">
              <div class="container-fluid">
                <div class="row">
                  <!-- left column -->
                  <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                      <!-- /.card-header -->
                      <!-- form start -->
                      <div class="card-body">

                        <table class="TablaPersonal table table-striped projects" id="TablaPersonal">
                          <thead>
                            <tr>
                              <th></th>
                              <th>Nombre</th>
                              <th>Rut</th>
                            </tr>
                          </thead>
                          <tbody>
                            <!-- Agrega más filas de datos de personas aquí -->
                          </tbody>
                        </table>

                      </div>
                      <!-- /.card-body -->
                      <input type="hidden" name="tipoOperacion" value="actualizarEstandar">
                      <input type="hidden" name="rutaActual">
                      <input type="hidden" name="id_estandar">
                    </div>
                    <!-- /.card -->
                    <!--/.col (right) -->
                  </div>
                  <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-success" id="btnValidar"><i class="far fa-solid fa-check"></i> Validar</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modal-cargados-personas-estandar" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form id="editarEstandar" enctype="multipart/form-data">

          <div class="modal-header">
            <h4 class="modal-title">Personal validado</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <section class="content">
              <div class="container-fluid">
                <div class="row">
                  <!-- left column -->
                  <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                      <!-- /.card-header -->
                      <!-- form start -->
                      <div class="card-body">
                        <div class="container">

                          <table class="table TablaPersonalValidado" id="TablaPersonalValidado">
                            <thead>
                              <tr>
                                <th></th>
                                <th>Nombre</th>
                                <th>Rut</th>
                              </tr>
                            </thead>
                            <tbody>
                              <!-- Agrega más filas de datos de personas aquí -->
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <!-- /.card-body -->
                      <input type="hidden" name="tipoOperacion" value="actualizarEstandar">
                      <input type="hidden" name="rutaActual">
                      <input type="hidden" name="id_estandar">
                    </div>
                    <!-- /.card -->
                    <!--/.col (right) -->
                  </div>
                  <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
          </div>
          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-danger" id="btnRevertir"><i class="fas fa-user-times"></i> Revertir Entrenamiento</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modal-cargados-filtrar" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form id="editarEstandar" enctype="multipart/form-data">

          <div class="modal-header">
            <h4 class="modal-title">Filtrar por áreas</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <section class="content">
              <div class="container-fluid">
                <div class="row">
                  <!-- left column -->
                  <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                      <!-- /.card-header -->
                      <!-- form start -->
                      <div class="card-body">
                        <div class="form-group">
                          <label>Área:</label>
                          <div class="col-sm-12">
                            <!-- checkbox -->
                            <div class="form-group">
                              <?php
                              $tabla6 = ModeloArea::listarAreaMdl();
                              usort($tabla6, function ($a, $b) {
                                return strcmp($a["nombre"], $b["nombre"]);
                              });
                              foreach ($tabla6 as $key => $value) {
                                echo '<div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="area[]" id="customCheckbox1' . $value["id"] . '" value="' . $value["id"] . '">
                        <label for="customCheckbox1' . $value["id"] . '" class="custom-control-label">' . $value["nombre"] . '</label>
                      </div>';
                              }
                              ?>
                            </div>
                          </div>
                        </div>

                        <div class="form-group">
                          <label>Tipos de Estandar:</label>
                          <div class="col-sm-12">
                            <!-- checkbox -->
                            <div class="form-group">
                              <?php
                              $tabla5 = ModeloArea::listarTipoMdl();
                              usort($tabla5, function ($a, $b) {
                                return strcmp($a["tipo"], $b["tipo"]);
                              });
                              foreach ($tabla5 as $key => $value) {
                                echo '<div class="custom-control custom-checkbox">
                        <input class="custom-control-input" type="checkbox" name="tipo[]" id="customCheckbox2' . $value["id"] . '" value="' . $value["id"] . '">
                        <label for="customCheckbox2' . $value["id"] . '" class="custom-control-label">' . $value["tipo"] . '</label>
                      </div>';
                              }
                              ?>
                            </div>
                          </div>
                        </div>
                      </div>
                      <!-- /.card-body -->
                      <input type="hidden" name="tipoOperacion" value="actualizarEstandar">
                      <input type="hidden" name="rutaActual">
                      <input type="hidden" name="id_estandar">
                    </div>
                    <!-- /.card -->
                    <!--/.col (right) -->
                  </div>
                  <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
          </div>

          <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-success" data-dismiss="modal" id="applyFilter"><i class="far fa-solid fa-check"></i> Filtrar</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>



  <div class="modal fade" id="modal-ver-estandar" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form id="formu-ver-estandares" enctype="multipart/form-data">
          <div class="modal-header">
            <h4 class="modal-title">Imagen Estandar</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <section class="content">
              <div class="container-fluid">
                <div class="row">
                  <!-- left column -->
                  <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                      <!-- /.card-header -->
                      <!-- form start -->
                      <div class="card-body">

                        <div class="form-group">

                          <img id="imagenMostrada" class="img-fluid pad" src="" alt="Imagen" />
                        </div>
                      </div>
                    </div>
                    <!-- /.card -->
                    <!--/.col (right) -->
                  </div>
                  <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
        </form>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
  </div>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

     <script>
        $(document).ready(function() {
                var miTabla = $('#TablaPersonal').DataTable();
                var miTablaValidado = $('#TablaPersonalValidado').DataTable();         

                // Agregar la casilla "Seleccionar Todo" en la parte superior de la tabla
                $('#TablaPersonal thead th:first').html('<input type="checkbox" id="seleccionarTodo">');
                $('#TablaPersonalValidado thead th:first').html('<input type="checkbox" id="seleccionarTodoValidado">');
            
                // Manejar el evento "change" del checkbox "Seleccionar Todo"
                $('#seleccionarTodo').on('change', function() {
                    var seleccionarTodo = this.checked;

        // Obtener las filas visibles después del filtro
        var filasFiltradas = miTabla.rows({
          search: 'applied'
        });

                    // Cambiar el estado de las casillas de verificación en las filas filtradas
                    filasFiltradas.nodes().to$().find(':checkbox.seleccionar').prop('checked', seleccionarTodo);
                });
                      // Manejar el evento "change" del checkbox "Seleccionar Todo"
                $('#seleccionarTodoValidado').on('change', function() {
                    var seleccionarTodoValidado = this.checked;

                    // Obtener las filas visibles después del filtro
                    var filasFiltradasValidado = miTablaValidado.rows({ search: 'applied' });

                    // Cambiar el estado de las casillas de verificación en las filas filtradas
                    filasFiltradasValidado.nodes().to$().find(':checkbox.seleccionar').prop('checked', seleccionarTodoValidado);
                });

                 // Manejar el clic en las filas
            $('#TablaPersonal tbody').on('click', 'tr', function() {
                var checkbox = $(':checkbox.seleccionar', this);
                checkbox.prop('checked', !checkbox.prop('checked'));
            });

              // Manejar el clic en las filas
              $('#TablaPersonalValidado tbody').on('click', 'tr', function() {
                var checkbox = $(':checkbox.seleccionar', this);
                checkbox.prop('checked', !checkbox.prop('checked'));
            });
            });         
     </script>
