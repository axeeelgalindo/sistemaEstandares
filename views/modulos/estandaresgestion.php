
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Entrenamiento</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Inicio</a></li>
              <li class="breadcrumb-item active">Entrenamiento</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
        <h3 class="col-10  card-title">Listado de estandares</h3>
        </div>
        <div class="card-body ">
          <table id="example1" class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 5%">
                          Código
                      </th>
                      <th style="width: 20%">
                        Tipo de Estandar
                      </th>
                      <th style="width: 20%">
                        Nombre Estandar
                      </th>
                      <th style="width: 20%">
                        Area
                      </th>
                      <th>
                      Total Personas / Entrenadas
                      </th>
                      <th>
                      % De Entrenamiento
                      </th>
                      <th style="width: 20%">
                      Acción
                      </th>
                  </tr>
              </thead>
              <tbody>
                
                          
                  <?php

                  
                    $tabla = ModeloEstandar::listarEstandaresCargadosMdl();
                    foreach ($tabla as $key => $value) {
                    echo '
            <tr>
            <td>'.nl2br($value["codigo"]).' </td>
            <td>'.nl2br($value["tipo"]).'</td>
            <td> '.nl2br($value["nombre"]).'</td>
            <td>'.nl2br($value["area"]).'</td>
            <td>'.nl2br($value["total_personas"]).'/'.nl2br($value["total_personas_entrenadas"]).' </td>
                      <td class="project_progress">
                          <div class="progress progress-sm">
                              <div class="progress-bar bg-green" role="progressbar" aria-valuenow="'.nl2br($value["porcentaje_entrenado"]).'" aria-valuemin="0" aria-valuemax="100" style="width: '.nl2br($value["porcentaje_entrenado"]).'%">
                              </div>
                          </div>
                          <small>
                          '.nl2br($value["porcentaje_entrenado"]).'% Entrenado
                          </small>
                      </td>
          
                      <td class="project-actions text-right">
                      <button class="btn btn-sm btn-default btnVerEstandar" Url="'.$value["url_pdf"].'" data-toggle="modal" data-target="#modal-ver-estandar">
            <i class="far fa-solid fa-eye"> </i> 
            </button>   
                          <button class="btn btn-sm btn-primary btnSubirEstandar" IdProceso="'.$value["id"].'" data-toggle="modal" data-target="#modal-cargar-personas-estandar">
                          <i class="fas fa-rocket"></i> Entrenar
                          </button>
                          <button class="btn btn-success btn-sm btnEstandarValidado" IdProceso="'.$value["id"].'"  data-toggle="modal" data-target="#modal-cargados-personas-estandar"   >
                          <i class="fas fa-user-check"></i> Entrenados               
                          </button>
                      </td>
                  </tr>     
                 ';}?>         
              </tbody>
          </table>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </section>
    <!-- /.content -->
<!-- jQuery -->
