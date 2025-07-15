<?php
// porcentajesvisualizar.php
// (Incluye aquí tu autoload, sesión, modelos, cabeceras, etc.)
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Visualizar Porcentajes</title>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
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
                <!-- FILTRO ÁREA -->
                <div class="row mb-2">
                    <div class="col-md-11">
                        <label>Área:</label>
                        <div class="input-group">
                            <select id="area-seleccion" class="form-control">
                                <option value="0">Seleccione un área</option>
                                <?php
                                $planta_id = $_SESSION['planta_id'];
                                $tabla = ModeloEstandar::listarEstandaresCargadosMdl($planta_id);
                                $seen = [];
                                if (is_array($tabla)) {
                                    foreach ($tabla as $v) {
                                        if (isset($v['id_area'], $v['area']) && !in_array($v['id_area'], $seen)) {
                                            $seen[] = $v['id_area'];
                                            $id = (int) $v['id_area'];
                                            $name = htmlspecialchars($v['area'], ENT_QUOTES, 'UTF-8');
                                            echo "<option value=\"$id\">$name</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                            <div class="input-group-append">
                                <button id="btn-buscar-area" class="btn btn-info"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
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
                        <select id="supervisor" class="form-control">
                            <option value="">Seleccione un supervisor</option>
                            <?php
                            $supers = ModeloUsuario::listarSupervisoresMdl($_SESSION['planta_id']);
                            foreach ($supers as $sup) {
                                $s = htmlspecialchars($sup, ENT_QUOTES, 'UTF-8');
                                echo "<option value=\"$s\">$s</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <label>Colaborador</label>
                        <select id="colaborador" class="form-control">
                            <option value="0">Seleccione un colaborador</option>
                            <?php
                            $pers = ModeloPersonas::listarPersonasMdl($_SESSION['planta_id']);
                            foreach ($pers as $p) {
                                $rut = htmlspecialchars($p['rut'], ENT_QUOTES, 'UTF-8');
                                $nom = htmlspecialchars($p['nombre'] . ' ' . $p['apellido'], ENT_QUOTES, 'UTF-8');
                                echo "<option value=\"$rut\">$nom</option>";
                            }
                            ?>
                        </select>
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
                            <div class="card-header bg-secondary text-white">Tendencia Semanal</div>
                            <div class="card-body"><canvas id="graficoSemanal" height="250"></canvas></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-secondary text-white">Desempeño por Colaborador</div>
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

    <!-- Dependencias JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/min/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

    <script>
        Chart.register(ChartDataLabels);

        let graficoSemanal = null;
        let graficoColaborador = null;
        let inicializadoSemanal = false;
        let inicializadoColab = false;

        function inicializarGraficos() {
            const area = $('#area-seleccion').val();
            const ctxS = document.getElementById('graficoSemanal');
            const ctxC = document.getElementById('graficoColaborador');

            if (ctxS && !inicializadoSemanal) {
                inicializadoSemanal = true;
                graficoSemanal = new Chart(ctxS, {
                    type: 'bar',
                    data: {
                        labels: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Acumulado'],
                        datasets: [
                            { label: 'Turno Día', data: Array(7).fill(0), backgroundColor: '#28a745', barPercentage: 1, categoryPercentage: 1 },
                            { label: 'Turno Noche', data: Array(7).fill(0), backgroundColor: '#17a2b8', barPercentage: 1, categoryPercentage: 1 }
                        ]
                    },
                    plugins: [ChartDataLabels],
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true, max: 100, ticks: { stepSize: 10, callback: v => v + '%' } } },
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.raw}%` } },
                            datalabels: { color: 'white', anchor: 'end', align: 'start', display: ctx => ctx.raw > 0, formatter: v => v + '%' }
                        }
                    }
                });
            }

            if (ctxC && !inicializadoColab) {
                inicializadoColab = true;
                graficoColaborador = new Chart(ctxC, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets: [{
                            label: 'Colaboradores',
                            data: [],
                            backgroundColor: '#17a2b8',
                            barThickness: 40, maxBarThickness: 80,
                            barPercentage: 1, categoryPercentage: 1
                        }]
                    },
                    plugins: [ChartDataLabels],
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        scales: {
                            x: { grid: { display: false } },
                            y: { beginAtZero: true, max: 100, ticks: { stepSize: 10, callback: v => v + '%' }, grid: { color: '#e4e4e4' } }
                        },
                        plugins: {
                            legend: { display: false },
                            datalabels: { color: 'white', anchor: 'end', align: 'start', display: ctx => ctx.dataset.data[ctx.dataIndex] > 0, formatter: v => v + '%' }
                        }
                    }
                });
            }

            if (area !== '0') {
                cargarGraficos();
                cargarColaboradoresPorArea();
            }
        }

        function cargarGraficos() {
            const area = $('#area-seleccion').val();
            if (area === '0') return;
            const filtros = {
                accion: 'obtenerDatosGraficos',
                area: area,
                fecha: $('#start-date').val() || moment().format('YYYY-MM-DD'),
                supervisor: $('#supervisor').val() || null,
                colaborador: null, // siempre null en tendencia
                turno: $('#turno').val() || null
            };
            $.post('/SistemaEstandaresAquaChile/ajax/ajaxPorcentajes.php', filtros, function (resp) {
                if (resp.status === 'success') {
                    const d = resp.data;
                    const pg = d.porcentajeGeneral || 0;
                    // actualiza Semanal
                    graficoSemanal.data.datasets[0].data = [...d.porcentajesSemana.Día.slice(0, 6), pg];
                    graficoSemanal.data.datasets[1].data = [...d.porcentajesSemana.Noche.slice(0, 6), null];
                    graficoSemanal.update();
                    // badge
                    $('#porcentaje-general')
                        .text(pg + '%')
                        .removeClass('bg-secondary bg-success bg-warning bg-danger')
                        .addClass(pg >= 80 ? 'bg-success' : pg >= 60 ? 'bg-warning' : 'bg-danger');
                    $('#total-registros').text('Total registros: ' + (d.totalRegistros || 0));
                } else {
                    Swal.fire('Error', resp.message || 'No hay datos', 'error');
                }
            }, 'json').fail(() => {
                Swal.fire('Error', 'No se pudo cargar datos', 'error');
            });
        }

        function cargarColaboradoresPorArea() {
            const area = $('#area-seleccion').val();
            if (area === '0') {
                graficoColaborador.data.labels = [];
                graficoColaborador.data.datasets[0].data = [];
                return graficoColaborador.update();
            }

            const colSel = $('#colaborador').val();
            const filtros = {
                accion: 'obtenerDatosColaborador',
                area: area,
                fecha: $('#start-date').val() || moment().format('YYYY-MM-DD'),
                supervisor: $('#supervisor').val() || null,
                colaborador: (colSel !== '0' ? colSel : null),
                turno: $('#turno').val() || null
            };

            $.post('/SistemaEstandaresAquaChile/ajax/ajaxPorcentajes.php', filtros, function (res) {
                if (res.status === 'success') {
                    // Usar directamente los nombres desde la respuesta
                    const labels = res.data.labels || [];
                    const valores = (res.data.porcentajesColaborador || []).map(v => Math.round(v));
                    graficoColaborador.data.labels = labels;
                    graficoColaborador.data.datasets[0].data = valores;
                    graficoColaborador.update();
                } else {
                    console.error('Error Carga Colab:', res.message);
                }
            }, 'json').fail(() => {
                Swal.fire('Error', 'No se pudo cargar colaboradores', 'error');
            });
        }

        $(function () {
            $("#start-date")
                .datepicker({
                    dateFormat: 'yy-mm-dd',
                    onSelect: function (dateText) {
                        cargarGraficos();
                        cargarColaboradoresPorArea();
                    }
                })
                .datepicker("setDate", new Date());

            $('#area-seleccion, #supervisor, #colaborador, #turno')
                .on('change', () => { cargarGraficos(); cargarColaboradoresPorArea(); });

            inicializarGraficos();
        });
    </script>

</body>

</html>

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