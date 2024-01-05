<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Ajustes de perfil</h1>
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
        <div class="col-md-12">


<!-- /.card -->

<!-- About Me Box -->
<div class="card card-secondary">
  <div class="card-header">
    <h3 class="card-title">Información</h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
  <?php
          $tabla = ModeloUsuario::listarUsuarioIdMdl($_SESSION["nombre"]);
          foreach ($tabla as $key => $value) {
            echo '<strong><i class="fas fa-user"></i> Nombre</strong>

    <p class="text-muted">
    '.$value["nombre"].'
    </p>
    <hr>
    <strong><i class="fas fa-envelope"></i> Email</strong>
    <p class="text-muted">'.$value["email"].'</p>
    <hr>
    <strong><i class="fa-brands fa-creative-commons-sampling"></i> Estado</strong>

    <p class="text-muted">
      <span class="tag tag-danger">'.$value["activo"].'</span>
    </p>
    <hr>
    <strong><i class="fa-solid fa-layer-group"></i> Nivel de usuario</strong>
    <p class="text-muted">'.$value["nivel de usuario"].'</p>';
          }
            ?>
    <a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-editar-usuariop"><b>Editar</b></a>
  </div>
  <!-- /.card-body -->
</div>
<!-- /.card -->
</div>

        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->




