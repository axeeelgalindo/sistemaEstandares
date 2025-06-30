<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Editar Porcentajes</h1>
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
        <h3 class="card-title">Seleccione un estandar para editar su porcentaje de aprobación.</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
        <div class="row mb-2">
          <div class="col-md-11">
            <label for="estandar-seleccion" class="mr-3">Estandar seleccionado:</label>
            <div class="input-group">
              <input type="text" class="form-control" id="buscar-estandar" placeholder="Buscar estándar...">
              <select class="form-control d-none" id="estandar-seleccion" name="estandar-seleccion">
                <option value="0">Seleccione un estándar</option>
                <?php
                $tabla = ModeloEstandar::listarEstandaresMdl($_SESSION['planta_id']);
                if ($tabla && is_array($tabla)) {
                  foreach ($tabla as $key => $value) {
                    if (isset($value["id"]) && isset($value["nombre"])) {
                      $id = htmlspecialchars($value["id"], ENT_QUOTES, 'UTF-8');
                      $nombre = htmlspecialchars($value["nombre"], ENT_QUOTES, 'UTF-8');
                      echo sprintf('<option value="%s">%s</option>', $id, $nombre);
                    }
                  }
                }
                ?>
              </select>
              <div class="input-group-append">
                <button class="btn btn-info" type="button" id="btn-buscar-estandar">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
            <div id="resultados-busqueda" class="list-group mt-2"
              style="position: absolute; z-index: 1000; width: 95%;"></div>
          </div>
        </div>
        <hr>

        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label>Fecha</label>
              <input type="text" id="start-date" class="form-control">
            </div>
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
            <div class="form-group">
              <label>Colaborador</label>
              <div class="input-group">
                <input type="text" class="form-control" id="buscar-colaborador" placeholder="Buscar colaborador...">
                <select class="form-control d-none" id="colaborador" name="colaborador">
                  <option value="0">Seleccione un colaborador</option>
                  <?php
                  $planta_id = $_SESSION['planta_id'];
                  $tabla = ModeloPersonas::listarPersonasMdl($planta_id);
                  if ($tabla && is_array($tabla)) {
                    foreach ($tabla as $key => $value) {
                      if (isset($value["nombre"]) && isset($value["apellido"])) {
                        $rut = htmlspecialchars($value['rut'], ENT_QUOTES, 'UTF-8');
                        $nombre = htmlspecialchars($value["nombre"], ENT_QUOTES, 'UTF-8');
                        $apellido = htmlspecialchars($value["apellido"], ENT_QUOTES, 'UTF-8');
                        $nombreCompleto = $nombre . ' ' . $apellido;

                        echo sprintf(
                          '<option value="%s">%s</option>',
                          $rut,
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
              <div id="resultados-colaborador" class="list-group mt-2"
                style="position: absolute; z-index: 1000; width: 95%;"></div>
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
        <hr>

        <div class="row">
          <div class="col-sm-3">
            <div class="form-group">
              <label>N° Chequeos</label>
              <input type="number" class="form-control" id="num-chequeos">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label>Chequeos Correctos</label>
              <input type="number" class="form-control" id="chequeos-correctos">
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label>% Cumplimiento</label>
              <input type="number" class="form-control" id="porcentaje-cumplimiento" readonly>
              <div class="progress mt-2" style="height: 20px;">
                <div class="progress-bar bg-success" id="barra-progreso" role="progressbar" style="width: 0%;"
                  aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
              </div>
              <div id="info-adquisicion"></div>
            </div>
          </div>
          <div class="col-sm-3">
            <div class="form-group">
              <label>Comentarios</label>
              <input type="text" class="form-control" id="comentarios">
            </div>
          </div>
        </div>

        <a href="#" class="btn btn-primary btn-block" data-toggle="modal"
          data-target="#modal-editar-usuariop"><b>Actualizar</b></a>
      </div>
      <!-- /.card-body -->
    </div>
  </div>
</section>

<!-- Agregar este modal al final del archivo, antes de los scripts -->
<div class="modal fade" id="modal-editar-usuariop">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Confirmar Actualización</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>¿Está seguro de actualizar los datos?</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btn-confirmar-actualizacion">Confirmar</button>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function () {
    // Función para el buscador (estándar y colaborador)
    function setupBuscador(inputId, selectId, resultadosId, btnId) {
      $(`#${inputId}`).on('input', function () {
        var busqueda = $(this).val().toLowerCase().trim();
        var resultados = '';

        if (busqueda.length > 0) {
          // Obtener todas las opciones del select correspondiente
          $(`#${selectId} option`).each(function () {
            if ($(this).val() !== '0') {
              var texto = $(this).text().toLowerCase();
              if (texto.includes(busqueda)) {
                resultados += `<a href="#" class="list-group-item list-group-item-action" 
                                         data-id="${$(this).val()}"
                                         data-nombre="${$(this).text()}">${$(this).text()}</a>`;
              }
            }
          });

          $(`#${resultadosId}`).html(resultados).show();
        } else {
          $(`#${resultadosId}`).hide();
        }
      });

      // Click en resultado
      $(`#${resultadosId}`).on('click', 'a', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');

        $(`#${inputId}`).val(nombre);
        $(`#${selectId}`).val(id).trigger('change');
        $(`#${resultadosId}`).hide();
      });

      // Click fuera
      $(document).mouseup(function (e) {
        var container = $(`#${resultadosId}, #${inputId}, #${btnId}`);
        if (!container.is(e.target) && container.has(e.target).length === 0) {
          $(`#${resultadosId}`).hide();
        }
      });

      // Click en botón de búsqueda
      $(`#${btnId}`).on('click', function () {
        $(`#${inputId}`).trigger('input');
      });

      // Limpiar búsqueda cuando el select cambia
      $(`#${selectId}`).on('change', function () {
        if ($(this).val() === '0') {
          $(`#${inputId}`).val('');
        }
      });
    }

    // Función específica para el buscador de colaborador
    function setupBuscadorColaborador() {
      $('#buscar-colaborador').on('keyup', function () {
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

          $('#resultados-colaborador').html(resultados);
          $('#resultados-colaborador').show();
        } else {
          $('#resultados-colaborador').hide();
        }
      });

      $('#resultados-colaborador').on('click', 'a', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');

        $('#buscar-colaborador').val(nombre);
        $('#colaborador').val(id);
        $('#resultados-colaborador').hide();
      });

      $(document).on('click', function (e) {
        if (!$(e.target).closest(
          '#buscar-colaborador, #resultados-colaborador, #btn-buscar-colaborador').length) {
          $('#resultados-colaborador').hide();
        }
      });

      $('#btn-buscar-colaborador').on('click', function () {
        var busqueda = $('#buscar-colaborador').val();
        if (busqueda.length > 0) {
          $('#buscar-colaborador').trigger('keyup');
        }
      });
    }

    // Función para el cálculo de porcentaje
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
            
            // Determinar si el estándar está adquirido (se permite fallar en máximo 1)
            var estandarAdquirido = chequeosCorrectos >= (numChequeos - 1) && numChequeos > 0;
            
            // Actualizar campos
            $("#porcentaje-cumplimiento").val(porcentaje);
            
            // Actualizar barra de progreso con color según adquisición
            $("#barra-progreso")
                .css('width', porcentaje + '%')
                .attr('aria-valuenow', porcentaje)
                .text(porcentaje + '% ' + (estandarAdquirido ? '(Adquirido)' : '(No adquirido)'))
                .removeClass('bg-success bg-warning bg-danger')
                .addClass(estandarAdquirido ? 'bg-success' : 'bg-danger');
            
            // Mostrar mensaje informativo sobre la regla de adquisición
            var mensajeRequerido = numChequeos > 0 ? (numChequeos - 1) : 0;
            $("#info-adquisicion").html(`
                <div class="alert alert-info mt-2">
                    <i class="fas fa-info-circle"></i> 
                    Para que el estándar se considere adquirido, se requieren al menos 
                    <strong>${mensajeRequerido}</strong> chequeos correctos de <strong>${numChequeos}</strong>.
                    <br>
                    Estado actual: <strong>${estandarAdquirido ? 'Adquirido' : 'No adquirido'}</strong>
                </div>
            `);
        });
    }

    setupBuscador(
      'buscar-estandar',     // el <input> donde escribes
      'estandar-seleccion',  // el <select> oculto que guarda el id
      'resultados-busqueda', // el contenedor donde salen las sugerencias
      'btn-buscar-estandar'  // el botón “lupa”
    );

    // autocomplete de supervisores
    setupBuscador(
      'buscar-supervisor',      // el input de supervisor
      'supervisor',             // el select oculto con los nombres
      'resultados-supervisor',  // contenedor de sugerencias supervisor
      'btn-buscar-supervisor'   // el botón de supervisor
    );

    setupBuscadorColaborador();
    setupPorcentaje();

    // Inicializar datepicker
    $("#start-date").datepicker({
      dateFormat: "yy-mm-dd"  // de ahora en adelante te da "2025-06-24"
    });

    // Validación antes de enviar el formulario
    $('[data-target="#modal-editar-usuariop"]').on('click', function (e) {
      e.preventDefault();

      // Validar campos requeridos
      var estandar = $('#estandar-seleccion').val();
      var fecha = $('#start-date').val();
      var supervisor = $('#supervisor').val();
      var colaborador = $('#colaborador').val();
      var numChequeos = $('#num-chequeos').val();
      var chequeosCorrectos = $('#chequeos-correctos').val();

      if (estandar === '0' || !fecha || !supervisor || colaborador === '0' || !numChequeos || !
        chequeosCorrectos) {
        Swal.fire({
          title: 'Error',
          text: 'Por favor complete todos los campos requeridos',
          icon: 'error'
        });
        return false;
      }

      // Si todo está correcto, abrir el modal
      $('#modal-editar-usuariop').modal('show');
    });

    // Manejar el click en el botón confirmar
    $('#btn-confirmar-actualizacion').on('click', function () {
      // Validar datos antes de enviar
      var estandarId = $('#estandar-seleccion').val();

      // Crear objeto de datos
      var datos = {
        accion: 'actualizar',
        id_estandar: estandarId, // Cambiar nombre del campo
        fecha: $('#start-date').val().trim(),
        supervisor: $('#supervisor').val().trim(),
        rut: $('#colaborador').val().trim(),    // aquí metes el RUT
        turno: $('#turno').val(),
        num_chequeos: parseInt($('#num-chequeos').val()) || 0, // Cambiar nombre del campo
        chequeos_correctos: parseInt($('#chequeos-correctos').val()) ||
          0, // Cambiar nombre del campo
        porcentaje: parseInt($('#porcentaje-cumplimiento').val()) || 0,
        comentarios: $('#comentarios').val().trim() || ''
      };

      // Validación detallada
      var errores = [];
      if (!datos.id_estandar || datos.id_estandar === '0') errores.push('El estándar es requerido');
      if (!datos.fecha) errores.push('La fecha es requerida');
      if (!datos.supervisor) errores.push('El supervisor es requerido');
      if (!datos.rut) errores.push('El colaborador es requerido');  // <-- aquí

      if (datos.num_chequeos <= 0) errores.push('El número de chequeos debe ser mayor a 0');
      if (datos.chequeos_correctos <= 0) errores.push(
        'El número de chequeos correctos debe ser mayor a 0');

      if (errores.length > 0) {
        Swal.fire({
          icon: 'error',
          title: 'Error de validación',
          html: errores.join('<br>'),
          confirmButtonText: 'Aceptar'
        });
        return;
      }

      // Mostrar indicador de carga
      Swal.fire({
        title: 'Procesando...',
        text: 'Por favor espere',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });

      // Debug: Mostrar datos en consola
      console.log('Datos a enviar:', datos);

      // Realizar petición AJAX
$.ajax({
  url: 'ajax/ajaxPorcentajes.php',
  type: 'POST',
  data: datos,
  dataType: 'json',
  success: function(response) {
    // --- NUEVO: si el SP o PDO nos devolvieron un status="error"…
    if (response.status === 'error') {
      console.error("Respuesta de error completa:", response.message);
      Swal.fire({
        icon: 'error',
        title: 'Error en servidor',
        text: response.message  // aquí verás el ErrorNumber y ErrorMessage que devolvió tu SP
      });
      return;
    }

    // --- Si todo salió bien, tu lógica original…
    Swal.fire({
      icon: 'success',
      title: 'Éxito',
      text: response.message,
      confirmButtonText: 'Aceptar'
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.reload();
      }
    });
  },
  error: function(xhr, status, error) {
    // — NUEVO: si falló la llamada AJAX (no llegó JSON válido)…
    console.error("ERROR AJAX (no JSON):", xhr.responseText);
    Swal.fire({
      icon: 'error',
      title: 'Error de red',
      text: xhr.responseText.substr(0, 200)  // los primeros 200 caracteres
    });
  }
});
      // Cerrar el modal
      $('#modal-editar-usuariop').modal('hide');
    });
  });
</script>

<style>
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
  }

  #resultados-busqueda a:hover {
    background-color: #f8f9fa;
  }

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
</style>

<!-- /.content -->