<!-- Modal para ingresar personas -->

<div class="modal fade" id="modal-insertar-nuevo-usuario" style="overflow-y: scroll;">
  <div class="modal-dialog modal-l">
    <div class="modal-content">
      <form id="insertarUsuario" enctype="multipart/form-data">

        <div class="modal-header">
          <h4 class="modal-title">Ingresar Usuario</h4>
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
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese nombre"
                          required>
                      </div>

                      <div class="form-group">
                        <label for="exampleInputEmail1">Email:</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Ingrese email"
                          required>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contraseña:</label>
                        <div class="password-container">
                          <input type="password" class="form-control" name="password" id="password1"
                            placeholder="Ingrese password" required>
                          <i class="toggle-password fas fa-eye" id="togglePassword1"></i>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Repita Contraseña:</label>
                        <div class="password-container">
                          <input type="password" class="form-control" name="password2" id="password2"
                            placeholder="Ingrese password" required>
                          <i class="toggle-password fas fa-eye" id="togglePassword2"></i>
                        </div>
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


                      <div class="form-group">
                        <label>Nivel de usuario:</label>
                        <select class="form-control" name="nivel" required>

                          <?php
                          $tabla2 = ModeloUsuario::listarNivelMdl();
                          echo '<option value="" selected>Seleccione Nivel de Usuario</option>';
                          foreach ($tabla2 as $key => $value) {
                            echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                          }
                          echo '</select>';

                          ?>
                      </div>
                      <!-- /.card-body -->
                      <input type="hidden" name="tipoOperacion" value="insertarUsuario">
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

<div class="modal fade" id="modal-editar-usuario" style="overflow-y: scroll;">
  <div class="modal-dialog modal-l">
    <div class="modal-content">
      <form id="editarUsuario" enctype="multipart/form-data">

        <div class="modal-header">
          <h4 class="modal-title">Editar Usuario</h4>
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
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese nombre"
                          required>
                      </div>

                      <div class="form-group">
                        <label for="exampleInputEmail1">Email:</label>
                        <input type="text" class="form-control" name="email" id="email" placeholder="Ingrese email"
                          required>
                      </div>
                      <div class="form-group">
                        <label for="exampleInputEmail1">Contraseña:</label>
                        <div class="password-container">
                          <input type="password" class="form-control" name="password" id="password3"
                            placeholder="Ingrese password" required>
                          <i class="toggle-password fas fa-eye" id="togglePassword3"></i>
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="planta_idEditar">Planta:</label>
                        <select class="form-control" name="planta_id" id="planta_idEditar">
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


                      <div class="form-group">
                        <label>Nivel de usuario:</label>
                        <select class="form-control" name="nivel" required>

                          <?php
                          $tabla2 = ModeloUsuario::listarNivelMdl();
                          echo '<option value="" selected>Seleccione Nivel de Usuario</option>';
                          foreach ($tabla2 as $key => $value) {
                            echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                          }
                          echo '</select>';

                          ?>
                      </div>


                    </div>
                    <!-- /.card-body -->
                    <input type="hidden" name="tipoOperacion" value="actualizarUsuario">
                    <input type="hidden" name="id_usuario">
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