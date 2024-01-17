<?php
session_start();
 if( !isset($_SESSION["nombre"]) ){
    echo '<script>
	window.location = "login.php"
</script>';

    }
require_once "controllers/enrutamiento.controller.php";
require_once "controllers/template.controller.php";
require_once "models/personas.model.php";
require_once "models/area.model.php";
require_once "models/estandar.model.php";
require_once "models/usuario.model.php";
require_once "models/nivelusuario.model.php";
require_once "models/unidades.model.php";

$template = new ControllerTemplate();
$template -> template();
?>