<?php
 session_start();
require_once "../models/login.model.php";

Class ajaxPesaje {

        public function ValidarLogin(){
            $datos = array(
                            "email"=>$this->email,		
                            "password"=>$this->password           		            			            												
                        );  
            $respuesta = ModeloLogin::mdlValidarLogin($datos);
            if($respuesta["result"] == 1){
               
				$_SESSION["autenticar"] = "ok";
				$_SESSION["nombre"] = $this->email;
             }
           echo ($respuesta["result"]);

         //  echo ($_SESSION["nombre"]);
        }
    }
        $tipoOperacion = $_POST["tipoOperacion"];
        if($tipoOperacion == "ValidarLogin") {
            $validarLogin = new ajaxPesaje();
            $validarLogin -> email = $_POST["email"];
            $validarLogin -> password = $_POST["password"];
            $validarLogin -> cargarplano = $_POST["CargarPlano"];
            $validarLogin ->ValidarLogin();
        }
    ?>