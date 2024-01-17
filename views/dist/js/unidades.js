$(document).on("submit", "#insertarUnidad", function (e) {
    e.preventDefault();

//e.stopPropagation(); // Prevent event propagation
var formData = new FormData(this);
$.ajax({
url: 'ajax/ajaxUnidad.php',
type: 'POST',
data: formData,
dataType: 'json',
contentType: false,
processData: false,
success: function (respuesta) {
    console.log(respuesta)
    console.log(respuesta.resultado)
    if (respuesta.resultado == 2) {
        Swal.fire({
            title: 'Error',
            text: 'Unidad ya está registrado',
            icon: 'error'
        });
    } else if (respuesta.resultado == 1) {
        Swal.fire(
            'Excelente!',
            'Registrado con exito!',
            'success'
          ).then((result) => {
                                if (result.value) {
                                  window.location = "unidades"
                                }
                              })
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
})

$(document).on("click", ".btnEditarUnidad", function(){
    var idUnidad = $(this).attr("idUnidad")
    var datos = new FormData()
    datos.append("id_unidad", idUnidad)
    datos.append("tipoOperacion", "editarUnidad")
    $.ajax({
        url: 'ajax/ajaxUnidad.php',
        type: 'POST',
        data: datos,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(respuesta) {

         console.log(respuesta.id)
            $('#editarUnidad input[name="id_unidad"]').val(respuesta.id)
            $('#editarUnidad input[name="nombre"]').val(respuesta.unidad)
    
        }
    })
})

$("#editarUnidad").submit(function (e) {
    e.preventDefault()
    var datos = new FormData($(this)[0])
    $.ajax({
        url: 'ajax/ajaxUnidad.php',
        type: 'POST',
        data: datos,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function (respuesta) {
            console.log(respuesta)
            console.log(respuesta.resultado)
            if (respuesta.resultado == 3) {
                Swal.fire({
                    title: 'Error',
                    text: 'Unidad no existe',
                    icon: 'error'
                });
            } else if (respuesta.resultado == 2 ) {
                Swal.fire({
                    title: 'Error',
                    text: 'Área ya existe',
                    icon: 'error'
                });
            } else if (respuesta.resultado == 1  ) {
                Swal.fire(
                    'Excelente!',
                    'Actualizado con exito!',
                    'success'
                  ).then((result) => {
                                        if (result.value) {
                                          window.location = "unidades"
                                        }
                                      })
            } 
        },
        error: function () {
            Swal.fire({
                title: 'Error',
                text: 'Ocurrió un error al procesar la solicitud.',
                icon: 'error'
            });
        }
    })
})
$(document).on("click", ".btnEliminarUnidad", function(){
    var idUnidad = $(this).attr("idUnidad")
    var datos = new FormData()
    datos.append("id_Unidad", idUnidad)
    datos.append("tipoOperacion", "eliminarUnidad")
    Swal.fire({
      title: '¿Estás seguro de eliminar Área?',
      text: "Los cambios no son reversibles!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Elimina!'
    }).then((result) => {
      if (result.value) {
          $.ajax({
            url: 'ajax/ajaxUnidad.php',
            type: 'POST',
            data: datos,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(respuesta) {
    console.log(respuesta)

    console.log(respuesta.resultado)

                if (respuesta.resultado == 2) {
                    Swal.fire({
                        title: 'Error',
                        text: 'Unidad no existe',
                        icon: 'error'
                    });
                } else if (respuesta.resultado == 1) {
                    Swal.fire(
                        'Eliminado',
                        'Unidad a sido eliminado.',
                        'success'
                      ).then((result) => {
                                            if (result.value) {
                                              window.location = "unidades"
                                            }
                                          })
                } else if (respuesta.resultado == 3) {
                    Swal.fire({
                        title: 'Error',
                        text: 'No se puede eliminar una área asociada a un entrenamiento',
                        icon: 'error'
                    });
                }                
            }

        })
      }
    })
})
