
$(document).on("change", ".nivel", function(){
    var idEstandar = $(this).attr("idMenuEstandar");
    var valorSelect = $(this).val()
    var datos = new FormData()
    datos.append("id_menu_estandar", idEstandar)
    datos.append("valor_select", valorSelect)
    datos.append("tipoOperacion", "ModificarEstadoNivel")
 
          $.ajax({
            url: 'ajax/ajaxNivelUsuario.php',
            type: 'POST',
            data: datos,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(respuesta) {
                if (respuesta.resultado == 1) {
                   toastr.success('Actualizado con exito')      
                }else{
                   toastr.error('No se pudo actualizar estado')      

                }
            }
        })
});