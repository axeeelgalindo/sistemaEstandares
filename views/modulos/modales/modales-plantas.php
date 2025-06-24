<!-- Modal para ingresar planta -->
<div class="modal fade" id="modal-insertar-nueva-planta" style="overflow-y: scroll;">
  <div class="modal-dialog modal-l">
    <div class="modal-content">
      <form id="insertarPlanta" enctype="multipart/form-data">
        <div class="modal-header">
          <h4 class="modal-title">Ingresar Planta</h4>
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
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese planta" required>
                      </div>
                    </div>
                    <input type="hidden" name="tipoOperacion" value="insertarPlanta">
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Agregar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para editar planta -->
<div class="modal fade" id="modal-editar-planta" style="overflow-y: scroll;">
  <div class="modal-dialog modal-l">
    <div class="modal-content">
      <form id="editarPlanta" enctype="multipart/form-data">
        <div class="modal-header">
          <h4 class="modal-title">Editar Planta</h4>
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
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" name="nombre" id="nombreEditar" placeholder="Ingrese planta" required>
                      </div>
                    </div>
                    <input type="hidden" name="tipoOperacion" value="actualizarPlanta">
                    <input type="hidden" name="id_planta" id="id_planta">
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
</div>
