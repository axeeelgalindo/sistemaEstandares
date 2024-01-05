$(document).on("submit", "#excelForm", function (e) {

	var TotalIngresadosCorrectamente = 0;
	var TotalNoIngresados = 0;

	e.preventDefault();
var xhr = new XMLHttpRequest();
var responses = []; // Arreglo para almacenar las respuestas
var cont = false;
xhr.open('POST', 'ajax/ajaxCargarArchivoExcel2.php', true);
var formData = new FormData(document.getElementById('excelForm'));
xhr.onreadystatechange = function() {
  if (xhr.readyState === XMLHttpRequest.LOADING) {
// Divide la respuesta en líneas asumiendo que cada línea contiene un registro JSON
var lineas = xhr.response.split('\n');

// Elimina las líneas vacías
lineas = lineas.filter(function(linea) {
  return linea.trim() !== '';
});
// Combina las líneas con comas para obtener una cadena de registros JSON
var registrosJSON = lineas.join(',');
// Encierra la cadena de registros JSON entre corchetes para obtener un arreglo JSON válido
var arregloJSON = '[' + registrosJSON + ']';
// Parsea la cadena como un arreglo JSON
var registros = JSON.parse(arregloJSON);
  // Obtiene el último registro del arreglo
var ultimoRegistro = registros[registros.length - 1];

  // Muestra el último registro
  console.log('Último Registro:');
  console.log(ultimoRegistro);
  TotalIngresadosCorrectamente = ultimoRegistro.insertedRows
  TotalNoIngresados = ultimoRegistro.erroredRows

	$('#progress-bar').css('width', ultimoRegistro.progress + '%');
	$('#total-inserted').text('Filas ingresadas: ' + ultimoRegistro.insertedRows);
	$('#total-errored').text('Filas erróneas: ' + ultimoRegistro.erroredRows);
  }
  if (xhr.readyState === XMLHttpRequest.DONE) {
    if (xhr.status === 200) {
		console.log('final:', xhr.response);
		// Utiliza una expresión regular para extraer el arreglo exterior
		var arregloExterior = xhr.response.match(/\[.*\]/); 
		if (arregloExterior) {
		  // Analiza el resultado de la expresión regular como un arreglo JSON
		  var arregloRespuesta = JSON.parse(arregloExterior[0]);  
		  var cont = 0
				// Comprueba si la respuesta es un arreglo
				if (Array.isArray(arregloRespuesta)) {
					// Recorre los elementos del arreglo
					arregloRespuesta.forEach(function(elemento, indice) {
						cont ++
				       $('#DetallesErrores').text($('#DetallesErrores').text() + '\n > Rut: ' + elemento.rut + ' Error: ' + elemento.error)+'';
					});
				} else {
				console.error('Error en la solicitud. Código de estado:', xhr.status);
				}
		}

		if(cont > 0 && TotalIngresadosCorrectamente > 0 ){
           		Swal.fire(
			'Excelente!',
			'Se registraron correctamente '+TotalIngresadosCorrectamente+' personas',
			'info'
		  )  
		}else if(cont > 0){
			Swal.fire(
				'Error!',
				'Existen errores en datos excel',
				'error'
			  )  

		}
		else{
			Swal.fire(
				'Excelente!',
				'Se registraron correctamente '+TotalIngresadosCorrectamente+' personas',
				'success'
			  )  
		}

    }else {
		// Se produjo un error, maneja el mensaje de error.
		var errorResponse = JSON.parse(xhr.responseText);

		Swal.fire({
			title: 'Error',
			text: errorResponse.error,
			icon: 'error'
		});
		console.error(errorResponse.error);
	}
  }
};

xhr.send(formData);

   // Prevenir la acción predeterminada del formulario
/*    e.preventDefault();

   // Obtener el formulario y el archivo Excel seleccionado
   var form = this;
   var formData = new FormData(form);

   // Enviar el archivo al servidor y procesarlo
		$.ajax({
			url: 'ajax/ajaxCargarArchivoExcel2.php',
			type: 'POST',
			data: formData,
			processData: false, // Evitar la serialización del formData
			contentType: false, // Evitar la configuración de tipo de contenido
			success: function (data) {
			console.log(data)
			console.log(data.progress)
			if (data.progress !== undefined) {
				console.log("porcentaje cargado :" +data.progress)
					// Actualizar la barra de progreso y los contadores en la página
					$('#progress-bar').css('width', data.progress + '%');
					$('#total-inserted').text('Filas ingresadas: ' + data.insertedRows);
					$('#total-errored').text('Filas erróneas: ' + data.erroredRows);
				}
			},
			error: function () {
				Swal.fire({
					title: 'Error',
					text: 'Ocurrió un error al procesar la solicitud.',
					icon: 'error'
				});
			}
		}); */
	})


$(document).on("submit", "#insertarPersona", function (e) {
		e.preventDefault();

if (validarRut()){
//e.stopPropagation(); // Prevent event propagation
var formData = new FormData(this);
$.ajax({
	url: 'ajax/ajaxPersonas.php',
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
				text: 'Rut ya está registrado',
				icon: 'error'
			});
		} else if (respuesta.resultado == 1) {
			Swal.fire(
				'Excelente!',
				'Registrado con exito!',
				'success'
			  ).then((result) => {
									if (result.value) {
									  window.location = "personas"
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
}else{
	Swal.fire({
		title: 'Error',
		text: 'Rut no valido',
		icon: 'error'
	});
}
	
})

        $(document).on("click", ".btnEditarPersonas", function(){
		var idPersonas = $(this).attr("idRut")
		var datos = new FormData()
		datos.append("id_personas", idPersonas)
		datos.append("tipoOperacion", "editarPersonas")
		$.ajax({
			url: 'ajax/ajaxPersonas.php',
			type: 'POST',
			data: datos,
			processData: false,
			contentType: false,
			success: function(respuesta) {
				var valor = JSON.parse(respuesta)
				console.log(valor.rut)
				$('#formu-editar-personas input[name="id_personas"]').val(valor.rut)
				$('#formu-editar-personas input[name="rut"]').val(valor.rut)
				$('#formu-editar-personas input[name="nombre"]').val(valor.nombre)
				$('#formu-editar-personas input[name="apellido"]').val(valor.apellido)
				$('#formu-editar-personas select[name="area"]').val(valor.area)
			}
		})
	})

    $("#formu-editar-personas").submit(function (e) {
		e.preventDefault()
		var datos = new FormData($(this)[0])
		$.ajax({
			url: 'ajax/ajaxPersonas.php',
			type: 'POST',
			data: datos,
			dataType: 'json',
			processData: false,
			contentType: false,
			success: function (respuesta) {
				console.log(respuesta)
				console.log(respuesta.resultado)
				if (respuesta.resultado == 2) {
					Swal.fire({
						title: 'Error',
						text: 'Persona no existe',
						icon: 'error'
					});
				} else if (respuesta.resultado == 1) {
					Swal.fire(
						'Excelente!',
						'Actualizado con exito!',
						'success'
					  ).then((result) => {
											if (result.value) {
											  window.location = "personas"
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

    $(document).on("click", ".btnEliminarPersonas", function(){
		var idPersonas = $(this).attr("idRut")
		var datos = new FormData()
		datos.append("id_personas", idPersonas)
		datos.append("tipoOperacion", "eliminarPersonas")

		Swal.fire({
		  title: '¿Estás seguro de eliminar persona?',
		  text: "Los registros asociados a la persona no se eliminarán.",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Si, Eliminar!'
		}).then((result) => {
		  if (result.value) {
		  	$.ajax({
				url: 'ajax/ajaxPersonas.php',
				type: 'POST',
				data: datos,
			    dataType: 'json',
				processData: false,
				contentType: false,
				success: function(respuesta) {
                    if (respuesta.resultado == 1) {
                        Swal.fire(
                  'Excelente!',
                  'Persona eliminada con exito!',
                  'success'
                ).then((result) => {
                                      if (result.value) {			
                              window.location = "personas"
                                      }
                                    })
                                }else{
                                    Swal.fire(
                                        'Personas no existe',
                                            'Rut de persona no existe en el sistema',
                                            'error'
                                  )	
                                }
				}
			})
		  }
		})
	})

	$(document).on("click", ".btnActivarPersonas", function(){
		var idPersonas = $(this).attr("idRut")
		var datos = new FormData()
		datos.append("id_personas", idPersonas)
		datos.append("tipoOperacion", "eliminarPersonas")

		Swal.fire({
		  title: '¿Estás seguro de activar persona?',
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Si, Activar!'
		}).then((result) => {
		  if (result.value) {
		  	$.ajax({
				url: 'ajax/ajaxPersonas.php',
				type: 'POST',
				data: datos,
			    dataType: 'json',
				processData: false,
				contentType: false,
				success: function(respuesta) {
                    if (respuesta.resultado == 1) {
                        Swal.fire(
                  'Excelente!',
                  'Persona Activada con exito!',
                  'success'
                ).then((result) => {
                                      if (result.value) {			
                              window.location = "personas"
                                      }
                                    })
                                }else{
                                    Swal.fire(
                                        'Personas no existe',
                                            'Rut de persona no existe en el sistema',
                                            'error'
                                  )	
                                }
				}
			})
		  }
		})
	})

	function validarRut() {
		var rut = document.getElementById('rut').value;	
		rut = rut.replace("‐","-");
		if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(rut)){		
			return false;
		}
		return true;
	}