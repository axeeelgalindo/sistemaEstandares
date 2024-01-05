<div class="modal fade" id="modal-editar-usuariop" style="overflow-y: scroll;">
        <div class="modal-dialog modal-l">
          <div class="modal-content">
          <form id="editarPerfil" enctype="multipart/form-data">

            <div class="modal-header">
            <h4 class="modal-title">Editar Perfil</h4>
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
                    <?php
                $tabla = ModeloUsuario::listarUsuarioIdMdl($_SESSION["nombre"]);
          foreach ($tabla as $key => $value) {
            echo ' <div class="form-group">
                    <label for="exampleInputEmail1">Nombre:</label>
                    <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Ingrese nombre" value="'.$value["nombre"].'" required>  
                </div>
                <div class="form-group">
                <label for="exampleInputEmail1">Email:</label>
                <input type="text" class="form-control" name="email" id="email" placeholder="Ingrese email" value="'.$value["email"].'" required>  
            </div>
            <div class="form-group">
            <label for="exampleInputEmail1">Contraseña:</label>
            <div class="password-container">
            <input type="password" class="form-control" name="password" id="password" placeholder="Ingrese password" value="'.$value["password"].'" required>  
            <i class="toggle-password fas fa-eye" id="togglePassword"></i>
            </div>
        </div>
                
           
                </div>
                <!-- /.card-body -->
                <input type="hidden" name="tipoOperacion" value="actualizarPerfil">
               <input type="hidden" name="id_usuario" value="'.$value["id"].'">
               <input type="hidden" name="email_old" value="'.$value["email"].'">';

            
            }

            ?>
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
   
