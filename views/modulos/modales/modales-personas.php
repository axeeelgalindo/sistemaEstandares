  <!-- Modal para ingresar personas -->

  <div class="modal fade" id="modal-insertar-nuevo-personas2" style="overflow-y: scroll;">
    <div class="modal-dialog modal-l">
      <div class="modal-content">
        <form id="insertarPersona" enctype="multipart/form-data">

          <div class="modal-header">
            <h4 class="modal-title">Ingresar Persona</h4>
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

                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="sizing-addon1">Rut</span>
                          </div>

                          <input type="text" class="form-control" placeholder="Rut a validar, ej: 11111111-1" name="rut" id="rut" aria-describedby="sizing-addon1" id="txt_rut" required>
                          <div class="card-footer">
                            <p>Ingrese rut sin puntos y con digito verificados. Ejemplo: 18345678-2</p>
                          </div>
                        </div>


                        <div class="form-group">
                          <label for="exampleInputEmail1">Nombre:</label>
                          <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese nombre" required>

                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Apellido:</label>
                          <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Ingrese apellido" required>
                        </div>
                        <div class="form-group">
                          <label>Área Base:</label>
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
                        <div class="form-group">
                          <label>Área Secundaria:</label>
                          <select class="form-control" name="areaSecundaria" required>

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
                      <input type="hidden" name="tipoOperacion" value="insertarPersonas">
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
            <button type="submit" class="btn btn-primary">Agregar</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>


  <div class="modal fade" id="modal-main" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Modal 1</h1>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        </div>
        <div class="modal-body">
          Seleccione que acción desea realizar.
        </div>
        <div class="modal-footer">
          <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Ingresar personal</button>
          <button class="btn btn-danger" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal">Desactivar personal</button>

        </div>
      </div>
    </div>
  </div>


  <!-- Modal para ingresar excel -->

  <div class="modal fade" id="modal-insertar-nuevo-personas" style="overflow-y: scroll;">
    <div class="modal-dialog modal-l">
      <div class="modal-content">
        <form id="excelForm" enctype="multipart/form-data">

          <div class="modal-header">
            <h4 class="modal-title titulo"></h4>
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
                          <div class="card card-primary card-outline">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo2">
                              <div class="card-header">
                                <h4 class="card-title w-100">
                                  Información importante sobre formato de archivo excel.
                                </h4>
                              </div>
                            </a>
                            <div id="collapseTwo2" class="collapse" data-parent="#accordion1">
                              <div class="card-body">
                                <ul>
                                  <li>Formato de columnas.
                                    <ul>
                                      <li>A1: nombre de columna "rut"</li>
                                      <li>B1: nombre de columna "nombre"</li>
                                      <li>C1: nombre de columna "apellido"</li>
                                      <li>D1: nombre de columna "area base"</li>
                                      <li>E1: nombre de columna "area secundaria"</li>
                                    </ul>
                                  </li>
                                  <li>El rut debe ir sin puntos y con guion verificador. Ejemplo: 19345235-3</li>
                                  <li>En la columna "area base" o "area secundaria" debe ingresar solo áreas existentes en el sistema, la columna "area secundaria" no será obligación ingresar datos.</li>
                                  <li class="TextoRutValidar"></li>
                                  <li>Al finalizar la carga del archivo excel en la parte inferior se mostrará si existe algún error en la integración de la fila</li>
                                </ul>
                              </div>
                            </div>
                          </div>
                          <label for="exampleInputEmail1">Agregue archivo Excel (Formato .XLSX):</label>
                          <div class="col-12" id="accordion1">
                          </div>
                          <input type="file" class="form-control" name="archivo_excel" id="archivo_excel" accept=".xlsx" required>
                        </div>

                        <div class="progress">
                          <div class="progress-bar progress-bar-striped progress-bar-animated" id="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>

                    </a>
                    <div id="collapseTwo2" class="collapse" data-parent="#accordion1">
                        <div class="card-body">
                        <ul>
                  <li>Formato de columnas.
                    <ul id="FormatLi">
                    </ul>
                  </li>
                  <li>El rut debe ir sin puntos y con guion verificador. Ejemplo: 19345235-3</li>
                  <li class="TextoAreaValidar"></li>
                  <li class="TextoRutValidar"></li>
                  <li>Al finalizar la carga del archivo excel en la parte inferior se mostrará si existe algún error en la integración de la fila</li>
                </ul>

                        </div>
                        <label id="total-inserted"></label>
                        <label id="total-errored"></label>
                        <div class="col-12" id="accordion">
                          <div class="card card-danger card-outline">
                            <a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
                              <div class="card-header">
                                <h4 class="card-title w-100">
                                  Errores
                                </h4>
                              </div>
                            </a>
                            <div id="collapseTwo" class="collapse" data-parent="#accordion">
                              <div class="card-body">
                                <p id="DetallesErrores" style="font-size:12px;">
                                </p>
                              </div>
                            </div>
                          </div>

                        </div>
                      </div>
                      <!-- /.card-body -->
                      <input type="hidden" name="tipoOperacion" value="">
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
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- Modal para editar personas -->

  <div class="modal fade" id="modal-editar-personas" style="overflow-y: scroll;">
    <div class="modal-dialog modal-l">
      <div class="modal-content">
        <form id="formu-editar-personas" enctype="multipart/form-data">
          <div class="modal-header">
            <h4 class="modal-title">Editar personas</h4>
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
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="sizing-addon1">Rut</span>
                          </div>
                          <input type="text" class="form-control" placeholder="Rut a validar, ej: 11111111-1" name="rut" id="rut" aria-describedby="sizing-addon1" disabled>
                          <div class="card-footer">
                            <p>Ingrese rut sin puntos y con digito verificados. Ejemplo: 18345678-2</p>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Nombre:</label>
                          <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese nombre" required>
                        </div>
                        <div class="form-group">
                          <label for="exampleInputEmail1">Apellido:</label>
                          <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Ingrese apellido" required>
                        </div>
                        <div class="form-group">
                          <label>Área Base:</label>
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
                        <div class="form-group">
                          <label>Área Secundaria:</label>
                          <select class="form-control" name="areaSecundaria">

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
                    </div>
                    <!-- /.card -->
                    <!--/.col (right) -->
                  </div>
                  <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
          </div>
          <div class="modal-footer justify-content-between">
            <input type="hidden" name="id_personas">

            <input type="hidden" name="tipoOperacion" value="actualizarPersonas">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modal-activos-filtrar" style="overflow-y: scroll;">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form id="filtrarPersonas" enctype="multipart/form-data">

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
                  <div class="col-md-12">
                    <div class="card card-primary">
                      <div class="card-body">
                        <div class="form-group">
                          <label>Área:</label>
                          <div class="col-sm-12">
                            <!-- checkbox -->
                            <div class="row">
                              <input class="checkbox" type="checkbox" id="checkActivo">
                              <label for="checkActivo"> Activo </label>
                            </div>
                            <div class="row">
                              <input class="checkbox" type="checkbox" id="checkInactivo">
                              <label for="checkInactivo"> Inactivo </label>
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
            <button type="button" class="btn btn-success" data-dismiss="modal" id="applyFilter2"><i class="far fa-solid fa-check"></i> Filtrar</button>
          </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>