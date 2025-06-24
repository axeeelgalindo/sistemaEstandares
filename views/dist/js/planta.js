// INSERTAR PLANTA
$(document).on("submit", "#insertarPlanta", function (e) {
  e.preventDefault();

  var formData = new FormData(this);
  $.ajax({
    url: 'ajax/ajaxPlanta.php',
    type: 'POST',
    data: formData,
    dataType: 'json',
    contentType: false,
    processData: false,
    success: function (respuesta) {
      if (respuesta.resultado == 2) {
        Swal.fire({
          title: 'Error',
          text: 'La planta ya está registrada',
          icon: 'error'
        });
      } else if (respuesta.resultado == 1) {
        Swal.fire(
          'Excelente!',
          'Planta registrada con éxito!',
          'success'
        ).then((result) => {
          if (result.value) {
            window.location = "plantas";
          }
        });
      }
    },
    error: function () {
      Swal.fire({
        title: 'Error',
        text: 'Ocurrió un error al procesar la solicitud.',
        icon: 'error'
      });
    }
  });
});


// CARGAR DATOS PARA EDITAR PLANTA
$(document).on("click", ".btnEditarPlanta", function () {
  var idPlanta = $(this).attr("idPlanta");
  var datos = new FormData();
  datos.append("id_planta", idPlanta);
  datos.append("tipoOperacion", "editarPlanta");

  $.ajax({
    url: 'ajax/ajaxPlanta.php',
    type: 'POST',
    data: datos,
    dataType: 'json',
    processData: false,
    contentType: false,
    success: function (respuesta) {
      $('#editarPlanta input[name="id_planta"]').val(respuesta.id);
      $('#editarPlanta input[name="nombre"]').val(respuesta.nombre);
    }
  });
});


// ACTUALIZAR PLANTA
$("#editarPlanta").submit(function (e) {
  e.preventDefault();

  var datos = new FormData(this);
  $.ajax({
    url: 'ajax/ajaxPlanta.php',
    type: 'POST',
    data: datos,
    dataType: 'json',
    processData: false,
    contentType: false,
    success: function (respuesta) {
      if (respuesta.resultado == 3) {
        Swal.fire({
          title: 'Error',
          text: 'La planta no existe',
          icon: 'error'
        });
      } else if (respuesta.resultado == 2) {
        Swal.fire({
          title: 'Error',
          text: 'La planta ya existe',
          icon: 'error'
        });
      } else if (respuesta.resultado == 1) {
        Swal.fire(
          'Excelente!',
          'Planta actualizada con éxito!',
          'success'
        ).then((result) => {
          if (result.value) {
            window.location = "plantas";
          }
        });
      }
    },
    error: function () {
      Swal.fire({
        title: 'Error',
        text: 'Ocurrió un error al procesar la solicitud.',
        icon: 'error'
      });
    }
  });
});


// ELIMINAR PLANTA
$(document).on("click", ".btnEliminarPlanta", function () {
  var idPlanta = $(this).attr("idPlanta");
  var datos = new FormData();
  datos.append("id_planta", idPlanta);
  datos.append("tipoOperacion", "eliminarPlanta");

  Swal.fire({
    title: '¿Estás seguro de eliminar la planta?',
    text: "Los cambios no son reversibles!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Sí, eliminar!'
  }).then((result) => {
    if (result.value) {
      $.ajax({
        url: 'ajax/ajaxPlanta.php',
        type: 'POST',
        data: datos,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (respuesta) {
          if (respuesta.resultado == 2) {
            Swal.fire({
              title: 'Error',
              text: 'La planta no existe',
              icon: 'error'
            });
          } else if (respuesta.resultado == 1) {
            Swal.fire(
              'Eliminado',
              'La planta ha sido eliminada.',
              'success'
            ).then((result) => {
              if (result.value) {
                window.location = "plantas";
              }
            });
          } else if (respuesta.resultado == 3) {
            Swal.fire({
              title: 'Error',
              text: 'No se puede eliminar una planta asociada a secciones u otros datos',
              icon: 'error'
            });
          }
        }
      });
    }
  });
});
