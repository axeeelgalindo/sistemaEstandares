$(document).on("submit", "#insertarArea", function (e) {
  e.preventDefault();

  //e.stopPropagation(); // Prevent event propagation
  var formData = new FormData(this);
  $.ajax({
    url: "ajax/ajaxArea.php",
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
          text: "Área ya está registrado",
          icon: "error",
        });
      } else if (respuesta.resultado == 1) {
        Swal.fire("Excelente!", "Registrado con exito!", "success").then(
          (result) => {
            if (result.value) {
              window.location = "areas";
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

$(document).on("click", ".btnEditarArea", function () {
  var idArea = $(this).attr("idArea");
  var datos = new FormData();
  datos.append("id_area", idArea);
  datos.append("tipoOperacion", "editarArea");

  $.ajax({
    url: "ajax/ajaxArea.php",
    type: "POST",
    data: datos,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta);
      $('#editarArea input[name="id_area"]').val(respuesta.id);
      $('#editarArea input[name="nombre"]').val(respuesta.nombre);
      $('#editarArea select[name="planta_idEditar"]').val(respuesta.planta_id); // << AQUI
    },
  });
});

$("#editarArea").submit(function (e) {
  e.preventDefault();

  var datos = new FormData(this);
  $.ajax({
    url: "ajax/ajaxArea.php",
    type: "POST",
    data: datos,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (respuesta) {
      console.log(respuesta);
      console.log(respuesta.resultado);
      if (respuesta.resultado == 3) {
        Swal.fire({
          title: "Error",
          text: "Área no existe",
          icon: "error",
        });
      } else if (respuesta.resultado == 2) {
        Swal.fire({
          title: "Error",
          text: "Área ya existe",
          icon: "error",
        });
      } else if (respuesta.resultado == 1) {
        Swal.fire("Excelente!", "Actualizado con éxito!", "success").then(
          (result) => {
            if (result.value) {
              window.location = "areas";
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


$(document).on("click", ".btnEliminarArea", function () {
  var idArea = $(this).attr("idArea");
  var datos = new FormData();
  datos.append("id_area", idArea);
  datos.append("tipoOperacion", "eliminarArea");
  Swal.fire({
    title: "¿Estás seguro de eliminar Área?",
    text: "Los cambios no son reversibles!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Elimina!",
  }).then((result) => {
    if (result.value) {
      $.ajax({
        url: "ajax/ajaxArea.php",
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
              text: "Área no existe",
              icon: "error",
            });
          } else if (respuesta.resultado == 1) {
            Swal.fire("Eliminado", "Área a sido eliminado.", "success").then(
              (result) => {
                if (result.value) {
                  window.location = "areas";
                }
              }
            );
          } else if (respuesta.resultado == 3) {
            Swal.fire({
              title: "Error",
              text: "No se puede eliminar una área asociada a un entrenamiento",
              icon: "error",
            });
          }
        },
      });
    }
  });
});
