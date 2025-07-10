<!--<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Usuarios</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid 
</section>-->


<section class="content pt-3">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title col-md-10 col-sm-12">Listado de usuarios registrados</h3>
            <button class="col-md-2 col-sm-12 btn btn-sm btn-secondary" data-toggle="modal"
              data-target="#modal-insertar-nuevo-usuario">
              <i class="far fa-edit"></i> + Agregar Usuario
            </button>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>
                    Nombre
                  </th>
                  <th>
                    Email
                  </th>
                  <th>
                    Activo
                  </th>
                  <th>
                    Nivel de usuario
                  </th>
                  <th>
                    Planta
                  </th>
                  <th style="width: 20%;">
                    Acción
                  </th>
                </tr>
              </thead>
              <tbody>

                <?php
                $nivel_usuario = $_SESSION['nivel_usuario'] ?? null;
                $planta_id = $_SESSION['planta_id'] ?? null;

                // Solo filtra si no es superadmin (nivel 4)
                if ($nivel_usuario == 4) {
                  $tabla = ModeloUsuario::listarUsuarioMdl(); // Superadmin ve todo
                } else {
                  $tabla = ModeloUsuario::listarUsuarioMdl($planta_id); // Filtra por su planta
                }
                foreach ($tabla as $key => $value) {
                  echo '
            <tr>
            <td>' . nl2br($value["nombre"]) . '</td>
            <td>' . nl2br($value["email"]) . '</td>
            <td>' . nl2br($value["activo"]) . '</td>
            <td>' . nl2br($value["nivel de usuario"]) . '</td>
            <td>' . (!empty($value["planta"]) ? nl2br($value["planta"]) : 'N/A') . '</td>
            <td width="100" class="text-center"> 
            <button class="btn btn-sm btn-info btnEditarUsuario mx-1" idUsuario="' . $value["id"] . '" data-toggle="modal" data-target="#modal-editar-usuario">
                                <i class="far fa-edit"></i> 
                            </button>
                <button class="btn btn-sm btn-danger btnEliminarUsuario mx-1" UsuarioActi="' . $value["activo"] . '"  idUsuario="' . $value["id"] . '">
                                <i class="far fa-trash-alt"></i> 
                </button>
                </td>
            </tr>
            ';
                } ?>
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