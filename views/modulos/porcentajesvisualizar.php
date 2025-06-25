<?php
// porcentajesvisualizar.php
// (Asegúrate de tener ya tu autoload o includes de sesión, modelos, cabeceras, etc.)
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Visualizar Porcentajes</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <style>
        /* estilo para los resultados desplegables */
        .list-group {
            max-height: 300px;
            overflow: auto;
            background: #fff;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>

    <section class="content-header">
        <div class="container-fluid">
            <h1>Visualizar Porcentajes</h1>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Seleccione los filtros para visualizar los porcentajes.</h3>
            </div>
            <div class="card-body">

                <!-- BUSCADOR ÁREA -->
                <div class="row mb-2">
                    <div class="col-md-11">
                        <label>Área:</label>
                        <div class="input-group">
                            <select id="area-seleccion" class="form-control" name="area-seleccion">
                                <option value="0">Seleccione un área</option>
                                <?php
                                // Obtiene el listado de estándares (ya devuelve id_area y area)
                                $planta_id = $_SESSION['planta_id'];
                                $tabla = ModeloEstandar::listarEstandaresCargadosMdl($planta_id);
                                $seen = [];
                                if ($tabla && is_array($tabla)) {
                                    foreach ($tabla as $v) {
                                        // Aseguramos que existan id_area y area, y no repitamos el mismo ID
                                        if (isset($v['id_area'], $v['area']) && !in_array($v['id_area'], $seen)) {
                                            $seen[] = $v['id_area'];
                                            $id = (int) $v['id_area'];
                                            $name = htmlspecialchars($v['area'], ENT_QUOTES, 'UTF-8');
                                            echo "<option value=\"{$id}\">{$name}</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                            <div class="input-group-append">
                                <button id="btn-buscar-area" class="btn btn-info">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div id="resultados-area" class="list-group mt-2" style="display:none;"></div>
                    </div>
                </div>
                <hr>

                <!-- FILTROS ADICIONALES -->
                <div class="row mb-4">
                    <div class="col-sm-3">
                        <label>Semana</label>
                        <input type="text" id="start-date" class="form-control">
                    </div>
                    <div class="col-sm-3">
                        <label>Supervisor</label>
                        <div class="input-group">
                            <select id="supervisor" name="supervisor" class="form-control">
                                <option value="">Seleccione un supervisor</option>
                                <?php
                                // Traer sólo el array de nombres
                                $supers = ModeloUsuario::listarSupervisoresMdl($_SESSION['planta_id']);
                                foreach ($supers as $nombreSupervisor) {
                                    $safe = htmlspecialchars($nombreSupervisor, ENT_QUOTES, 'UTF-8');
                                    echo "<option value=\"{$safe}\">{$safe}</option>";
                                }
                                ?>
                            </select>
                            <div class="input-group-append">
                                <button id="btn-buscar-supervisor" class="btn btn-info">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <label>Colaborador</label>
                        <div class="input-group">
                            <!--<input type="text" id="buscar-colaborador" class="form-control"
                                placeholder="Buscar colaborador…"> -->
                            <select id="colaborador" class="form-control" name="colaborador">
                                <option value="0">Seleccione un colaborador</option>
                                <?php
                                $planta_id = $_SESSION['planta_id'];
                                $pers = ModeloPersonas::listarPersonasMdl($planta_id);
                                if ($pers && is_array($pers)) {
                                    foreach ($pers as $v) {
                                        $rut = htmlspecialchars($v['rut'], ENT_QUOTES, 'UTF-8');
                                        $n = htmlspecialchars($v['nombre'], ENT_QUOTES, 'UTF-8');
                                        $a = htmlspecialchars($v['apellido'], ENT_QUOTES, 'UTF-8');
                                        echo "<option value=\"{$rut}\">{$n} {$a}</option>";
                                    }
                                }
                                ?>
                            </select>
                            <div class="input-group-append">
                                <button id="btn-buscar-colaborador" class="btn btn-info">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div id="resultados-colaborador" class="list-group mt-2" style="display:none;"></div>
                    </div>
                    <div class="col-sm-3">
                        <label>Turno</label>
                        <select id="turno" class="form-control">
                            <option value="Día">Día</option>
                            <option value="Noche">Noche</option>
                        </select>
                    </div>
                </div>

                <!-- GRÁFICOS -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                Tendencia Semanal
                            </div>
                            <div class="card-body"><canvas id="graficoSemanal" height="250"></canvas></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                Desempeño por Colaborador
                            </div>
                            <div class="card-body"><canvas id="graficoColaborador" height="250"></canvas></div>
                        </div>
                    </div>
                </div>

                <!-- INFO GENERAL -->
                <div class="row mt-3">
                    <div class="col-md-6 d-flex align-items-center">
                        <h4 class="mr-2 mb-0">Porcentaje General:</h4>
                        <span id="porcentaje-general" class="badge bg-secondary">0%</span>
                    </div>
                    <div class="col-md-6 text-right">
                        <span id="total-registros">Total registros: 0</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3"></script>

    <script>
        $(document).ready(function () {
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
                                    ticks: { callback: value => value + '%' }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top'
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
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
                                        callback: function (value) {
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

                if ($('#area-seleccion').val() && $('#area-seleccion').val() !== '0') {
                    cargarGraficos();
                    cargarColaboradoresPorArea();
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
                                    label: function (context) {
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
                    porcentajeGeneralElement.className = `badge ${!porcentaje ? 'bg-secondary' :
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
                $('#buscar-area').on('input', function () {
                    var busqueda = $(this).val().toLowerCase().trim();
                    var resultados = '';

                    if (busqueda.length > 0) {
                        $('#area-seleccion option').each(function () {
                            if ($(this).val() !== '0') {
                                var texto = $(this).text().toLowerCase();
                                if (texto.includes(busqueda)) {
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
                $(document).on('click', '#resultados-area a', function (e) {
                    e.preventDefault();
                    var rut = $(this).data('id');
                    var texto = $(this).text();

                    $('#buscar-area').val(texto);
                    $('#area-seleccion').val(id);
                    $('#resultados-area').hide();
                    cargarGraficos();
                });

                // Click fuera
                $(document).on('click', function (e) {
                    if (!$(e.target).closest('#buscar-area, #resultados-area, #btn-buscar-area').length) {
                        $('#resultados-area').hide();
                    }
                });

                // Click en botón de búsqueda
                $('#btn-buscar-area').on('click', function () {
                    $('#buscar-area').trigger('input');
                });
            }

            // Setup del buscador de colaborador
            function setupBuscadorColaborador() {
                $('#buscar-colaborador').on('input', function () {
                    var busqueda = $(this).val().toLowerCase().trim();
                    var resultados = '';

                    if (busqueda.length > 0) {
                        $('#colaborador option').each(function () {
                            if ($(this).val() !== '0') {
                                var texto = $(this).text().toLowerCase();
                                if (texto.includes(busqueda)) {
                                    resultados += `<a href="#" class="list-group-item list-group-item-action" 
                                         data-id="${$(this).val()}"
                                         data-nombre="${$(this).text()}">${$(this).text()}</a>`;
                                }
                            }
                        });

                        if (resultados) {
                            $('#resultados-colaborador').html(resultados).show();
                        } else {
                            $('#resultados-colaborador').html('<a href="#" class="list-group-item list-group-item-action disabled">No se encontraron resultados</a>').show();
                        }
                    } else {
                        $('#resultados-colaborador').hide();
                    }
                });

                $('#resultados-colaborador').on('click', 'a', function (e) {
                    e.preventDefault();
                    if ($(this).hasClass('disabled')) return;

                    var id = $(this).data('id');
                    var nombre = $(this).data('nombre');

                    $('#buscar-colaborador').val(nombre);
                    $('#colaborador').val(rut).trigger('change');
                    $('#resultados-colaborador').hide();
                });

                $(document).on('click', function (e) {
                    if (!$(e.target).closest('#buscar-colaborador, #resultados-colaborador, #btn-buscar-colaborador').length) {
                        $('#resultados-colaborador').hide();
                    }
                });

                $('#btn-buscar-colaborador').on('click', function () {
                    $('#buscar-colaborador').trigger('input');
                    $('#resultados-colaborador').show();
                });
            }

            // 2. Funciones core de carga y actualización de datos
            function cargarGraficos() {
                const filtros = {
                    accion: 'obtenerDatosGraficos',
                    area: $('#area-seleccion').val(),  // ahora ID numérico
                    fecha: $('#start-date').val() || moment().format('YYYY-MM-DD'),
                    supervisor: $('#supervisor').val() || null,
                    colaborador: $('#colaborador').val() || null,
                    turno: $('#turno').val() || null
                };

                if (!filtros.area || filtros.area === '0') {
                    return Swal.fire('Aviso', 'Debe seleccionar un área', 'warning');
                }

                $.post('ajax/ajaxPorcentajes.php', filtros, function (response) {
                    if (response.status === 'success') {
                        actualizarGraficos(response.data);
                    } else {
                        Swal.fire('Error', response.message || 'No hay datos', 'error');
                    }
                }, 'json')
                    .fail(() => Swal.fire('Error', 'No se pudo cargar datos', 'error'));
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
                    success: function (response) {
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
                                    callback: function (value) {
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
                                    label: function (context) {
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
                    success: function (response) {
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
                    success: function (response) {
                        if (response.status === 'success' && response.data) {
                            actualizarGraficoColaborador(response.data);
                        }
                    }
                });
            }

            $('#area-seleccion, #start-date, #supervisor, #colaborador, #turno')
                .on('change input', cargarGraficos);

            // 5. Configuración de datepicker
            $("#start-date").datepicker({
                dateFormat: "yy-mm-dd",
                onSelect: function (dateText) {
                    cargarGraficos();
                }
            }).datepicker("setDate", new Date());

            // 6. Funciones auxiliares
            function setupPorcentaje() {
                $("#num-chequeos, #chequeos-correctos").on('input', function () {
                    var numChequeos = parseInt($("#num-chequeos").val()) || 0;
                    var chequeosCorrectos = parseInt($("#chequeos-correctos").val()) || 0;

                    // Validar que correctos no sea mayor que total
                    if (chequeosCorrectos > numChequeos) {
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

                $input.on('input', function () {
                    const busqueda = $(this).val().toLowerCase().trim();

                    if (busqueda.length > 0) {
                        $resultados.find('a').each(function () {
                            const texto = $(this).text().toLowerCase();
                            $(this).toggle(texto.includes(busqueda));
                        });
                        $resultados.show();
                    } else {
                        $resultados.hide();
                    }
                });

                // Click en resultado
                $resultados.on('click', 'a', function (e) {
                    e.preventDefault();
                    const $this = $(this);
                    const id = $this.data('id');
                    const texto = $this.text();

                    $input.val(texto).data('id', id);
                    $resultados.hide();
                    cargarGraficos();
                });

                // Click en botón de búsqueda
                $btn.on('click', function () {
                    $resultados.toggle();
                });

                // Click fuera
                $(document).on('click', function (e) {
                    if (!$(e.target).closest('#buscar-estandar, #resultados-busqueda, #btn-buscar-estandar').length) {
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
                    success: function (response) {
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
                    error: function (xhr, status, error) {
                        console.error('Error en la petición:', { xhr, status, error });
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #resultados-area a {
            padding: 8px 15px;
            border-bottom: 1px solid #eee;
        }

        #resultados-area a:hover {
            background-color: #f8f9fa;
        }
    </style>