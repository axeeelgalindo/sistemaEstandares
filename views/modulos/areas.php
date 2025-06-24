<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Áreas</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Inicio</a></li>
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
            <h3 class="card-title col-md-10 col-sm-12">Listado de áreas registradas</h3>
            <button class="col-md-2 col-sm-12 btn btn-sm btn-secondary" data-toggle="modal"
              data-target="#modal-insertar-nuevo-area">
              <i class="far fa-edit"></i> + Agregar Área
            </button>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>
                    Área
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
                $planta_id = $_SESSION["planta_id"];
                $tabla = ModeloArea::listarAreaMdl($planta_id);
                foreach ($tabla as $key => $value) {
                  echo '
            <tr>
            <td>' . nl2br($value["nombre"]) . '</td>
                        <td>' . (!empty($value["planta"]) ? nl2br($value["planta"]) : 'N/A') . '</td>

    
            <td width="100" class="text-center"> 
            <button class="btn btn-sm btn-info btnEditarArea mx-1" idArea="' . $value["id"] . '" data-toggle="modal" data-target="#modal-editar-area">
              <i class="far fa-edit"></i> 
            </button>
            <button class="btn btn-sm btn-danger btnEliminarArea mx-1"  idArea="' . $value["id"] . '">
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