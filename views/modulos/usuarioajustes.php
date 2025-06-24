<?php
// views/modulos/usuarioajustes.php

// 1) Cargar los modelos
require_once __DIR__ . '/../../models/usuario.model.php';
require_once __DIR__ . '/../../models/planta.model.php';

if (!isset($_SESSION["nombre"])) {
  echo "<div class='alert alert-danger'>No se ha definido la planta_id en la sesión.</div>";
  die();
}


// 2) Obtener datos del usuario
$tabla   = ModeloUsuario::listarUsuarioIdMdl($_SESSION["nombre"]);
$value   = $tabla[0];


?>
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6 text-center"><h1>Ajustes de perfil</h1></div>
      <!--<div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="dashboard">Inicio</a></li>
        </ol>
      </div>-->
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-secondary">
          <div class="card-header">
            <h3 class="card-title">Información</h3>
          </div>
          <div class="card-body">
            <strong><i class="fas fa-user"></i> Nombre</strong>
            <p class="text-muted"><?= htmlspecialchars($value['nombre']) ?></p>
            <hr>

            <strong><i class="fas fa-envelope"></i> Email</strong>
            <p class="text-muted"><?= htmlspecialchars($value['email']) ?></p>
            <hr>

            <strong><i class="fa-brands fa-creative-commons-sampling"></i> Estado</strong>
            <p class="text-muted"><?= $value['activo'] ?></p>
            <hr>

            <strong><i class="fa-solid fa-industry"></i> Planta</strong>
            <p class="text-muted"><?= htmlspecialchars($value['planta']) ?></p>
            <hr>

            <strong><i class="fa-solid fa-layer-group"></i> Nivel de usuario</strong>
            <p class="text-muted"><?= htmlspecialchars($value['nivel de usuario']) ?></p>

            <a href="#" class="btn btn-primary btn-block mt-3"
               data-toggle="modal" data-target="#modal-editar-usuariop">
              <b>Editar</b>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
// 3) Incluir el modal al final de la página
include __DIR__ . '/modales/modales-usuarioajustes.php';
