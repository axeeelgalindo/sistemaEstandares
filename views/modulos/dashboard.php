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
    <ul class="nav nav-tabs m-2" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home"
          aria-selected="true">Estándares</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile"
          aria-selected="false">Personas</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="adquisicion-tab" data-toggle="tab" href="#adquisicion" role="tab"
          aria-controls="adquisicion" aria-selected="false">Adquisición</a>
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
                      <div class="col-lg-9 col-sm-6">
                        <canvas id="donutChart"
                          style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                        <select class="form-control areas" style="width: 60%;" name="areas">
                          <option value="0">Todas las Áreas</option>
                          <?php
                          $planta_id = $_SESSION('planta_id');
                          $tabla = ModeloArea::listarAreaMdl($planta_id);
                          foreach ($tabla as $key => $value) {
                            echo '<option value=' . $value["id"] . '>' . $value["nombre"] . '</option>';
                          } ?>
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
                    <div class="chart">
                      <canvas id="barChart"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                          <canvas id="pieChart"
                            style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                        </div>

                      </div>
                      <div class=" col-md-6">
                        <div class="small-box bg-default">
                          <canvas id="pieChart2"
                            style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                        </div>
                      </div>

                      <div class=" col-md-6">
                        <div class="small-box bg-default">

                          <canvas id="pieChart3"
                            style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                        </div>
                      </div>

                      <div class=" col-md-6">
                        <div class="small-box bg-default">

                          <canvas id="pieChart4"
                            style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
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
        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
          <!-- Main content 2 -->
          <div class="content">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-6">
                  <!-- DONUT CHART -->
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
                        <div class="col-lg-9 col-sm-6">
                          <canvas id="donutChart2"
                            style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <div class="col-lg-3 col-sm-6">
                          <!-- small card -->
                          <div class="small-box bg-default">
                            <div class="inner">
                              <h3 style="color:red;" id="PorcentajeEntrenado2"></h3>
                              <p>Entrenados</p>
                            </div>
                            <div class="icon">
                              <i class="ion ion-stats-bars"></i>
                            </div>
                          </div>
                          <div class="small-box bg-default">
                            <div class="inner">
                              <h3 style="color:red;" id="HorasEntrenado"></h3>
                              <p>Horas entrenadas</p>
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
                    <div class="card-body">
                      <div class="col-6 col-sm-6">
                        <div class="form-group">
                          <select class="form-control areas" style="width: 60%;" name="areas">
                            <option value="0">Todas las Áreas</option>
                            <?php
                            $planta_id = $_SESSION('planta_id');

                            $tabla = ModeloArea::listarAreaMdl($planta_id);
                            foreach ($tabla as $key => $value) {
                              echo '<option value=' . $value["id"] . '>' . $value["nombre"] . '</option>';
                            } ?>
                          </select>
                        </div>
                        <!-- /.form-group -->
                      </div>
                      <div class="chart" style="height: 285px;">
                        <canvas id="barChart4"
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
                      <div class="chart">
                        <canvas id="barChart3"
                          style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                      </div>

                    </div>
                    <!-- /.card-body -->
                  </div>
                  <!-- /.card -->

                  <!-- PIE CHART -->
                  <div class="card card-secondary">
                    <div class="card-header">
                      <h3 class="card-title">Personas Totales vs Personas Entrenadas por pilar</h3>

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
                            <canvas id="pieChart5"
                              style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                          </div>
                          <h5 style="color:red;">
                            <p style="font-size: 16px" ;>Horas entrenadas: <b id="HorasEntrenado1">TEST</b></p>
                          </h5>
                        </div>
                        <div class=" col-md-6">
                          <div class="small-box bg-default">
                            <canvas id="pieChart6"
                              style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                          </div>
                          <h5 style="color:red;">
                            <p style="font-size: 16px" ;>Horas entrenadas: <b id="HorasEntrenado2">TEST</b></p>
                          </h5>
                        </div>

                        <div class=" col-md-6">
                          <div class="small-box bg-default">
                            <canvas id="pieChart7"
                              style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                          </div>
                          <h5 style="color:red;">
                            <p style="font-size: 16px" ;>Horas entrenadas: <b id="HorasEntrenado3">TEST</b></p>
                          </h5>
                        </div>

                        <div class=" col-md-6">
                          <div class="small-box bg-default">
                            <canvas id="pieChart8"
                              style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                          </div>
                          <h5 style="color:red;">
                            <p style="font-size: 16px" ;>Horas entrenadas: <b id="HorasEntrenado4">TEST</b></p>
                          </h5>
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
                      <div class="col-lg-9 col-sm-6">
                        <canvas id="donutChartAdquisicion"
                          style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                        <select class="form-control areas" style="width: 60%;" name="areas">
                          <option value="0">Todas las Áreas</option>
                          <?php
                          $planta_id = $_SESSION('planta_id');
                          $tabla = ModeloArea::listarAreaMdl($planta_id);
                          foreach ($tabla as $key => $value) {
                            echo '<option value=' . $value["id"] . '>' . $value["nombre"] . '</option>';
                          } ?>
                        </select>
                      </div>
                      <!-- /.form-group -->
                    </div>
                    <div class="chart" style="height: 285px;">
                      <canvas id="barChartAdquisicion"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                    <div class="chart">
                      <canvas id="barChartAdquisicionAnual"
                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                          <canvas id="pieChartAdquisicion1"
                            style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="small-box bg-default">
                          <canvas id="pieChartAdquisicion2"
                            style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="small-box bg-default">
                          <canvas id="pieChartAdquisicion3"
                            style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="small-box bg-default">
                          <canvas id="pieChartAdquisicion4"
                            style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
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