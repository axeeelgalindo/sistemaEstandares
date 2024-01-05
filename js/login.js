$(function(){
           $("#btn1").on("click", function(e){
               e.preventDefault();
               var f = $(this);
               var formData = new FormData(document.getElementById("EnvioLogin"));
               formData.append("dato", "valor");
               //formData.append(f.attr("name"), $(this)[0].files[0]);
               $.ajax({
                   url: "ajax/ajaxLoguear.php",
                   type: "post",
                   dataType: "html",
                   data: formData,
                   cache: false,
                   contentType: false,
                   processData: false,
               }).done(function(res){
 
                   console.log(res)
                   if (res== 1) {
                       window.location = "dashboard";
                   } else{
                       $("#resultado").html("<div class='alert alert-danger'>Datos incorrectos. Inténtelo nuevamente.</div>");
                   }
                   });
           });
       })