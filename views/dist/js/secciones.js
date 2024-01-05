$(document).on("submit", "#formu-nuevo-Secciones", function (e) {
    e.preventDefault();
    //e.stopPropagation(); // Prevent event propagation
		var datos = new FormData($(this)[0])
		$.ajax({
			url: 'ajax/ajaxSecciones.php',
			type: 'POST',
			data: datos,
			processData: false,
			contentType: false,
			success: function(respuesta) {
				console.log(respuesta)
				if (respuesta == 1) {
		Swal.fire(
  'Excelente!',
  'Sección registrada con exito!',
  'success'
).then((result) => {
					  if (result.value) {			
              window.location = "secciones"
					  }
					})
				}else{
                    Swal.fire(
                        'Sección ya registrada',
                            'Debe ingresar un nombre diferente',
                            'error'
                  )	
                }
			}
		})
	})
       $(document).on("click", ".btnEditarSecciones", function(){
		var idSecciones = $(this).attr("idSeccion")
		var datos = new FormData()
		datos.append("id_Secciones", idSecciones)
		datos.append("tipoOperacion", "editarSecciones")
		$.ajax({
			url: 'ajax/ajaxSecciones.php',
			type: 'POST',
			data: datos,
			processData: false,
			contentType: false,
			success: function(respuesta) {
				var valor = JSON.parse(respuesta)
				console.log(valor.id_Secciones)
	$('#formu-editar-Secciones input[name="NombreSecciones"]').val(valor.name)
	$('#formu-editar-Secciones input[name="id_seccion"]').val(valor.id_Secciones)
			}
		})
	})
    $("#formu-editar-Secciones").submit(function (e) {
		e.preventDefault()
		var datos = new FormData($(this)[0])
		$.ajax({
			url: 'ajax/ajaxSecciones.php',
			type: 'POST',
			data: datos,
			processData: false,
			contentType: false,
			success: function(respuesta) {
                console.log(respuesta)
				if (respuesta == 1) {
		Swal.fire(
  'Excelente!',
  'Sección actualizada con exito!',
  'success'
).then((result) => {
					  if (result.value) {			
              window.location = "secciones"
					  }
					})
				}else{
                    Swal.fire(
                        'Sección ya registrada',
                            'Debe ingresar un nombre diferente',
                            'error'
                  )	
                }
			}
		})
	})

    $(document).on("click", ".btnEliminarSecciones", function(){
		var idSecciones = $(this).attr("idseccion")
		var datos = new FormData()
		datos.append("id_Secciones", idSecciones)
		datos.append("tipoOperacion", "eliminarSecciones")

		Swal.fire({
		  title: '¿Estás seguro de eliminar Sección?',
		  text: "Los cambios no son reversibles!",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Si, Elimina!'
		}).then((result) => {
		  if (result.value) {
		  	$.ajax({
				url: 'ajax/ajaxSecciones.php',
				type: 'POST',
				data: datos,
				processData: false,
				contentType: false,
				success: function(respuesta) {
                    if (respuesta == 1) {
                        Swal.fire(
                  'Excelente!',
                  'Secciones eliminado con exito!',
                  'success'
                ).then((result) => {
                                      if (result.value) {			
                              window.location = "secciones"
                                      }
                                    })
                                }else{
                                    Swal.fire(
                                        'Sección tiene asociados planos',
                                            'Debe eliminar estos planos para eliminar Secciones',
                                            'error'
                                  )	
                                }
				}
			})
		  }
		})
	})