<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Visualizar Porcentajes</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Inicio</a></li>
        </ol>
      </div>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="card card-secondary">
      <div class="card-header">
        <h3 class="card-title">Seleccione los filtros para visualizar los porcentajes.</h3>
      </div>
      <div class="card-body">


        <!-- Buscador de Área -->
        <div class="row mb-2">
          <div class="col-md-11">
            <label for="area-seleccion" class="mr-3">Área:</label>
            <div class="input-group">
              <input type="text" class="form-control" id="buscar-area" placeholder="Buscar área...">
              <select class="form-control d-none" id="area-seleccion" name="area-seleccion">
                <option value="0">Seleccione un área</option>
                <?php
                $tabla = ModeloEstandar::listarEstandaresCargadosMdl();
                $areas = array();
                if($tabla && is_array($tabla)) {
                  foreach ($tabla as $value) {
                    if(isset($value["area"]) && !in_array($value["area"], $areas)) {
                      $areas[] = $value["area"];
                      $areaValue = htmlspecialchars($value["area"], ENT_QUOTES, 'UTF-8');
                      echo "<option value=\"{$areaValue}\">{$areaValue}</option>";
                    }
                  }
                }
                ?>
              </select>
              <div class="input-group-append">
                <button class="btn btn-info" type="button" id="btn-buscar-area">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
            <div id="resultados-area" class="list-group mt-2" style="position: absolute; z-index: 1000; width: 95%;"></div>
          </div>
        </div>
        <hr>

        <!-- Filtros -->
        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label>Semana</label>
              <input type="text" id="start-date" class="form-control">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label>Supervisor</label>
              <input type="text" class="form-control" id="supervisor">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label>Colaborador</label>
              <div class="input-group">
                <input type="text" class="form-control" id="buscar-colaborador" placeholder="Buscar colaborador...">
                <select class="form-control d-none" id="colaborador" name="colaborador">
                  <option value="0">Seleccione un colaborador</option>
                  <?php
                  $tabla = ModeloPersonas::listarPersonasMdl();
                  if($tabla && is_array($tabla)) {
                    foreach ($tabla as $key => $value) {
                      if(isset($value["nombre"]) && isset($value["apellido"])) {
                        $id = $key; // Usando el key como ID
                        $nombre = htmlspecialchars($value["nombre"], ENT_QUOTES, 'UTF-8');
                        $apellido = htmlspecialchars($value["apellido"], ENT_QUOTES, 'UTF-8');
                        $nombreCompleto = $nombre . ' ' . $apellido;
                        
                        echo sprintf('<option value="%s">%s</option>', 
                          $id,
                          $nombreCompleto
                        );
                      }
                    }
                  }
                  ?>
                </select>
                <div class="input-group-append">
                  <button class="btn btn-info" type="button" id="btn-buscar-colaborador">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
              <div id="resultados-colaborador" class="list-group mt-2" style="position: absolute; z-index: 1000; width: 95%;"></div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label>Turno</label>
              <select class="form-control" id="turno">
                <option value="Día">Día</option>
                <option value="Noche">Noche</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Gráficos -->
        <div class="row mt-4">
            <!-- Gráfico de Tendencia Semanal -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title text-white">Tendencia Semanal por Estándar</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:250px; width: 100%;">
                            <canvas id="graficoSemanal"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Desempeño por Colaborador -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-secondary">
                        <h3 class="card-title text-white">Desempeño por Colaborador</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:250px; width: 100%;">
                            <canvas id="graficoColaborador"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                <!-- Información General -->
                <div class="row mb-3">
          <div class="col-md-6">
            <div class="d-flex align-items-center">
              <h4 class="mr-3 mb-0">Porcentaje General:</h4>
              <span id="porcentaje-general" class="badge bg-secondary">0%</span>
            </div>
          </div>
          <div class="col-md-6 text-right">
            <span id="total-registros" class="text-muted">Total registros: 0</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
$(document).ready(function() {
    // Inicialización de los gráficos
    let graficoSemanal = null;
    let graficoColaborador = null;

    function inicializarGraficos() {
        const ctxSemanal = document.getElementById('graficoSemanal');
        const ctxColaborador = document.getElementById('graficoColaborador');

        if (ctxSemanal) {
            graficoSemanal = new Chart(ctxSemanal, {
                type: 'bar',
                data: {
                    labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Acumulado'],
                    datasets: [
                        {
                            label: 'Turno Día',
                            data: Array(7).fill(0),
                            backgroundColor: '#28a745',
                            borderColor: '#1e7e34',
                            borderWidth: 1,
                            barPercentage: 0.8,
                            categoryPercentage: 0.4
                        },
                        {
                            label: 'Turno Noche',
                            data: Array(7).fill(0),
                            backgroundColor: '#17a2b8',
                            borderColor: '#138496',
                            borderWidth: 1,
                            barPercentage: 0.8,
                            categoryPercentage: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                callback: value => value + '%'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${context.raw}%`;
                                }
                            }
                        }
                    }
                }
            });
        }

        if (ctxColaborador) {
            graficoColaborador = new Chart(ctxColaborador, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: '#17a2b8',
                        barThickness: 15,
                        maxBarThickness: 20
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            beginAtZero: true,
                            max: 100,
                            grid: {
                                display: true
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        },
                        y: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    }

    // Inicializar los gráficos al cargar la página
    inicializarGraficos();

    // Hacer la función disponible globalmente
    window.inicializarGraficos = inicializarGraficos;
    window.graficoSemanal = graficoSemanal;
    window.graficoColaborador = graficoColaborador;

    // 1. Verificar que los elementos canvas existan
    const canvasSemanal = document.getElementById('graficoSemanal');
    const canvasColaborador = document.getElementById('graficoColaborador');

    // 2. Función para actualizar gráficos
    function actualizarGraficos(datos) {
        console.log('Actualizando gráficos con datos:', datos);
        
        // Configuración de colores según porcentaje
        function getColor(porcentaje) {
            if (porcentaje === null || porcentaje === 0) return 'rgba(200, 200, 200, 0.5)'; 
            if (porcentaje < 60) return 'rgba(255, 99, 132, 0.5)';  
            if (porcentaje < 80) return 'rgba(255, 206, 86, 0.5)';  
            return 'rgba(75, 192, 192, 0.5)';  
        }

        // Configurar datos para el gráfico semanal
        const diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Acumulado'];
        
        // Preparar los datos para el gráfico, usando el porcentaje general para el acumulado
        const porcentajeGeneral = datos.porcentajeGeneral || 0;
        const datosGrafico = {
            labels: diasSemana,
            datasets: [
                {
                    label: 'Turno Día',
                    data: [...datos.porcentajesSemana.Día.slice(0, 6), porcentajeGeneral],
                    backgroundColor: '#28a745',
                    borderColor: '#1e7e34',
                    borderWidth: 1,
                    barPercentage: 0.8,
                    categoryPercentage: 0.4
                },
                {
                    label: 'Turno Noche',
                    data: [...datos.porcentajesSemana.Noche.slice(0, 6), null],
                    backgroundColor: '#17a2b8',
                    borderColor: '#138496',
                    borderWidth: 1,
                    barPercentage: 0.8,
                    categoryPercentage: 0.4
                }
            ]
        };

        // Actualizar o crear el gráfico
        if (window.graficoSemanal) {
            window.graficoSemanal.data = datosGrafico;
            window.graficoSemanal.options = {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: value => value + '%'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.dataIndex === 6) {
                                    return `Turnos Día/Noche: ${context.raw}%`;
                                }
                                return `${context.dataset.label}: ${context.raw}%`;
                            }
                        }
                    }
                }
            };
            window.graficoSemanal.update();
        }

        // Actualizar gráfico de colaboradores si hay datos
        if (window.graficoColaborador && datos.colaboradores && datos.colaboradores.length > 0 && datos.porcentajesColaborador) {
            // Obtener nombres de colaboradores desde el select
            const nombresColaboradores = datos.colaboradores.map(id => {
                const option = document.querySelector(`#colaborador option[value="${id}"]`);
                return option ? option.text : `Colaborador ${id}`;
            });

            window.graficoColaborador.data.labels = nombresColaboradores;
            window.graficoColaborador.data.datasets[0].data = datos.porcentajesColaborador;
            window.graficoColaborador.data.datasets[0].backgroundColor = datos.porcentajesColaborador.map(p => 
                !p ? '#dc3545' :  // Rojo para 0 o null
                p >= 80 ? '#28a745' : 
                p >= 60 ? '#ffc107' : 
                '#dc3545'
            );
            window.graficoColaborador.update();
        }

        // Actualizar información adicional
        const porcentajeGeneralElement = document.getElementById('porcentaje-general');
        if (porcentajeGeneralElement) {
            const porcentaje = datos.porcentajeGeneral || 0;
            porcentajeGeneralElement.textContent = `${porcentaje}%`;
            porcentajeGeneralElement.className = `badge ${
                !porcentaje ? 'bg-secondary' :
                porcentaje >= 80 ? 'bg-success' :
                porcentaje >= 60 ? 'bg-warning' :
                'bg-danger'
            }`;
        }

        const totalRegistrosElement = document.getElementById('total-registros');
        if (totalRegistrosElement) {
            totalRegistrosElement.textContent = `Total registros: ${datos.totalRegistros || 0}`;
        }
    }

    // 1. Configuraciones principales de búsqueda
    // Setup del buscador de área (crítico para el funcionamiento)
    function setupBuscadorArea() {
        $('#buscar-area').on('input', function() {
            var busqueda = $(this).val().toLowerCase().trim();
            var resultados = '';
            
            if(busqueda.length > 0) {
                $('#area-seleccion option').each(function() {
                    if($(this).val() !== '0') {
                        var texto = $(this).text().toLowerCase();
                        if(texto.includes(busqueda)) {
                            resultados += `<a href="#" class="list-group-item list-group-item-action" 
                                         data-id="${$(this).val()}">${$(this).text()}</a>`;
                        }
                    }
                });
                
                $('#resultados-area').html(resultados).show();
            } else {
                $('#resultados-area').hide();
            }
        });

        // Click en resultado
        $(document).on('click', '#resultados-area a', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var texto = $(this).text();
            
            $('#buscar-area').val(texto);
            $('#area-seleccion').val(id);
            $('#resultados-area').hide();
            cargarGraficos();
        });

        // Click fuera
        $(document).on('click', function(e) {
            if(!$(e.target).closest('#buscar-area, #resultados-area, #btn-buscar-area').length) {
                $('#resultados-area').hide();
            }
        });

        // Click en botón de búsqueda
        $('#btn-buscar-area').on('click', function() {
            $('#buscar-area').trigger('input');
        });
    }

    // Setup del buscador de colaborador
    function setupBuscadorColaborador() {
        $('#buscar-colaborador').on('input', function() {
            var busqueda = $(this).val().toLowerCase().trim();
            var resultados = '';
            
            if(busqueda.length > 0) {
                $('#colaborador option').each(function() {
                    if($(this).val() !== '0') {
                        var texto = $(this).text().toLowerCase();
                        if(texto.includes(busqueda)) {
                            resultados += `<a href="#" class="list-group-item list-group-item-action" 
                                         data-id="${$(this).val()}"
                                         data-nombre="${$(this).text()}">${$(this).text()}</a>`;
                        }
                    }
                });
                
                if(resultados) {
                    $('#resultados-colaborador').html(resultados).show();
                } else {
                    $('#resultados-colaborador').html('<a href="#" class="list-group-item list-group-item-action disabled">No se encontraron resultados</a>').show();
                }
            } else {
                $('#resultados-colaborador').hide();
            }
        });

        $('#resultados-colaborador').on('click', 'a', function(e) {
            e.preventDefault();
            if($(this).hasClass('disabled')) return;
            
            var id = $(this).data('id');
            var nombre = $(this).data('nombre');
            
            $('#buscar-colaborador').val(nombre);
            $('#colaborador').val(id).trigger('change');
            $('#resultados-colaborador').hide();
        });

        $(document).on('click', function(e) {
            if (!$(e.target).closest('#buscar-colaborador, #resultados-colaborador, #btn-buscar-colaborador').length) {
                $('#resultados-colaborador').hide();
            }
        });

        $('#btn-buscar-colaborador').on('click', function() {
            $('#buscar-colaborador').trigger('input');
            $('#resultados-colaborador').show();
        });
    }

    // 2. Funciones core de carga y actualización de datos
    function cargarGraficos() {
        const area = $('#area-seleccion').val();
        const fecha = $('#start-date').val() || moment().format('YYYY-MM-DD');

        console.log('Fecha seleccionada:', fecha);
        console.log('Área actual:', area);

        if (!area || area === '0') {
            console.warn('Área no seleccionada');
            return;
        }

        const datos = new FormData();
        datos.append("accion", "obtenerDatosGraficos");
        datos.append("area", area);
        datos.append("fecha", fecha);

        $.ajax({
            url: 'ajax/ajaxPorcentajes.php',
            method: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                
                if (response.status === 'success' && response.data) {
                    console.log('Actualizando gráficos con datos:', response.data);
                    actualizarGraficos(response.data);
                } else {
                    console.warn('No se encontraron datos, mostrando valores por defecto');
                    actualizarGraficos({
                        porcentajesSemana: {
                            Día: Array(7).fill(0),
                            Noche: Array(7).fill(0)
                        },
                        porcentajeGeneral: 0,
                        totalRegistros: 0
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la petición:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar los datos del servidor'
                });
            }
        });
    }

    // Separar la carga de gráficos en dos funciones
    function cargarGraficoTendencia() {
        const area = $('#area-seleccion').val();
        const fecha = $('#start-date').val() || moment().format('YYYY-MM-DD');

        if (!area || area === '0') return;

        const datos = new FormData();
        datos.append("accion", "obtenerDatosTendencia");
        datos.append("area", area);
        datos.append("fecha", fecha);

        $.ajax({
            url: 'ajax/ajaxPorcentajes.php',
            method: 'POST',
            data: datos,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.status === 'success' && response.data) {
                    actualizarGraficoTendencia(response.data);
                }
            }
        });
    }

    function cargarGraficoColaborador(datos) {
        if (window.graficoColaborador) {
            const datosGrafico = {
                labels: datos.map(d => d.nombre_colaborador),
                datasets: [{
                    data: datos.map(d => d.porcentaje_promedio),
                    backgroundColor: datos.map(d => {
                        const porcentaje = d.porcentaje_promedio;
                        return !porcentaje ? '#dc3545' :  // Rojo para 0
                               porcentaje >= 80 ? '#28a745' : // Verde >= 80%
                               porcentaje >= 60 ? '#ffc107' : // Amarillo >= 60%
                               '#dc3545'; // Rojo < 60%
                    }),
                    barThickness: 20,
                    maxBarThickness: 25
                }]
            };

            window.graficoColaborador.data = datosGrafico;
            window.graficoColaborador.options = {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            display: true
                        },
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const colaborador = datos[context.dataIndex];
                                return `${colaborador.nombre_colaborador}: ${colaborador.porcentaje_promedio}% (${colaborador.total_registros} registros)`;
                            }
                        }
                    }
                }
            };
            window.graficoColaborador.update();
        }
    }

    // Función para cargar colaboradores por área
    function cargarColaboradoresPorArea() {
        const area = $('#area-seleccion').val();
        
        if (!area || area === '0') return;

        $.ajax({
            url: 'ajax/ajaxPorcentajes.php',
            method: 'POST',
            data: {
                accion: 'obtenerDatosColaborador',
                area: area,
                fecha: $('#start-date').val() || moment().format('YYYY-MM-DD')
            },
            success: function(response) {
                if (response.status === 'success' && response.data) {
                    actualizarGraficoColaborador(response.data);
                }
            }
        });
    }

    // Función para aplicar filtros
    function aplicarFiltros() {
        const area = $('#area-seleccion').val();
        const fecha = $('#start-date').val() || moment().format('YYYY-MM-DD');
        const supervisor = $('#supervisor').val();
        const colaborador = $('#colaborador').val();
        const turno = $('#turno').val();

        if (!area || area === '0') return;

        $.ajax({
            url: 'ajax/ajaxPorcentajes.php',
            method: 'POST',
            data: {
                accion: 'obtenerDatosColaborador',
                area: area,
                fecha: fecha,
                supervisor: supervisor || null,
                colaborador: colaborador || null,
                turno: turno || null
            },
            success: function(response) {
                if (response.status === 'success' && response.data) {
                    actualizarGraficoColaborador(response.data);
                }
            }
        });
    }

    // Event listeners
    $('#area-seleccion').on('change', function() {
        cargarColaboradoresPorArea();
    });

    $('#supervisor, #colaborador, #turno').on('change', function() {
        aplicarFiltros();
    });

    // 5. Configuración de datepicker
    $("#start-date").datepicker({
        dateFormat: "yy-mm-dd",
        onSelect: function(dateText) {
            cargarGraficos();
        }
    }).datepicker("setDate", new Date());

    // 6. Funciones auxiliares
    function setupPorcentaje() {
        $("#num-chequeos, #chequeos-correctos").on('input', function() {
            var numChequeos = parseInt($("#num-chequeos").val()) || 0;
            var chequeosCorrectos = parseInt($("#chequeos-correctos").val()) || 0;
            
            // Validar que correctos no sea mayor que total
            if(chequeosCorrectos > numChequeos) {
                chequeosCorrectos = numChequeos;
                $("#chequeos-correctos").val(numChequeos);
            }
            
            // Calcular porcentaje
            var porcentaje = numChequeos > 0 ? Math.round((chequeosCorrectos / numChequeos) * 100) : 0;
            
            // Actualizar campos
            $("#porcentaje-cumplimiento").val(porcentaje);
            $("#barra-progreso")
                .css('width', porcentaje + '%')
                .attr('aria-valuenow', porcentaje)
                .text(porcentaje + '%')
                .removeClass('bg-success bg-warning bg-danger')
                .addClass(porcentaje >= 80 ? 'bg-success' : 
                         porcentaje >= 50 ? 'bg-warning' : 'bg-danger');
        });
    }

    function setupBuscadorEstandar() {
        const $input = $('#buscar-estandar');
        const $resultados = $('#resultados-busqueda');
        const $btn = $('#btn-buscar-estandar');

        $input.on('input', function() {
            const busqueda = $(this).val().toLowerCase().trim();
            
            if(busqueda.length > 0) {
                $resultados.find('a').each(function() {
                    const texto = $(this).text().toLowerCase();
                    $(this).toggle(texto.includes(busqueda));
                });
                $resultados.show();
            } else {
                $resultados.hide();
            }
        });

        // Click en resultado
        $resultados.on('click', 'a', function(e) {
            e.preventDefault();
            const $this = $(this);
            const id = $this.data('id');
            const texto = $this.text();
            
            $input.val(texto).data('id', id);
            $resultados.hide();
            cargarGraficos();
        });

        // Click en botón de búsqueda
        $btn.on('click', function() {
            $resultados.toggle();
        });

        // Click fuera
        $(document).on('click', function(e) {
            if(!$(e.target).closest('#buscar-estandar, #resultados-busqueda, #btn-buscar-estandar').length) {
                $resultados.hide();
            }
        });
    }

    // 7. Inicializaciones
    setupBuscadorArea();
    setupBuscadorColaborador();
    setupBuscadorEstandar();

    // Función para probar la conexión y datos
    function probarConexion() {
        const datos = {
            area: $('#area-seleccion').val(),
            fecha: $('#start-date').val() || new Date().toISOString().split('T')[0],
            supervisor: $('#supervisor').val(),
            colaborador: $('#colaborador').val(),
            turno: $('#turno').val()
        };

        console.log('Datos de prueba:', datos);

        $.ajax({
            url: 'ajax/ajaxPorcentajes.php',
            method: 'POST',
            data: {
                accion: 'obtenerDatosGraficos',
                ...datos
            },
            success: function(response) {
                console.log('Respuesta del servidor:', response);
                
                if (response.status === 'success' && response.data) {
                    console.log('Datos recibidos correctamente');
                    console.log('Datos semanales:', response.data.porcentajesSemana);
                    console.log('Datos colaboradores:', response.data.colaboradores);
                    
                    actualizarGraficos(response.data);
                } else {
                    console.error('Error en la respuesta:', response);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Error al obtener los datos'
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error('Error en la petición:', {xhr, status, error});
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error en la comunicación con el servidor'
                });
            }
        });
    }

    // Cargar datos iniciales cuando se carga la página
    if ($('#area-seleccion').val()) {
        cargarColaboradoresPorArea();
    }

});
</script>

<style>
#resultados-colaborador {
    max-height: 300px;
    overflow-y: auto;
    display: none;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

#resultados-colaborador a {
    padding: 8px 15px;
    border-bottom: 1px solid #eee;
}

#resultados-colaborador a:hover {
    background-color: #f8f9fa;
}

#resultados-busqueda {
    max-height: 300px;
    overflow-y: auto;
    display: none;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

#resultados-busqueda a {
    padding: 8px 15px;
    border-bottom: 1px solid #eee;
    white-space: normal;
    word-break: break-word;
}

#resultados-busqueda a:hover {
    background-color: #f8f9fa;
    text-decoration: none;
}

#resultados-area {
    max-height: 300px;
    overflow-y: auto;
    display: none;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

#resultados-area a {
    padding: 8px 15px;
    border-bottom: 1px solid #eee;
}

#resultados-area a:hover {
    background-color: #f8f9fa;
}
</style>

