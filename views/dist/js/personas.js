$(document).on("submit", "#excelForm", function (e) {

	var TotalIngresadosCorrectamente = 0;
	var TotalNoIngresados = 0;

	e.preventDefault();
var xhr = new XMLHttpRequest();

xhr.open('POST', 'ajax/ajaxCargarArchivoExcel2.php', true);

//var formData = new FormData(document.getElementById('excelForm'));
var formData = new FormData(this);
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
			'Se realizaron cambios correctamente en '+TotalIngresadosCorrectamente+' personas',
			'info'
		  )  

/* 		  Swal.fire(
			'Excelente!',
			'Se realizaron cambios correctamente en '+TotalIngresadosCorrectamente+' personas',
			'success'
		  ).then((result) => {
								if (result.value) {
								  window.location = "personas"
								}
							  }) */
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
				'Se realizaron cambios correctamente en '+TotalIngresadosCorrectamente+' personas',
				'success'
			  ).then((result) => {
									if (result.value) {
									  window.location = "personas"
									}
								  })
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
				$('#formu-editar-personas select[name="areaSecundaria"]').val(valor.area_secundaria)
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

		var onBtnClicked = (btnId) => {
			Swal.close();
			var modalElement = document.getElementById("modal-insertar-nuevo-personas");

			if (btnId == "agregar") 
			{
				$('#excelForm input[name="tipoOperacion"]').val("insertarPersonas")
				$('#excelForm .titulo').text("Cargar archivo Excel - Agregar Personal");
				$('#excelForm .TextoRutValidar').text("Al cargar el documento se validará que rut NO exista en el sistema, ademas que el campo area exista en el sistema.");
				// Abre el modal
				$(modalElement).modal('show');
			}else if(btnId == "desactivar"){
				$('#excelForm input[name="tipoOperacion"]').val("desactivarPersonas")
				$('#excelForm .titulo').text("Cargar archivo Excel - Desactivar Personal");
				$('#excelForm .TextoRutValidar').text("Al cargar el documento se validará que rut exista en el sistema, ademas que el campo area exista en el sistema.");		
					// Abre el modal
				$(modalElement).modal('show');
			}else if(btnId == "activar"){
				$('#excelForm input[name="tipoOperacion"]').val("activarPersonas")
				$('#excelForm .titulo').text("Cargar archivo Excel - Activar Personal");
				$('#excelForm .TextoRutValidar').text("Al cargar el documento se validará que rut exista en el sistema, ademas que el campo area exista en el sistema.");
				$(modalElement).modal('show');
			}else if(btnId == "cambiararea"){
				$('#excelForm input[name="tipoOperacion"]').val("cambiarArea")
				$('#excelForm .titulo').text("Cargar archivo Excel - Modificar Área");
				$('#excelForm .TextoRutValidar').text("Al cargar el documento se validará que rut exista en el sistema, ademas que el campo area exista en el sistema.");
				$(modalElement).modal('show');
			}				
		  };

	$(document).on("click", ".btnAgregarPersonaExcel", function(){
			Swal.fire({
			  icon: "warning",
			  showConfirmButton: false,
			  showCloseButton: true,
			  html: `
			<div class="custom-modal">
				 <h3>Seleccione una acción</h3>
				<div >
				  <button class="btn btn-primary col-12 my-2" onclick="onBtnClicked('agregar')"><i class="fa-solid fa-user-plus"></i> Agregar Personal</button>
				  <button class="btn btn-primary col-12 my-2" onclick="onBtnClicked('cambiararea')"><i class="fa-solid fa-user-gear"></i> Modificar Área</button>
				  <button class="btn btn-danger col-12 my-2" onclick="onBtnClicked('desactivar')"><i class="fa-solid fa-user-slash"></i>Desactivar Personal</button>
				  <button class="btn btn-success col-12 my-2" onclick="onBtnClicked('activar')"><i class="fa-solid fa-user-check"></i> Activar Personal</button>
				  <button class="btn btn-secondary col-12 my-2" onclick="onBtnClicked('cancel')">Cancelar</button>
				</div>
				</div>`
			});  
		})

	function validarRut() {
		var rut = document.getElementById('rut').value;	
		rut = rut.replace("‐","-");
		if (!/^[0-9]+[-|‐]{1}[0-9kK]{1}$/.test(rut)){		
			return false;
		}
		return true;
	}