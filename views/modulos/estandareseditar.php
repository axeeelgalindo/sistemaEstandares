<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Editar Estandares</h1>
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
            <div class="row">
              <h3 class="col-md-10 col-sm-12 card-title">Listado de estandares registradas</h3>
              <button class="col-md-2 col-sm-12 btn btn-sm btn-secondary" data-toggle="modal"
                data-target="#modal-insertar-nuevo-estandar">
                <i class="far fa-edit"></i> + Agregar Estandar
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>
                    Código
                  </th>
                  <th>
                    Nombre
                  </th>
                  <th>
                    Áreas
                  </th>
                  <th>
                    Tipo
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
                // Listamos estándares
                $tabla = ModeloEstandar::listarEstandaresMdl($_SESSION['planta_id']);
                // Ruta base de tu aplicación (ajústala si cambias de carpeta)
                $baseUrl = '/SistemaEstandaresAquaChile/';
                foreach ($tabla as $value) {
                  // 1) Limpiar puntos y barras al principio
                  $raw = ltrim($value['url_pdf'], './');
                  // 2) Fusionar con la ruta base
                  $fullImgUrl = $baseUrl . $raw;
                  echo '
      <tr>
        <td>' . htmlspecialchars($value["codigo"]) . '</td>
        <td>' . htmlspecialchars($value["nombre"]) . '</td>
        <td>' . htmlspecialchars($value["areas"]) . '</td>
        <td>' . htmlspecialchars($value["tipo"]) . '</td>
        <td>' . (!empty($value["planta"]) ? htmlspecialchars($value["planta"]) : 'N/A') . '</td>
        <td width="100">
          <button
            class="btn btn-sm btn-default btnVerEstandar"
            data-imgurl="' . htmlspecialchars($fullImgUrl) . '"
            data-toggle="modal"
            data-target="#modal-ver-estandar"
          >
            <i class="far fa-eye"></i>
          </button>
          <button
            class="btn btn-sm btn-info btnEditarEstandar"
            IdEstandar="' . intval($value["id"]) . '"
            data-toggle="modal"
            data-target="#modal-editar-estandar"
          >
            <i class="far fa-edit"></i>
          </button>';
                  if ($_SESSION["nivel_usuario"] == 1) {
                    echo '
          <button
            class="btn btn-sm btn-danger btnEliminarEstandar"
            IdEstandar="' . intval($value["id"]) . '"
            rutaImagen="' . htmlspecialchars($value["url_pdf"]) . '"
          >
            <i class="far fa-trash-alt"></i>
          </button>';
                  }
                  echo '
        </td>
      </tr>
    ';
                }
                ?>

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

  <!-- /.container-fluid -->
</section>
<!-- /.content -->