$(document).on("submit", "#insertarUsuario", function (e) {
    e.preventDefault();
    const pass1 = document.getElementById("password1").value;
    const pass2 = document.getElementById("password2").value;

    console.log(pass1)
    console.log(pass2)

    if(pass2 != pass1){
        Swal.fire({
            title: 'Error',
            text: 'Debe ingresar contraseñas iguales',
            icon: 'error'
        });
    }else{
        //e.stopPropagation(); // Prevent event propagation
            var formData = new FormData(this);
            $.ajax({
            url: 'ajax/ajaxUsuario.php',
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
                        text: 'Email ya está registrado',
                        icon: 'error'
                    });
                } else if (respuesta.resultado == 1) {
                    Swal.fire(
                        'Excelente!',
                        'Registrado con exito!',
                        'success'
                    ).then((result) => {
                                            if (result.value) {
                                            window.location = "usuarios"
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
     }

})


$(document).on("click", ".btnEditarUsuario", function(){
    var idUsuario = $(this).attr("idUsuario")
    var datos = new FormData()
    datos.append("id_usuario", idUsuario)
    datos.append("tipoOperacion", "editarUsuario")
    $.ajax({
        url: 'ajax/ajaxUsuario.php',
        type: 'POST',
        data: datos,
        dataType: 'json',
        processData: false,
        contentType: false,
        success: function(respuesta) {

         console.log(respuesta.id)
            $('#editarUsuario input[name="id_usuario"]').val(respuesta.id)
            $('#editarUsuario input[name="nombre"]').val(respuesta.nombre)
            $('#editarUsuario input[name="email"]').val(respuesta.email)
            $('#editarUsuario input[name="password"]').val(respuesta.password)
			$('#editarUsuario select[name="nivel"]').val(respuesta.nivel_usuario)



    
        }
    })
})

$("#editarUsuario").submit(function (e) {
    e.preventDefault()
    var datos = new FormData($(this)[0])
    $.ajax({
        url: 'ajax/ajaxUsuario.php',
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
                    text: 'Usuario no existe',
                    icon: 'error'
                });
            } else if (respuesta.resultado == 2 ) {
                Swal.fire({
                    title: 'Error',
                    text: 'Email de usuario ya existe',
                    icon: 'error'
                });
            } else if (respuesta.resultado == 1  ) {
                Swal.fire(
                    'Excelente!',
                    'Actualizado con exito!',
                    'success'
                  ).then((result) => {
                                        if (result.value) {
                                          window.location = "usuarios"
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
$(document).on("click", ".btnEliminarUsuario", function(){

    var UsuarioActi = $(this).attr("UsuarioActi")
    var Estado;
    if(UsuarioActi == "Activo"){
      Estado = "deshabilitar"
    }else{
      Estado = "habilitar"
    }

    var idUsuario = $(this).attr("idUsuario")
    var datos = new FormData()
    datos.append("id_usuario", idUsuario)
    datos.append("tipoOperacion", "deshabilitarUsuario")
    Swal.fire({
      title: '¿Estás seguro de '+Estado+' usuario?',
      text: "Los cambios no son reversibles!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, '+Estado+' !'
    }).then((result) => {
      if (result.value) {
          $.ajax({
            url: 'ajax/ajaxUsuario.php',
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
                        text: 'Usuario no existe',
                        icon: 'error'
                    });
                } else if (respuesta.resultado == 1) {
                    Swal.fire(
                        Estado,
                        'Usuario a sido '+Estado+'.',
                        'success'
                      ).then((result) => {
                                            if (result.value) {
                                              window.location = "usuarios"
                                            }
                                          })
                }         
            }

        })
      }
    })
})

$("#editarPerfil").submit(function (e) {
    e.preventDefault()
    var datos = new FormData($(this)[0])
    $.ajax({
        url: 'ajax/ajaxUsuario.php',
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
                    text: 'Email ya está registrado',
                    icon: 'error'
                });
            } else if (respuesta.resultado == 2 ) {
                Swal.fire({
                    title: 'Error',
                    text: 'Usuario no existe',
                    icon: 'error'
                });
            } else if (respuesta.resultado == 1  ) {
                Swal.fire(
                    'Excelente!',
                    'Actualizado con exito!',
                    'success'
                  ).then((result) => {
                                        if (result.value) {
                                          window.location = "usuarioajustes"
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

document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.getElementById("password");
    const togglePasswordIcon = document.getElementById("togglePassword");



    togglePasswordIcon.addEventListener("click", function () {
        if (passwordInput.type === "password") {
            passwordInput.type = "text"; // Mostrar la contraseña
            togglePasswordIcon.classList.remove("fa-eye");
            togglePasswordIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password"; // Ocultar la contraseña
            togglePasswordIcon.classList.remove("fa-eye-slash");
            togglePasswordIcon.classList.add("fa-eye");
        }
    });
})
$(document).on("click", "#togglePassword1", function(){
    
    const passwordInput1 = document.getElementById("password1");
    const togglePasswordIcon1 = document.getElementById("togglePassword1");

      if (passwordInput1.type === "password") {
            passwordInput1.type = "text"; // Mostrar la contraseña
            togglePasswordIcon1.classList.remove("fa-eye");
            togglePasswordIcon1.classList.add("fa-eye-slash");
        } else {
            passwordInput1.type = "password"; // Ocultar la contraseña
            togglePasswordIcon1.classList.remove("fa-eye-slash");
            togglePasswordIcon1.classList.add("fa-eye");
        }

})
$(document).on("click", "#togglePassword2", function(){
    const passwordInput2 = document.getElementById("password2");
    const togglePasswordIcon2 = document.getElementById("togglePassword2");

        if (passwordInput2.type === "password") {
            passwordInput2.type = "text"; // Mostrar la contraseña
            togglePasswordIcon2.classList.remove("fa-eye");
            togglePasswordIcon2.classList.add("fa-eye-slash");
        } else {
            passwordInput2.type = "password"; // Ocultar la contraseña
            togglePasswordIcon2.classList.remove("fa-eye-slash");
            togglePasswordIcon2.classList.add("fa-eye");
        }
})
$(document).on("click", "#togglePassword3", function(){
    
    const passwordInput3 = document.getElementById("password3");
    const togglePasswordIcon3 = document.getElementById("togglePassword3");

      if (passwordInput3.type === "password") {
            passwordInput3.type = "text"; // Mostrar la contraseña
            togglePasswordIcon3.classList.remove("fa-eye");
            togglePasswordIcon3.classList.add("fa-eye-slash");
        } else {
            passwordInput3.type = "password"; // Ocultar la contraseña
            togglePasswordIcon3.classList.remove("fa-eye-slash");
            togglePasswordIcon3.classList.add("fa-eye");
        }

})
