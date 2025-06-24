<?php
session_start();
require_once "../models/login.model.php";

class ajaxPesaje
{

  public $email;
  public $password;

  public function ValidarLogin()
  {
    $datos = array(
      "email" => $this->email,
      "password" => $this->password
    );

    $respuesta = ModeloLogin::mdlValidarLogin($datos);

    if ($respuesta["result"] == 1) {
      $_SESSION["autenticar"] = "ok";
      $_SESSION["nombre"] = $this->email;
      $_SESSION["nivel_usuario"] = $respuesta["nivel_usuario"];

      if (isset($respuesta["planta_id"])) {
        $_SESSION["planta_id"] = $respuesta["planta_id"];
      }
    }

    echo ($respuesta["result"]);
  }
}

$tipoOperacion = $_POST["tipoOperacion"];
if ($tipoOperacion == "ValidarLogin") {
  $validarLogin = new ajaxPesaje();
  $validarLogin->email = $_POST["email"];
  $validarLogin->password = $_POST["password"];
  $validarLogin->ValidarLogin();
}
?>