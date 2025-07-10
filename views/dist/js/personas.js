//personas.js
$(document).on("submit", "#excelForm", function (e) {
  e.preventDefault();

  var TotalIngresadosCorrectamente = 0;
  var TotalNoIngresados = 0;

  var xhr = new XMLHttpRequest();
  var formData = new FormData(this);

  xhr.open("POST", "ajax/ajaxCargarArchivoExcel2.php", true);

  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        try {
          const response = JSON.parse(xhr.responseText);

          // Mostrar errores si existen
          if (response.errores && Array.isArray(response.errores)) {
            response.errores.forEach(function (error) {
              $("#DetallesErrores").append(
                `<div>> Rut: ${error.rut} — Error: ${error.error}</div>`
              );
            });
          }

          // Mostrar progreso y totales
          $("#progress-bar").css("width", response.progress + "%");
          $("#total-inserted").text(
            "Filas ingresadas: " + response.insertedRows
          );
          $("#total-errored").text("Filas erróneas: " + response.erroredRows);

          TotalIngresadosCorrectamente = response.insertedRows;
          TotalNoIngresados = response.erroredRows;

          // Mostrar mensajes SweetAlert
          if (response.errores.length > 0 && response.insertedRows > 0) {
            Swal.fire(
              "Excelente!",
              `Se realizaron cambios correctamente en ${response.insertedRows} personas`,
              "info"
            );
          } else if (response.errores.length > 0) {
            Swal.fire("Error!", "Existen errores en datos Excel", "error");
          } else {
            Swal.fire(
              "Excelente!",
              `Se realizaron cambios correctamente en ${response.insertedRows} personas`,
              "success"
            ).then((result) => {
              if (result.value) {
                window.location = "personas";
              }
            });
          }
        } catch (err) {
          console.error("❌ Error parseando JSON:", err);
          console.error("Respuesta cruda:", xhr.responseText);

          Swal.fire({
            title: "Error",
            text: "Respuesta inesperada del servidor.",
            icon: "error",
          });
        }
      } else {
        try {
          const errorResponse = JSON.parse(xhr.responseText);
          Swal.fire({
            title: "Error",
            text: errorResponse.error || "Error desconocido",
            icon: "error",
          });
        } catch (e) {
          Swal.fire(
            "Error",
            "Ocurrió un error inesperado al procesar la carga.",
            "error"
          );
          console.error("Error al parsear error JSON:", e);
        }
      }
    }
  };

  xhr.send(formData);
});

$(document).on("submit", "#insertarPersona", function (e) {
  e.preventDefault();

  if (validarRut()) {
    //e.stopPropagation(); // Prevent event propagation
    var formData = new FormData(this);
    $.ajax({
      url: "ajax/ajaxPersonas.php",
      type: "POST",
      data: formData,
      dataType: "json",
      contentType: false,
      processData: false,
      success: function (respuesta) {
        console.log(respuesta);

        console.log(respuesta.resultado);
        if (respuesta.resultado == 2) {
          Swal.fire({
            title: "Error",
            text: "Rut ya está registrado",
            icon: "error",
          });
        } else if (respuesta.resultado == 1) {
          Swal.fire("Excelente!", "Registrado con exito!", "success").then(
            (result) => {
              if (result.value) {
                window.location = "personas";
              }
            }
          );
        }
      },
      error: function () {
        Swal.fire({
          title: "Error",
          text: "Ocurrió un error al procesar la solicitud.",
          icon: "error",
        });
      },
    });
  } else {
    Swal.fire({
      title: "Error",
      text: "Rut no valido",
      icon: "error",
    });
  }
});

$(document).on("click", ".btnEditarPersonas", function () {
  var idPersonas = $(this).attr("idRut");
  var datos = new FormData();
  datos.append("id_personas", idPersonas);
  datos.append("tipoOperacion", "editarPersonas");
  $.ajax({
    url: "ajax/ajaxPersonas.php",
    type: "POST",
    data: datos,
    processData: false,
    contentType: false,
    success: function (respuesta) {
      var valor = JSON.parse(respuesta);
      console.log(valor.rut);
      $('#formu-editar-personas input[name="id_personas"]').val(valor.rut);
      $('#formu-editar-personas input[name="rut"]').val(valor.rut);
      $('#formu-editar-personas input[name="nombre"]').val(valor.nombre);
      $('#formu-editar-personas input[name="apellido"]').val(valor.apellido);
      $('#formu-editar-personas select[name="area"]').val(valor.area);
      $('#formu-editar-personas select[name="areaSecundaria"]').val(
        valor.area_secundaria
      );
    },
  });
});

$("#formu-editar-personas").submit(function (e) {
  e.preventDefault();
  var datos = new FormData($(this)[0]);
  $.ajax({
    url: "ajax/ajaxPersonas.php",
    type: "POST",
    data: datos,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta);
      console.log(respuesta.resultado);
      if (respuesta.resultado == 2) {
        Swal.fire({
          title: "Error",
          text: "Persona no existe",
          icon: "error",
        });
      } else if (respuesta.resultado == 1) {
        Swal.fire("Excelente!", "Actualizado con exito!", "success").then(
          (result) => {
            if (result.value) {
              window.location = "personas";
            }
          }
        );
      }
    },
    error: function () {
      Swal.fire({
        title: "Error",
        text: "Ocurrió un error al procesar la solicitud.",
        icon: "error",
      });
    },
  });
});

$(document).on("click", ".btnEliminarPersonas", function () {
  var idPersonas = $(this).attr("idRut");
  var datos = new FormData();
  datos.append("id_personas", idPersonas);
  datos.append("tipoOperacion", "eliminarPersonas");

  Swal.fire({
    title: "¿Estás seguro de eliminar persona?",
    text: "Los registros asociados a la persona no se eliminarán.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Eliminar!",
  }).then((result) => {
    if (result.value) {
      $.ajax({
        url: "ajax/ajaxPersonas.php",
        type: "POST",
        data: datos,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (respuesta) {
          if (respuesta.resultado == 1) {
            Swal.fire(
              "Excelente!",
              "Persona eliminada con exito!",
              "success"
            ).then((result) => {
              if (result.value) {
                window.location = "personas";
              }
            });
          } else {
            Swal.fire(
              "Personas no existe",
              "Rut de persona no existe en el sistema",
              "error"
            );
          }
        },
      });
    }
  });
});

$(document).on("click", ".btnActivarPersonas", function () {
  var idPersonas = $(this).attr("idRut");
  var datos = new FormData();
  datos.append("id_personas", idPersonas);
  datos.append("tipoOperacion", "eliminarPersonas");

  Swal.fire({
    title: "¿Estás seguro de activar persona?",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Activar!",
  }).then((result) => {
    if (result.value) {
      $.ajax({
        url: "ajax/ajaxPersonas.php",
        type: "POST",
        data: datos,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function (respuesta) {
          if (respuesta.resultado == 1) {
            Swal.fire(
              "Excelente!",
              "Persona Activada con exito!",
              "success"
            ).then((result) => {
              if (result.value) {
                window.location = "personas";
              }
            });
          } else {
            Swal.fire(
              "Personas no existe",
              "Rut de persona no existe en el sistema",
              "error"
            );
          }
        },
      });
    }
  });
});

var onBtnClicked = (btnId) => {
  Swal.close();
  var modalElement = document.getElementById("modal-insertar-nuevo-personas");
  $("#FormatLi").empty();
  $("#excelForm .TextoAreaValidar").text("");
  if (btnId == "agregar" || btnId == "cambiararea") {
    $("#FormatLi").append('<li>A1: nombre de columna "rut"');
    $("#FormatLi").append('<li>B1: nombre de columna "nombre"');
    $("#FormatLi").append('<li>C1: nombre de columna "apellido"');
    $("#FormatLi").append('<li>D1: nombre de columna "area base"');
    $("#FormatLi").append('<li>E1: nombre de columna "area secundaria"');
  } else {
    $("#FormatLi").append('<li>A1: nombre de columna "rut"');
  }
  if (btnId == "agregar") {
    $('#excelForm input[name="tipoOperacion"]').val("insertarPersonas");
    $("#excelForm .titulo").text("Cargar archivo Excel - Agregar Personal");
    $("#excelForm .TextoRutValidar").text(
      "Al cargar el documento se validará que rut NO exista en el sistema, ademas que el campo area exista en el sistema."
    );
    $("#excelForm .TextoAreaValidar").text(
      'En la columna "area base" o "area secundaria" debe ingresar solo áreas existentes en el sistema, la columna "area secundaria" no será obligación ingresar datos'
    );
    $("#excelForm .TextoAreaValidar").show();
    // Abre el modal
    $(modalElement).modal("show");
  } else if (btnId == "desactivar") {
    $('#excelForm input[name="tipoOperacion"]').val("desactivarPersonas");
    $("#excelForm .titulo").text("Cargar archivo Excel - Desactivar Personal");
    $("#excelForm .TextoRutValidar").text(
      "Al cargar el documento se validará que rut exista en el sistema"
    );
    $("#excelForm .TextoAreaValidar").hide();

    // Abre el modal
    $(modalElement).modal("show");
  } else if (btnId == "activar") {
    $('#excelForm input[name="tipoOperacion"]').val("activarPersonas");
    $("#excelForm .titulo").text("Cargar archivo Excel - Activar Personal");
    $("#excelForm .TextoRutValidar").text(
      "Al cargar el documento se validará que rut exista en el sistema"
    );
    $(modalElement).modal("show");
    $("#excelForm .TextoAreaValidar").hide();
  } else if (btnId == "cambiararea") {
    $('#excelForm input[name="tipoOperacion"]').val("cambiarArea");
    $("#excelForm .titulo").text("Cargar archivo Excel - Modificar Área");
    $("#excelForm .TextoRutValidar").text(
      "Al cargar el documento se validará que rut exista en el sistema, ademas que el campo area exista en el sistema."
    );
    $("#excelForm .TextoAreaValidar").text(
      'En la columna "area base" o "area secundaria" debe ingresar solo áreas existentes en el sistema, la columna "area secundaria" no será obligación ingresar datos'
    );
    $("#excelForm .TextoAreaValidar").show();

    $(modalElement).modal("show");
  }
};

$(document).on("click", ".btnAgregarPersonaExcel", function () {
  Swal.fire({
    icon: "warning",
    showConfirmButton: false,
    showCloseButton: true,
    html: `
			<div class="custom-modal">
				 <h3>Seleccione una acción</h3>
				<div >
				  <button class="btn btn-primary col-12 my-2" onclick="onBtnClicked('agregar')"><i class="fa-solid fa-user-plus"></i> Agregar Personal</button>
				  <button class="btn btn-primary col-12 my-2" onclick="onBtnClicked('cambiararea')" ><i class="fa-solid fa-user-gear"></i> Modificar Área</button>
				  <button class="btn btn-danger col-12 my-2" onclick="onBtnClicked('desactivar')"><i class="fa-solid fa-user-slash"></i>Desactivar Personal</button>
				  <button class="btn btn-success col-12 my-2" onclick="onBtnClicked('activar')"><i class="fa-solid fa-user-check"></i> Activar Personal</button>
				  <button class="btn btn-secondary col-12 my-2" onclick="onBtnClicked('cancel')">Cancelar</button>
				</div>
				</div>`,
  });
});

function validarRut() {
  var rut = document.getElementById("rut").value;
  rut = rut.replace("‐", "-");
  if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(rut)) {
    return false;
  }
  return true;
}
