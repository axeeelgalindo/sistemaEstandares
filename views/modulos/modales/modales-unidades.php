         <!-- Modal para ingresar personas -->

         <div class="modal fade" id="modal-insertar-nuevo-unidad" style="overflow-y: scroll;">
        <div class="modal-dialog modal-l">
          <div class="modal-content">
          <form id="insertarUnidad" enctype="multipart/form-data">

            <div class="modal-header">
              <h4 class="modal-title">Ingresar Unidad</h4>
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
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese Unidad" required>  
                  </div>
                </div>
                <!-- /.card-body -->
                <input type="hidden" name="tipoOperacion" value="insertarUnidad">
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
    
      <div class="modal fade" id="modal-editar-unidad" style="overflow-y: scroll;">
        <div class="modal-dialog modal-l">
          <div class="modal-content">
          <form id="editarUnidad" enctype="multipart/form-data">

            <div class="modal-header">
            <h4 class="modal-title">Editar Unidad</h4>
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
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese Unidad" required>  
                </div>
                </div>
                <!-- /.card-body -->
                <input type="hidden" name="tipoOperacion" value="actualizarUnidad">
               <input type="hidden" name="id_unidad">
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