
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SmartEyes | Login</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<style>
.video-slider-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: auto; /* Mantener la relación de aspecto */
  z-index: 0;
}
.screen{
    position: relative;
    width: 100%;
    height: 100vh;
    overflow: hidden;
    background-position: center;
    padding-top: 15%;
    text-align: center;
}
.video-slider-bg::-webkit-media-controls {
  display: none !important;
}
.video-slider-bg::-webkit-media-controls-start-playback-button {
  display: none !important;
}
.custom-logo{
  margin-bottom: 30px;;
}

</style>
<section class="screen" style="background-image:url('https://www.aquachile.com/wp-content/uploads/2022/06/portada-home.jpg');">
<video controls="false" autoplay playsinline name="media" loop muted class="d-none d-md-block video-slider-bg">
<source src="dist/img/FondoWeb-webm.webm" type="video/webm">  
</video>
<div class="login-box" style="display:inline-block;">

 <div style="position:relative;">
 <img width="200" height="43" src="dist/img/logoaquachile.png" class="custom-logo" alt="AquaChile" decoding="async">
 </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Ingrese usuario para iniciar sesión</p>

      <form  method="post" id="EnvioLogin">
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div id="resultado"></div>
        <div class="row">
          <!-- /.col -->
          <div class="col-12">
          <input type="hidden"  name="tipoOperacion" value="ValidarLogin">
          <input type="hidden"  name="CargarPlano" value="<?php echo $id; ?>">
            <button type="submit" id="btn1" class="btn btn-primary btn-block">ingresar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
      <!-- /.social-auth-links -->
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
</section>
<!-- /.login-box -->
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<script src="js/login.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
