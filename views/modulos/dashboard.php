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

              <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                    $tabla = ModeloArea::listarAreaMdl();
                    foreach ($tabla as $key => $value) {
                      echo '<option value='.$value["id"].'>'.$value["nombre"].'</option>';}?>
                  </select>
                </div>
                <!-- /.form-group -->
              </div>
                <div class="chart" style="height: 285px;">
                  <canvas id="barChart2" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
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
                  <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

             <!-- PIE CHART -->
             <div class="card card-secondary" >
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
                    <div class=" col-md-6" >
                    <div class="small-box bg-default">
                    <canvas id="pieChart" style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                  </div>

                  </div>
                  <div class=" col-md-6" >
                  <div class="small-box bg-default">
                    <canvas id="pieChart2" style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                  </div>
                  </div>

                  <div class=" col-md-6" >
                  <div class="small-box bg-default">

                    <canvas id="pieChart3" style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
                  </div>
                  </div>

                  <div class=" col-md-6" >
                  <div class="small-box bg-default">

                  <canvas id="pieChart4" style="min-height: 150px; height: 150px; max-height: 150px; max-width: 100%;"></canvas>
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
