<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema Estandares</title>
  <link rel="icon" href="smarticono.png" type="image/x-icon">
  <?php 
     include 'modulos/links.php';
     ?>
</head>
<style>.password-container {
    position: relative;
}

.toggle-password {
    position: absolute;
    top: 50%;
    right: 10px;
    transform: translateY(-50%);
    cursor: pointer;
}</style>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Preloader -->
  <div class="preloader flex-column justify-content-center align-items-center" style="background-color:rgb(244 246 249 / 86%);">
    <img class="animation__shake" src="dist/img/loading.gif" alt="AdminLTELogo" height="150" width="150">
  </div>
  <!-- Navbar -->
  <?php 
    include 'modulos/header.php';
     include "modulos/modales/modales-".$_GET["ruta"].".php";
     ?>
  <!-- /.navbar -->
     <!-- Sidebar Menu -->
     <?php 
     include 'modulos/menu.php';
     ?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
       
  <?php 
      if( isset($_GET["ruta"])) {
        $enrutar = new ControllerEnrutamiento();
        $enrutar -> enrutamiento();
      }
    ?>
    <!-- /.content -->
  </div>

  <script type="text/javascript">
/// Url actual
let url = window.location.href;
/// Elementos de li
const tabs = ["dashboard", "personas","estandareseditar","estandaresgestion","usuarios","areas","reportes","nivelesusuario","usuarioajustes"];
tabs.forEach(e => {
    /// Agregar .php y ver si lo contiene en la url
    if (url.indexOf(e ) !== -1) {
        /// Agregar tab- para hacer que coincida la Id
        if(e == "estandareseditar" || e == "estandaresgestion"){
          setActive("tab-menu");
          ExpandirMenu("tab-menup")      
        }
        if(e == "nivelesusuario" || e == "usuarios"){
          setActive("tab-menu2");
          ExpandirMenu("tab-menup2")      
        }

        setActive("tab-" + e);
    }
});
/// Funcion que asigna la clase active
function setActive(id) {
    document.getElementById(id).setAttribute("class", "nav-link active");
}
function ExpandirMenu(id) {
    document.getElementById(id).setAttribute("class", "nav-item menu-is-opening menu-open");
}
 </script>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2023 <a href="https://adminlte.io">SmartEyes</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.1
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
 <script src="plugins/chart.js/Chart2.min.js"></script>
<!--<script src="plugins/chart.js/plugin.js"></script> -->

<!--<script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>-->
<script src="plugins/chart.js/datalabel2.js"></script>

<script src="dist/js/pages/dashboard.js"></script>
<!--<script type="text/javascript" src="views/dist/js/secciones.js"></script>-->
<script type="text/javascript" src="views/dist/js/personas.js"></script>
<script type="text/javascript" src="views/dist/js/area.js"></script>
<script type="text/javascript" src="views/dist/js/usuario.js"></script>
<script type="text/javascript" src="views/dist/js/nivelusuario.js"></script>
<script type="text/javascript" src="views/dist/js/estandar.js"></script>
<script src="dist/js/pages/dashboard3.js"></script>
<script src="plugins/sweetalert/sweetalert.js"></script>
<!-- Toastr -->
<script src="plugins/toastr/toastr.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
      "pageLength": 25 // Configura el número de registros por página
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
      "pageLength": 25 // Configura el número de registros por página
    });
  });
</script>
</body>
</html>
