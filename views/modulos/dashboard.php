<?php

error_log("🕵️‍♀️ [dashboard.php] cargando vista del dashboard para planta: " . ($_SESSION['planta_id'] ?? 'NULL'));
$planta_id = $_SESSION['planta_id'] ?? null;
$id_nivel = isset($_SESSION['nivel_usuario']) ? intval($_SESSION['nivel_usuario']) : 0;

error_log("💡 SESSION id_nivel → " . var_export($_SESSION['id_nivel'], true));

?>

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Inicio</a></li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content m-2">
  <div class="card shadow">
    <?php if ($id_nivel === 4):

      ?>
      <div class="card-header">
        <div class="form-group">
          <label for="plantaFilter">Planta:</label>
          <select id="plantaFilter" class="form-control" style="width:30%;">
            <?php
            require_once __DIR__ . '/../../models/planta.model.php';
            // Asegúrate de haber incluido el modelo Planta antes
            $plantas = ModeloPlanta::listarPlantas();
            foreach ($plantas as $p) {
              $sel = ($p['id'] == ($_SESSION['planta_id'] ?? 0)) ? 'selected' : '';
              echo "<option value=\"{$p['id']}\" $sel>{$p['nombre']}</option>";
            }
            ?>
          </select>
        </div>
      </div>
    <?php endif; ?>
    <ul class="nav nav-tabs m-2" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab">Estándares</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab">Personas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="adquisicion-tab" data-toggle="tab" href="#adquisicion" role="tab">Adquisición</a>
      </li>
    </ul>
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6">

                <!-- /.card -->
                <!-- /.card -->

                <!-- DONUT CHART -->
                <div class="card card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">Creados vs Entrenados</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-9 col-sm-6" style="height:250px;">
                        <canvas id="donutChart"></canvas>
                      </div>
                      <div class="col-lg-3 col-sm-6">
                        <!-- small card -->
                        <div class="small-box bg-default">
                          <div class="inner">
                            <h3 style="color:red;" id="PorcentajeEntrenado"></h3>

                            <p>Entrenados</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- BAR CHART -->
                <div class="card card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">Creados vs Entrenados por Área</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>

                  </div>
                  <div class="card-body">
                    <div class="col-6 col-sm-6">
                      <div class="form-group">
                        <select id="areaFilter" class="form-control areas" style="width: 60%;" name="areas">
                          <option value="0">Todas las Áreas</option>
                          <?php
                          $planta_id = $_SESSION['planta_id'];
                          $tabla = ModeloArea::listarAreaMdl($planta_id);
                          foreach ($tabla as $value) {
                            echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                      <!-- /.form-group -->
                    </div>
                    <div class="chart" style="height: 285px;">
                      <canvas id="barChart2"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>

              </div>
              <!-- /.col (LEFT) -->
              <div class="col-md-6">
                <!-- LINE CHART -->
                <!-- STACKED BAR CHART -->
                <div class="card card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">Creados vs Entrenados Anual</h3>

                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="chart" style="height:250px;">
                      <canvas id="barChart"></canvas>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- PIE CHART -->
                <div class="card card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">Creados vs entrenados por pilar</h3>

                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class=" col-md-6">
                        <div class="small-box bg-default">
                          <canvas id="pieChart"></canvas>
                        </div>

                      </div>
                      <div class=" col-md-6">
                        <div class="small-box bg-default">
                          <canvas id="pieChart2"></canvas>
                        </div>
                      </div>

                      <div class=" col-md-6">
                        <div class="small-box bg-default">
                          <canvas id="pieChart3"></canvas>
                        </div>
                      </div>

                      <div class=" col-md-6">
                        <div class="small-box bg-default">

                          <canvas id="pieChart4"></canvas>
                        </div>
                      </div>

                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->

              </div>
              <!-- /.col (RIGHT) -->
            </div>
            <!-- /.row -->
          </div><!-- /.container-fluid -->
          <!-- /.container-fluid -->
        </div>
      </div>
      <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
        <div class="content">
          <div class="container-fluid">

            <!-- PRIMERA FILA: Donut y Anual -->
            <div class="row">
              <!-- Donut de Entrenamientos -->
              <div class="col-md-6">
                <div class="card card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">Entrenamientos Disponibles vs Entrenamientos Ejecutados</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-9 col-sm-6" style="height:250px;">
                        <canvas id="donutChart2"></canvas>
                      </div>
                      <div class="col-lg-3 col-sm-6">
                        <div class="small-box bg-default">
                          <div class="inner">
                            <h3 id="PorcentajeEntrenado2" class="text-red"></h3>
                            <p>Entrenados</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                        </div>
                        <div class="small-box bg-default" style="min-height: 120px;">
                          <div class="inner">
                            <h3 id="PorcentajeEntrenado2" style="color:red; margin-bottom: 0.5em;"></h3>
                            <!-- aquí insertaremos las horas -->
                            <h4 id="HorasEntrenado" style="white-space: pre-line; font-size: 0.9em; margin-top: 0.5em;">
                            </h4>
                          </div>
                          <div class="icon">
                            <i class="ion ion-clock"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Personas Totales vs Entrenadas Anual -->
              <div class="col-md-6">
                <div class="card card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">Personas Totales vs Personas Entrenadas Anual</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="chart" style="height:250px;">
                      <canvas id="barChart3"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- SEGUNDA FILA: Por Área y Por Pilar -->
            <div class="row">
              <!-- Personas en Entrenamiento vs Entrenadas por Área -->
              <div class="col-md-6 d-flex align-items-stretch mb-3">
                <div class="card card-secondary w-100">
                  <div class="card-header">
                    <h3 class="card-title">Personas en Entrenamiento vs Personas Entrenadas por Área</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <!-- Convertimos el body en flex-column para que el chart “crezca” -->
                  <div class="card-body d-flex flex-column">
                    <div class="row mb-3">
                      <div class="col-6 col-sm-6">
                        <select id="areaFilterPersonas" class="form-control" style="width: 60%">
                          <option value="0">Todas las Áreas</option>
                          <?php
                          $planta_id = $_SESSION['planta_id'];
                          $tabla = ModeloArea::listarAreaMdl($planta_id);
                          foreach ($tabla as $value) {
                            echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <!-- Hacemos que este div crezca -->
                    <div class="chart flex-fill" style="min-height: 285px;">
                      <canvas id="barChart4"></canvas>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Personas Totales vs Entrenadas por Pilar -->
              <div class="col-md-6">
                <!-- Personas Totales vs Personas Entrenadas por Pilar -->
                <div class="card card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">Personas Totales vs Personas Entrenadas por Pilar</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <?php
                      $pillars = [
                        ['id' => 'pieChart5', 'horas' => 'HorasEntrenado1'],
                        ['id' => 'pieChart6', 'horas' => 'HorasEntrenado2'],
                        ['id' => 'pieChart7', 'horas' => 'HorasEntrenado3'],
                        ['id' => 'pieChart8', 'horas' => 'HorasEntrenado4'],
                      ];
                      foreach ($pillars as $p): ?>
                        <div class="col-md-6 d-flex align-items-stretch mb-3">
                          <!-- small-box igual alto que tu gráfico de área -->
                          <div class="small-box bg-default w-100" style="height: 285px;">
                            <!-- aca tu canvas centrado -->
                            <div class="chart" style="height: 220px;">
                              <canvas id="<?= $p['id'] ?>"></canvas>
                            </div>
                            <!-- horas abajo, dentro de la misma caja -->
                            <div class="inner">
                              <p class="text-danger mb-0">
                                Horas entrenadas:<br>
                                <b id="<?= $p['horas'] ?>">0</b>
                              </p>
                            </div>
                          </div>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div><!-- /.container-fluid -->
        </div><!-- /.content -->
      </div>
      <div class="tab-pane fade" id="adquisicion" role="tabpanel" aria-labelledby="adquisicion-tab">
        <div class="content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-6">
                <!-- DONUT CHART -->
                <div class="card card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">Entrenados vs Estándares Adquiridos</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-lg-9 col-sm-6" style="height:250px;">
                        <canvas id="donutChartAdquisicion"></canvas>
                      </div>
                      <div class="col-lg-3 col-sm-6">
                        <div class="small-box bg-default">
                          <div class="inner">
                            <h3 style="color:red;" id="PorcentajeEntrenadoAdquisicion"></h3>
                            <p>Entrenados</p>
                          </div>
                          <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- BAR CHART -->
                <div class="card card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">Entrenados vs Estándares Adquiridos por Área</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="col-6 col-sm-6">
                      <div class="form-group">
                        <select id="areaFilterAdquisicion" class="form-control areas" style="width: 60%;" name="areas">
                          <option value="0">Todas las Áreas</option>
                          <?php
                          $planta_id = $_SESSION['planta_id'];
                          $tabla = ModeloArea::listarAreaMdl($planta_id);
                          foreach ($tabla as $value) {
                            echo '<option value="' . $value["id"] . '">' . $value["nombre"] . '</option>';
                          }
                          ?>
                        </select>
                      </div>
                      <!-- /.form-group -->
                    </div>
                    <div class="chart" style="height: 285px;">
                      <canvas id="barChartAdquisicion"></canvas>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>

              </div>
              <!-- /.col (LEFT) -->
              <div class="col-md-6">
                <!-- LINE CHART -->
                <div class="card card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">Entrenados vs Estándares Adquiridos Anual</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="chart" style="height:250px;">
                      <canvas id="barChartAdquisicionAnual"></canvas>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- PIE CHART -->
                <div class="card card-secondary">
                  <div class="card-header">
                    <h3 class="card-title">Entrenados vs Estándares Adquiridos por pilar</h3>
                    <div class="card-tools">
                      <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                      </button>
                      <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                      </button>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="small-box bg-default">
                          <canvas id="pieChartAdquisicion1"></canvas>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="small-box bg-default">
                          <canvas id="pieChartAdquisicion2"></canvas>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="small-box bg-default">
                          <canvas id="pieChartAdquisicion3"></canvas>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="small-box bg-default">
                          <canvas id="pieChartAdquisicion4"></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->

              </div>
              <!-- /.col (RIGHT) -->
            </div>
            <!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
      </div>
    </div>
  </div>
</div>
</div>