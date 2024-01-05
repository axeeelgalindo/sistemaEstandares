  
         <!-- Modal para ingresar personas -->

         <div class="modal fade" id="modal-insertar-nuevo-estandar" style="overflow-y: scroll;">
        <div class="modal-dialog modal-l">
          <div class="modal-content">
          <form id="insertarEstandar" enctype="multipart/form-data">

            <div class="modal-header">
              <h4 class="modal-title">Ingresar Estandar</h4>
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
                    <label for="exampleInputEmail1">Código:</label>
                    <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Ingrese código" required>  
                  </div>
                <div class="form-group">
                <label for="exampleInputEmail1">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese nombre" required>
                </div>
                <div class="form-group">
                        <label>Tipo:</label>
                        <select class="form-control" name="tipo" required>

                                    <?php
                                    $tabla2 = ModeloArea::listarTipoMdl();
                                    echo '<option value="" selected>Seleccione Tipo de estandar</option>';
                                    foreach ($tabla2 as $key => $value) { 
                                    echo '<option value="'.$value["id"].'">'.$value["tipo"].'</option>';
                                    }
                                    echo '</select>';

                                    ?>
              </div>
              <div class="form-group">
                        <label>Área:</label>

                        <div class="col-sm-12">
                      <!-- checkbox -->
                      <div class="form-group">                       
                        <?php
                                    $tabla2 = ModeloArea::listarAreaMdl();
                                    foreach ($tabla2 as $key => $value) { 
                              echo '<div class="custom-control custom-checkbox">
                              <input class="custom-control-input" type="checkbox" name="area[]" id="customCheckbox'.$value["id"].'" value="'.$value["id"].'">
                               <label for="customCheckbox'.$value["id"].'" class="custom-control-label">'.$value["nombre"].'</label>
                                 </div>';}
                                  
                                    ?>
                      </div>
                    </div>
              </div>
                    <!-- general form elements disabled -->
              <div class="form-group">
                  <label for="exampleInputEmail1">Cargar imagen</label>
                  <input type="file" class="form-control" id="exampleInputEmail1" name="ArchivoEstandar" placeholder="Seleccion archivo" accept=".jpg, .png">
                </div>
                </div>
                <!-- /.card-body -->
                <input type="hidden" name="tipoOperacion" value="insertarEstandar">
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


      <div class="modal fade" id="modal-editar-estandar" style="overflow-y: scroll;">
        <div class="modal-dialog modal-l">
          <div class="modal-content">
          <form id="editarEstandar" enctype="multipart/form-data">

            <div class="modal-header">
            <h4 class="modal-title">Editar Estandar</h4>
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
                    <label for="exampleInputEmail1">Código:</label>
                    <input type="text" class="form-control" name="codigo" id="codigo" placeholder="Ingrese código" required>  
                </div>
                <div class="form-group">
                <label for="exampleInputEmail1">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese nombre" required>
                </div>
                <div class="form-group">
                        <label>Tipo:</label>
                        <select class="form-control" name="tipo" required>

                                    <?php
                                    $tabla2 = ModeloArea::listarTipoMdl();
                                    echo '<option value="" selected>Seleccione Tipo de estandar</option>';
                                    foreach ($tabla2 as $key => $value) { 
                                    echo '<option value="'.$value["id"].'">'.$value["tipo"].'</option>';
                                    }
                                    echo '</select>';

                                    ?>
            </div>

            <div class="form-group">
                        <label>Área:</label>

                        <div class="col-sm-12">
                      <!-- checkbox -->
                      <div class="form-group">                       
                        <?php
                                    $tabla2 = ModeloArea::listarAreaMdl();
                                    foreach ($tabla2 as $key => $value) { 
                              echo '<div class="custom-control custom-checkbox">
                              <input class="custom-control-input" type="checkbox" name="area[]" id="customCheckboxE'.$value["id"].'" value="'.$value["id"].'">
                               <label for="customCheckboxE'.$value["id"].'" class="custom-control-label">'.$value["nombre"].'</label>
                                 </div>';}                                 
                                    ?>
                      </div>
                    </div>
              </div>

            <div class="form-group">
                <label for="exampleInputEmail1">Cargar imagen</label>
                <input type="file" class="form-control" id="exampleInputEmail1" name="ArchivoEstandar" placeholder="Seleccion archivo" accept=".jpg, .png">
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
            <button type="submit" class="btn btn-primary">Guardar</button>
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
      
   
            <div class="contenedor-imagen" style="width: 100%;max-width: 100%;height:auto;">
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

    