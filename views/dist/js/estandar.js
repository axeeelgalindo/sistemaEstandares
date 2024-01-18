$(document).on("submit", "#insertarEstandar", function (e) {
    e.preventDefault();

//e.stopPropagation(); // Prevent event propagation
var formData = new FormData(this);
$.ajax({
url: 'ajax/ajaxEstandar.php',
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
            text: 'Código ya está registrado',
            icon: 'error'
        });
    } else if (respuesta.resultado == 1) {
        Swal.fire(
            'Excelente!',
            'Registrado con exito!',
            'success'
          ).then((result) => {
                                if (result.value) {
                                  window.location = "estandareseditar"
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

$(document).on("click", ".btnVerEstandar", function(){
    var url = $(this).attr("Url");
    var nuevaUrl = url.slice(3);
    // Asignar la URL a la imagen
    $('#imagenMostrada').attr("src", nuevaUrl);
})

$(document).on("click", ".btnEliminarEstandar", function(){
    var idEstandar = $(this).attr("idEstandar")
    var rutaImagen = $(this).attr("rutaImagen")
    var datos = new FormData()
    datos.append("id_estandar", idEstandar)
    datos.append("tipoOperacion", "eliminarEstandar")
    datos.append("rutaImagen", rutaImagen)
    Swal.fire({
      title: '¿Estás seguro de eliminar Estandar?',
      text: "Los cambios no son reversibles!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Elimina!'
    }).then((result) => {
      if (result.value) {
          $.ajax({
            url: 'ajax/ajaxEstandar.php',
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
                        text: 'Estandar no existe',
                        icon: 'error'
                    });
                } else if (respuesta.resultado == 1) {
                    Swal.fire(
                        'Eliminado',
                        'Estandar a sido eliminado.',
                        'success'
                      ).then((result) => {
                                            if (result.value) {
                                              window.location = "estandareseditar"
                                            }
                                          })
                } else if (respuesta.resultado == 3) {
                  Swal.fire({
                    title: 'Error',
                    text: 'No es puede eliminar estandar ya en entrenamiento',
                    icon: 'error'
                });
                }           
            }

        })
      }
    })
})

$(document).on("click", ".btnEditarEstandar", function(){
    var idEstandar = $(this).attr("idEstandar")
    var datos = new FormData()
    datos.append("id_estandar", idEstandar)
    datos.append("tipoOperacion", "editarEstandar")
    $('#editarEstandar input[name="area[]"]').prop('checked', false);

    $.ajax({
        url: 'ajax/ajaxEstandar.php',
        type: 'POST',
        data: datos,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(respuesta) {

         console.log(respuesta.id_area)
            $('#editarEstandar input[name="id_estandar"]').val(respuesta.id)
            $('#editarEstandar input[name="codigo"]').val(respuesta.codigo)
            $('#editarEstandar input[name="nombre"]').val(respuesta.nombre)
			$('#editarEstandar select[name="tipo"]').val(respuesta.tipo)

            var arreglo = respuesta.id_area.split(",");
           // Recorriendo el arreglo con un bucle for  
           for (var i = 0; i < arreglo.length; i++) {
                $('#editarEstandar input[id="customCheckboxE' + arreglo[i].replace(/\s/g, '') + '"]').prop('checked', true);
           }
            $('#editarEstandar input[name="rutaActual"]').val(respuesta.url_pdf)     
        }
    })
})

$("#editarEstandar").submit(function (e) {
    e.preventDefault()
    var datos = new FormData($(this)[0])
    $.ajax({
        url: 'ajax/ajaxEstandar.php',
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
                    text: 'Estandar no existe',
                    icon: 'error'
                });
            } else if (respuesta.resultado == 4 ) {
                Swal.fire({
                    title: 'Error',
                    text: 'Código de Estandar ya existe',
                    icon: 'error'
                });
            } else if (respuesta.resultado == 1 || respuesta.resultado == 2 ) {
                Swal.fire(
                    'Excelente!',
                    'Actualizado con exito!',
                    'success'
                  ).then((result) => {
                                        if (result.value) {
                                          window.location = "estandareseditar"
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


$(document).on("submit", "#cargarEstandar", function (e) {
    e.preventDefault();

//e.stopPropagation(); // Prevent event propagation
var formData = new FormData(this);
$.ajax({
url: 'ajax/ajaxEstandar.php',
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
            text: 'Estandar no existe',
            icon: 'error'
        });
    } else if (respuesta.resultado == 1) {
        Swal.fire(
            'Excelente!',
            'Registrado con exito!',
            'success'
          ).then((result) => {
                                if (result.value) {
                                  window.location = "estandaresgestion"
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


$(document).on("click", ".btnSubirEstandar", function(){
    
    CargarPersonal($(this).attr("IdProceso"));
})

  // Manejar el clic en el botón "Validar y Enviar"
  $(document).on("click", "#btnValidar", function(){ 
    var miTabla = $('#TablaPersonal').DataTable();
    var datosAEnviar = []; // Arreglo para almacenar los datos seleccionados
    var idEstandar1 = $('#modal-cargar-personas-estandar input[name="id_estandar"]').val()
    // Limpiar el arreglo de datos a enviar antes de recopilar los datos nuevamente
    datosAEnviar = [];
    // Recopilar los datos de las filas seleccionadas
    miTabla.rows().nodes().to$().filter(':has(:checked)').each(function() {
        var fila = $(this);
        var rut = fila.find('td:eq(2)').text();
        datosAEnviar.push({ rut: rut});
    });

    // Realizar validaciones de los datos si es necesario

      // Agregar los datos y el parámetro adicional al objeto de datos
      var datosParaEnviar = {
        datos: datosAEnviar
    };

        // Enviar los datos al servidor a través de una solicitud AJAX
        $.ajax({
              url: 'ajax/ajaxEstandar.php',
            type: 'POST',
            data: { datos: datosParaEnviar,tipoOperacion: "ValidarPersonal",id_estandar:idEstandar1 },
            dataType: 'json',
            success: function(respuesta) {
           
                if(respuesta == "ok"){
                   Swal.fire(
                    'Excelente!',
                    'Validado con exito!',
                    'success'
                  ).then((result) => {
                                        if (result.value) {
                                            CargarPersonal(idEstandar1);
                                            CargarEstandares()
                                        }
                                      })  
                }else{
                    Swal.fire({
                        title: 'Error',
                        text: 'Error al validar personal',
                        icon: 'error'
                    });
                }
               
            }
        });

});


  // Manejar el clic en el botón "Validar y Enviar"
  $(document).on("click", "#btnRevertir", function(){ 
    var miTabla = $('#TablaPersonalValidado').DataTable();
    var datosAEnviar = []; // Arreglo para almacenar los datos seleccionados
    var idEstandar1 = $('#modal-cargados-personas-estandar input[name="id_estandar"]').val()
    // Limpiar el arreglo de datos a enviar antes de recopilar los datos nuevamente
    datosAEnviar = [];
    // Recopilar los datos de las filas seleccionadas
    miTabla.rows().nodes().to$().filter(':has(:checked)').each(function() {
        var fila = $(this);
        var rut = fila.find('td:eq(2)').text();
        datosAEnviar.push({ rut: rut});
    });

    // Realizar validaciones de los datos si es necesario

      // Agregar los datos y el parámetro adicional al objeto de datos
      var datosParaEnviar = {
        datos: datosAEnviar
    };
    Swal.fire({
      title: '¿Estás seguro de revertir los entrenamientos?',
      text: "Los cambios no son reversibles!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Revertir!'
    }).then((result) => {
      if (result.value) {
        // Enviar los datos al servidor a través de una solicitud AJAX
        $.ajax({
          url: 'ajax/ajaxEstandar.php',
        type: 'POST',
        data: { datos: datosParaEnviar,tipoOperacion: "RevertirPersonal",id_estandar:idEstandar1 },
        dataType: 'json',
        success: function(respuesta) {
       
            if(respuesta == "ok"){
               Swal.fire(
                'Excelente!',
                'Reversión realizada con exito!',
                'success'
              ).then((result) => {
                                    if (result.value) {
                                      CargarPersonalValidado(idEstandar1);
                                        CargarEstandares()
                                    }
                                  })  
            }else{
                Swal.fire({
                    title: 'Error',
                    text: 'Error al revertir personal',
                    icon: 'error'
                });
            }
           
        }
    });
      }
    })








});

function CargarPersonal(IdProceso){
  //  var IdProceso = $(".btnSubirEstandar").attr("IdProceso")
    $('#modal-cargar-personas-estandar input[name="id_estandar"]').val(IdProceso)
    var datos = new FormData()
    datos.append("id_proceso", IdProceso)
    datos.append("tipoOperacion", "SubirProceso")
    $.ajax({
        url: 'ajax/ajaxEstandar.php',
        type: 'POST',
        data: datos,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(respuesta) {

           var miTabla = $('#TablaPersonal').DataTable();
        
           miTabla.clear().draw();
            
        // Supongamos que 'respuesta' es un array JSON
        respuesta.forEach(function(item) {
            // Agrega 'item' como una fila a la tabla DataTable
            miTabla.row.add([
                '<input type="checkbox" class="seleccionar">',
                item.Nombre +" "+ item.Apellido ,
                item.Rut
            ]).draw();
        });

           // Opcional: Si deseas que la tabla se reorganice
           miTabla.order([0, 'asc']).draw();

           // Opcional: Si deseas limpiar el formulario o realizar otras acciones
           // ...

           // Imprimir la tabla
           console.log("Fila agregada: Nombre " + respuesta.Nombre + ", Apellido " + respuesta.Apellido);   
        }
    })
}

function CargarEstandares(){
    $.ajax({
        url: 'ajax/ajaxEstandar.php',
      type: 'POST',
      data: { tipoOperacion: "CargarEstandares"},
      dataType: 'json',
      success: function(respuesta) {

        var miTabla = $('#example1').DataTable();
        miTabla.clear().draw();

        // Supongamos que 'datos' es un array JSON
        respuesta.forEach(function(item) {
            var porcentajeHTML;
            if (item.porcentaje_entrenado > 90) {
                porcentajeHTML = '<span class="badge badge-success">Adquirido</span>';
            } else {
                porcentajeHTML = '<span class="badge badge-danger">No Adquirido</span>';
            }

            var nuevaFila = miTabla.row.add([
                item.codigo,
                item.tipo,
                item.nombre,
                item.area,
                item.total_personas + '/' + item.total_personas_entrenadas,
                '<div class="progress progress-sm">\
                <div class="progress-bar bg-green" role="progressbar" aria-valuenow="'+item.porcentaje_entrenado + '" aria-valuemin="0" aria-valuemax="100" style="width: '+item.porcentaje_entrenado+'%">\
                </div>\
            </div>\
            <small>\
            '+item.porcentaje_entrenado +'% Entrenado\
            </small>' ,                    
                 '<button class="btn btn-sm btn-default btnVerEstandar" Url="'+item.url_pdf+'" data-toggle="modal" data-target="#modal-ver-estandar">\
                 <i class="far fa-solid fa-eye"> </i>\
                 </button> <button class="btn btn-sm btn-primary btnSubirEstandar" IdProceso="'+ item.id +'" data-toggle="modal" data-target="#modal-cargar-personas-estandar">\
                 <i class="fas fa-rocket"></i> Entrenar\
                 </button>\
                 <button class="btn btn-success btn-sm btnEstandarValidado" IdProceso="'+item.id +'" data-toggle="modal" data-target="#modal-cargados-personas-estandar"><i class="fas fa-user-check"></i>Entrenados</button>'                                              
            ])
                // Aplicar una clase a la celda específica
                nuevaFila.nodes().to$().find('td:eq(5)').addClass('project_progress');
                nuevaFila.nodes().to$().find('td:eq(6)').addClass('project-state');
                nuevaFila.nodes().to$().find('td:eq(7)').addClass('project-actions text-right');

        });
        miTabla.draw();
      }
  });
}

$(document).on("click", ".btnEstandarValidado", function(){
    
    CargarPersonalValidado($(this).attr("IdProceso"));
})
function CargarPersonalValidado(IdProceso){
    //  var IdProceso = $(".btnSubirEstandar").attr("IdProceso")
      $('#modal-cargados-personas-estandar input[name="id_estandar"]').val(IdProceso)
      var datos = new FormData()
      datos.append("id_proceso", IdProceso)
      datos.append("tipoOperacion", "PersonalValidado")
      $.ajax({
          url: 'ajax/ajaxEstandar.php',
          type: 'POST',
          data: datos,
          dataType: 'json',
          processData: false,
          contentType: false,
          success: function(respuesta) {
  
             var miTabla = $('#TablaPersonalValidado').DataTable();
          
             miTabla.clear().draw();     
          // Supongamos que 'respuesta' es un array JSON
          respuesta.forEach(function(item) {
              // Agrega 'item' como una fila a la tabla DataTable
              miTabla.row.add([
                  '<input type="checkbox" class="seleccionar">',
                  item.Nombre +" "+ item.Apellido ,
                  item.Rut
              ]).draw();
          });
  
             // Opcional: Si deseas que la tabla se reorganice
             miTabla.order([0, 'asc']).draw();
  
             // Opcional: Si deseas limpiar el formulario o realizar otras acciones
             // ...
  
             // Imprimir la tabla
             console.log("Fila agregada: Nombre " + respuesta.Nombre + ", Apellido " + respuesta.Apellido);
  
         
          }
      })
  }

let creados 
 let entrenados
 let porcentaje
 var datos = new FormData()
 datos.append("tipoOperacion", "GraficoCreados_Entrenados")

    $.ajax({
      url: 'ajax/ajaxEstandar.php',
      type: 'POST',
      data: datos,
      dataType: 'json',
      processData: false,
      contentType: false,
      success: function(respuesta) {
     console.log(respuesta)
     creados = parseInt(respuesta.total_estandares_creados)
     entrenados = parseInt(respuesta.total_estandares_entrenados)
      if(creados == 0){
        porcentaje = 0
      }else{
        porcentaje = ( entrenados * 100) / creados;
      }

     $('#PorcentajeEntrenado').text(porcentaje.toFixed(2)+' %');  
      }
  })

    $(function () {
      var SeguridadEntrenados = 0
      var SeguridadCreados = 0
      let CalidadEntrenados = 0
      let CalidadCreados = 0
      var ProduccionEntrenados = 0
      let ProduccionCreados = 0
      let S5Entrenados = 0
      let S5Creados = 0

      var CreadosPorMesesArea = [];
      var EntrenadosPorMesesArea = [];

      var GraficoArea
      var GraficoPilarSeguridad
      var GraficoPilarCalidad
      var GraficoPilarProduccion
      var GraficoPilar5S
      var GraficoBarras


      var mesesDelAnio = [];
      var TotalArea = [];

      var EntrenadosPorMeses = [];
      var CreadosPorMeses = [];

    // Obtener la fecha actual
    const fechaActual = new Date();

    // Iniciar desde enero (mes 0) del año actual
    fechaActual.setMonth(0);

    // Obtener los 12 meses del año y agregarlos al array
    for (let i = 0; i < 12; i++) {
      mesesDelAnio.push(fechaActual.toLocaleString('default', { month: 'long' }));
      fechaActual.setMonth(fechaActual.getMonth() + 1); // Avanzar al siguiente mes
    }
      TotalArea.push("Total Área")
    var areaChartDataAreas = {
      labels  : TotalArea,
      datasets: [
        {
          label               : 'Entrenados',
          backgroundColor     : '#D85E05',
          borderColor         : 'rgba(60,141,188,0.8)',
          pointRadius          : false,
          pointColor          : '#3b8bba',
          pointStrokeColor    : 'rgba(60,141,188,1)',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(60,141,188,1)',
          data                : EntrenadosPorMesesArea
        },
        {
          label               : 'Creados',
          backgroundColor     : '#1C245A',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : CreadosPorMesesArea
        },
      ]
    }
    var barChartCanvas2 = $('#barChart2').get(0).getContext('2d')
    var barChartDataArea = $.extend(true, {}, areaChartDataAreas)
    var temp0A = areaChartDataAreas.datasets[0]
    var temp1A = areaChartDataAreas.datasets[1]
    barChartDataArea.datasets[0] = temp1A
    barChartDataArea.datasets[1] = temp0A

    GraficoArea = new Chart(barChartCanvas2, {
      type: 'bar',
      data: barChartDataArea,
      options: barChartOptions,
      plugins:[ChartDataLabels],
            options:{
              plugins:{
                    datalabels: {
                color: '#FFFFFF'
              },
              }
   
      }
    })

  //-------------
    //- PIE CHART -
    //-------------
    // Get context with jQuery - using jQuery's .get() method.

      var pieChartCanvasSeguridad = $('#pieChart').get(0).getContext('2d')
      var pieChartCanvasCalidad = $('#pieChart2').get(0).getContext('2d')
      var pieChartCanvasProduccion = $('#pieChart3').get(0).getContext('2d')
      var pieChartCanvas5S = $('#pieChart4').get(0).getContext('2d')

      GraficoPilarSeguridad =  new Chart(pieChartCanvasSeguridad, {
            type: 'pie',
            data: {
                labels: ['Entrenados', 'Creados'],
                datasets: [
                    {
                        data: [SeguridadEntrenados, SeguridadCreados],
                        backgroundColor: ['#D85E05', '#1C245A'],
                    }
                ]
            },
            options: {
              plugins: {
                // Change options for ALL labels of THIS CHART
                datalabels: {
                  color: '#36A2EB'
                } , title: {
                  display: true,
                  text: 'Seguridad'
              }
                     
            },
            },
            plugins:[ChartDataLabels],
            options:{
              plugins:{
                    datalabels: {
                color: '#FFFFFF'
              },
              title: {
                display: true,
                text: 'Seguridad'
            }
              }
   
      }
      });
      GraficoPilarCalidad = new Chart(pieChartCanvasCalidad, {
          type: 'pie',
          data: {
              labels: ['Entrenados', 'Creados'],
              datasets: [
                  {
                      data: [CalidadEntrenados, CalidadCreados],
                      backgroundColor: ['#D85E05', '#1C245A'],
                  }
              ]
          },
          options: {
            plugins: {
              legend:{
                display:false
              },
              // Change options for ALL labels of THIS CHART
              datalabels: {
                color: '#36A2EB'
              } , title: {
                display: true,
                text: 'Calidad'
            }
                  
          },
          },
          plugins:[ChartDataLabels],
          options:{
            plugins:{
                  datalabels: {
              color: '#FFFFFF'
            }, title: {
              display: true,
              text: 'Calidad'
          }
            }
 
    }
      });
      GraficoPilarProduccion =  new Chart(pieChartCanvasProduccion,{
        type: 'pie',
        data: {
            labels: ['Entrenados', 'Creados'],
            datasets: [
                {
                    data: [ProduccionEntrenados, ProduccionCreados],
                    backgroundColor: ['#D85E05', '#1C245A'],
                }
            ]
        },
        options: {
            plugins: {
              legend:{
                display:false
              },
            // Change options for ALL labels of THIS CHART
            datalabels: {
              color: '#36A2EB'
            } , title: {
              display: true,
              text: 'Producción'
          }
                
        },
        }, 
        plugins:[ChartDataLabels],
        options:{
          plugins:{
                datalabels: {
            color: '#FFFFFF'
          }, title: {
            display: true,
            text: 'Producción'
        }
          }

      }
        
      });
      GraficoPilar5S =  new Chart(pieChartCanvas5S,{
        type: 'pie',
        data: {
            labels: ['Entrenados', 'Creados'],
            datasets: [
                {
                    data: [S5Entrenados, S5Creados],
                    backgroundColor: ['#D85E05', '#1C245A'],
                }
            ]
        },
        options: {
          plugins: {
            legend:{
              display:false
            },
            // Change options for ALL labels of THIS CHART
            datalabels: {
              color: '#36A2EB'
            } , title: {
              display: true,
              text: '5S'
          }
                
        },
        },

        plugins:[ChartDataLabels],
        options:{
          plugins:{
                datalabels: {
            color: '#FFFFFF'
          },
          title: {
            display: true,
            text: '5S'
             }
          }

       }
      });


      var areaChartData = {
        labels  : mesesDelAnio,
        datasets: [
          {
            label               : 'Entrenados',
            backgroundColor     : '#D85E05',
            borderColor         : 'rgba(60,141,188,0.8)',
            pointRadius          : false,
            pointColor          : '#3b8bba',
            pointStrokeColor    : 'rgba(60,141,188,1)',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(60,141,188,1)',
            data                : EntrenadosPorMeses
          },
          {
            label               : 'Creados',
            backgroundColor     : '#1C245A',
            borderColor         : 'rgba(210, 214, 222, 1)',
            pointRadius         : false,
            pointColor          : 'rgba(210, 214, 222, 1)',
            pointStrokeColor    : '#c1c7d1',
            pointHighlightFill  : '#fff',
            pointHighlightStroke: 'rgba(220,220,220,1)',
            data                : CreadosPorMeses
          },
        ]
      }
          //-------------
    //- BAR CHART -
    //-------------
    var barChartCanvas = $('#barChart').get(0).getContext('2d')

    var barChartData = $.extend(true, {}, areaChartData)
    var temp0 = areaChartData.datasets[0]
    var temp1 = areaChartData.datasets[1]
    barChartData.datasets[0] = temp1
    barChartData.datasets[1] = temp0

    GraficoBarras = new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions,
   
      plugins:[ChartDataLabels],
      options:{
        plugins:{
              datalabels: {
          color: '#FFFFFF'
        },
        }

}
    })

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false,
      plugins: {
        legend:{
          display:false
        },
        datalabels: {
          display: true,
            color: 'black', // Color de las etiquetas
            anchor: 'end',  // Posición de las etiquetas (puedes ajustarla según tus preferencias)
            align: 'end',   // Alineación de las etiquetas (puedes ajustarla según tus preferencias)
            formatter: function(value, context) {
                var dataset = context.dataset;
                var sum = dataset.data.reduce((acc, data) => acc + data, 0);
                var percentage = ((value / sum) * 100).toFixed(2) + '%';
                return percentage;
            }
        }
    }
    }


$(document).on("change", ".areas", function(){

  CargarInformacionGraficos($(this).val())

    });


    function CargarInformacionGraficos(area){

      GraficoArea.data.datasets[0].data = []       
      GraficoArea.data.datasets[1].data = []                       
      GraficoArea.update()
      GraficoPilarSeguridad.data.datasets[0].data = [0,0]                   
      GraficoPilarSeguridad.update()
      GraficoPilarProduccion.data.datasets[0].data = [0,0]                       
      GraficoPilarProduccion.update()
      GraficoPilarCalidad.data.datasets[0].data = [0,0]                       
      GraficoPilarCalidad.update()
      GraficoPilar5S.data.datasets[0].data =[0,0]                      
      GraficoPilar5S.update()
      GraficoBarras.data.datasets[0].data = []       
      GraficoBarras.data.datasets[1].data = []                       
      GraficoBarras.update()
    
          var Area = area
          var datos = new FormData()
          datos.append("id_area", Area)
          datos.append("tipoOperacion", "GraficoBarras_Areas_Creados")
                $.ajax({
                  url: 'ajax/ajaxEstandar.php',
                  type: 'POST',
                  data: datos,
                  dataType: 'json',
                  processData: false,
                  contentType: false,
                  success: function(respuesta) {
                    CreadosPorMesesArea = []
    
                    for (let i = 0; i < respuesta.length; i++) {
                      CreadosPorMesesArea.push(respuesta[i]["CantidadRegistrosCreadosAreas"])
                     }   
    
                     GraficoArea.data.datasets[0].data = CreadosPorMesesArea       
                     
                     GraficoArea.update()
                  }
              })
              var datos2 = new FormData()
              datos2.append("id_area", Area)
              datos2.append("tipoOperacion", "GraficoBarras_Areas_Entrenados")
                    $.ajax({
                      url: 'ajax/ajaxEstandar.php',
                      type: 'POST',
                      data: datos2,
                      dataType: 'json',
                      processData: false,
                      contentType: false,
                      success: function(respuesta) {
                        EntrenadosPorMesesArea = []
        
                        for (let i = 0; i < respuesta.length; i++) {
                          EntrenadosPorMesesArea.push(respuesta[i]["CantidadRegistrosEntrenadosAreas"])
                         }     
                         GraficoArea.data.datasets[1].data = EntrenadosPorMesesArea  
                                      
                         GraficoArea.update()
                      }
                  })
    
                  var datosentrenados = new FormData()
                  datosentrenados.append("id_area", Area)
                  datosentrenados.append("tipoOperacion", "GraficoBarras_Entrenados")
                 
                     $.ajax({
                       url: 'ajax/ajaxEstandar.php',
                       type: 'POST',
                       data: datosentrenados,
                       dataType: 'json',
                       processData: false,
                       contentType: false,
                       success: function(respuesta) {
                      console.log(respuesta)
                       EntrenadosPorMeses = []
    
                                  for (let i = 0; i < respuesta.length; i++) {
                                    EntrenadosPorMeses.push(respuesta[i]["CantidadRegistrosEntrenados"])
                                  }
                             GraficoBarras.data.datasets[1].data = EntrenadosPorMeses                                
                            GraficoBarras.update()
    
                       }
                   })
        
                   var datoscreados = new FormData()
                   datoscreados.append("id_area", Area)
                   datoscreados.append("tipoOperacion", "GraficoBarras_Creados")
                  
                      $.ajax({
                        url: 'ajax/ajaxEstandar.php',
                        type: 'POST',
                        data: datoscreados,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                        success: function(respuesta) {
                       console.log(respuesta)
                       CreadosPorMeses = []
                                   for (let i = 0; i < respuesta.length; i++) {
                                    CreadosPorMeses.push(respuesta[i]["CantidadRegistrosCreados"])
                                   }
    
                                  GraficoBarras.data.datasets[0].data = CreadosPorMeses    
                                  GraficoBarras.update()
                        }
                    })
    
                    var datos = new FormData()
                    datos.append("id_area", Area)
                    datos.append("tipoOperacion", "GraficoPie_Por_Pilar")
                   
                       $.ajax({
                         url: 'ajax/ajaxEstandar.php',
                         type: 'POST',
                         data: datos,
                         dataType: 'json',
                         processData: false,
                         contentType: false,
                         success: function(respuesta) {
                        GraficoPilarSeguridad.data.datasets[0].data = [parseInt(respuesta.seguridad_entrenados),parseInt(respuesta.seguridad_creados)]                       
                        GraficoPilarSeguridad.update()
                            
                        GraficoPilarProduccion.data.datasets[0].data = [parseInt(respuesta.produccion_entrenados),parseInt(respuesta.produccion_creados)]                       
                        GraficoPilarProduccion.update()
      
                        GraficoPilarCalidad.data.datasets[0].data = [parseInt(respuesta.calidad_entrenados),parseInt(respuesta.calidad_creados)]                       
                        GraficoPilarCalidad.update()
    
                        GraficoPilar5S.data.datasets[0].data = [parseInt(respuesta.s5_entrenados),parseInt(respuesta.s5_creados)]                       
                        GraficoPilar5S.update()
                         }
                     })
    
    }

    // This will get the first returned node in the jQuery collection.
/*     new Chart(areaChartCanvas, {
      type: 'line',
      data: areaChartData,
      options: areaChartOptions
    })
 */
    //-------------
    //- LINE CHART -
    //--------------
   // var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
var selectElement = document.querySelector('select[name="areas"]');

// Obtener el valor seleccionado
var selectedValue = selectElement.value; 
   CargarInformacionGraficos(selectedValue)

   var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
   GraficoTest = new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: {
        labels: ['Entrenados', 'Creados'],
        datasets: [
            {
                data: [entrenados, creados],
                backgroundColor: ['#D85E05', '#1C245A'],
            }
        ]
      },
      options: {
        plugins: {
          legend:{
            display:false
          },
          // Change options for ALL labels of THIS CHART

      }
    },
    plugins:[ChartDataLabels],
    options:{
      plugins:{
   
        datalabels: {
          anchor: 'end',
          backgroundColor: function(context) {
            return context.dataset.backgroundColor;
          },
          borderColor: 'white',
          borderRadius: 0,
          borderWidth: 2,
          color: 'white',
          font: {
            weight: 'bold'
          },
          formatter: Math.round,
          padding: 6
        }
        
      }
 
    }
    
   })
  })




  
