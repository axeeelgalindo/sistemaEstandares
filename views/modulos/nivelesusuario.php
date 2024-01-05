<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Niveles de usuarios</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
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
<!--                 <h3 class="card-title col-10">Listado de niveles de usuarios</h3>
                <button class="col-2 btn btn-sm btn-secondary" data-toggle="modal" data-target="#modal-insertar-nuevo-nivel">
                                        <i class="far fa-edit"></i> + Agregar Nivel de usuario
                                    </button> -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
              <div id="accordion">

              <?php    
              $tabla = ModeloNivelUsuario::listarNivelUsuarioMdl();
              foreach ($tabla as $key => $value) {
              echo'
                  <div class="card card-secondary">
                    <div class="card-header">
                      <h4 class="card-title w-100">
                        <a class="d-block w-100" data-toggle="collapse" href="#collapseOne'.$value["id"].'">
                        '.$value["nombre"].'
                        </a>
                      </h4>
                    </div>
                     <div id="collapseOne'.$value["id"].'" class="collapse hide" data-parent="#accordion">
                        <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                                <thead>
                                    <tr>
                                        <th >
                                        Menú
                                        </th>
                                        <th style="width: 20%;" >
                                        Acción
                                        </th>
                                    </tr>
                                </thead>
                                    <tbody>';

                                            $tabla2 = ModeloNivelUsuario::listarMenuNivelUsuarioMdl($value["id"]);
                                            foreach ($tabla2 as $key => $value2) {
                                                echo '
                                                <tr>
                                                <td>'.nl2br($value2["menu"]).'</td>
                                                <td width="100">

                                                <div class="form-group">
                                                <select class="form-control nivel" name="nivel" required  idMenuEstandar="'.$value2["id_niveles_usuarios_menu"].'">';
                        
                                                            $tabla3 = ModeloNivelUsuario::listarNivelEstadosMdl();
                                                            foreach ($tabla3 as $key => $value3) { 
                                                                if($value2["id_estado"] == $value3["id"]){
                                                                 echo '<option value="'.$value3["id"].'" selected>'.$value3["estado"].'</option>';                                                                  
                                                                }else{
                                                                    echo '<option value="'.$value3["id"].'">'.$value3["estado"].'</option>';
                                                                }

                                                            }
                                                            echo '</select>
                                      </div>
                                                    </td>
                                </tr>
                                ';
                            } echo'
                                    </tbody>
                        
                                    </table>
                       </div>
                      </div>
                    </div>';} ?>


              
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




