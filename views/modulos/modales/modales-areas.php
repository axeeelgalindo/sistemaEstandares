<!-- Modal para ingresar personas -->

<div class="modal fade" id="modal-insertar-nuevo-area" style="overflow-y: scroll;">
  <div class="modal-dialog modal-l">
    <div class="modal-content">
      <form id="insertarArea" enctype="multipart/form-data">

        <div class="modal-header">
          <h4 class="modal-title">Ingresar Área</h4>
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
                        <label for="exampleInputEmail1">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese Área"
                          required>
                      </div>
                      <div class="form-group">
                        <label for="planta_id">Planta:</label>
                        <select class="form-control" name="planta_id" id="planta_id">
                          <option value="">Sin planta</option>
                          <?php
                          require_once "models/planta.model.php";
                          $plantas = ModeloPlanta::listarPlantas();
                          foreach ($plantas as $p) {
                            echo '<option value="' . $p["id"] . '">' . $p["nombre"] . '</option>';
                          }
                          ?>
                        </select>
                      </div>

                    </div>
                    <!-- /.card-body -->
                    <input type="hidden" name="tipoOperacion" value="insertarArea">
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

<div class="modal fade" id="modal-editar-area" style="overflow-y: scroll;">
  <div class="modal-dialog modal-l">
    <div class="modal-content">
      <form id="editarArea" enctype="multipart/form-data">

        <div class="modal-header">
          <h4 class="modal-title">Editar Área</h4>
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
                        <label for="exampleInputEmail1">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese área"
                          required>
                      </div>
                      <div class="form-group">
                        <label for="planta_idEditar">Planta:</label>
                        <select class="form-control" name="planta_id"      id="planta_idEditar">
                          <option value="">Sin planta</option>
                          <?php
                          require_once "models/planta.model.php";
                          $plantas = ModeloPlanta::listarPlantas();
                          foreach ($plantas as $p) {
                            echo '<option value="' . $p["id"] . '">' . $p["nombre"] . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <!-- /.card-body -->
                    <input type="hidden" name="tipoOperacion" value="actualizarArea">
                    <input type="hidden" name="id_area">
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