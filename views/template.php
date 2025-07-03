<?php
// views/template.php

// 1) Arranca sesión y captura la ruta
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
$ruta = $_GET['ruta'] ?? '';


// Suprime la impresión de warnings PHP dentro de JS:
ini_set('display_errors', 0);
$plantaId = $_SESSION['planta_id'] ?? null;
$userNivel = $_SESSION['id_nivel'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <script>
    const PLANTA_ID = <?= json_encode($plantaId, JSON_NUMERIC_CHECK) ?>;
    const USER_NIVEL = <?= json_encode($id_nivel, JSON_NUMERIC_CHECK) ?>;
  </script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Sistema Estandares</title>
  <link rel="icon" href="smarticono.png" type="image/x-icon">

  <link href="plugins/select2/css/select2.min.css" rel="stylesheet" />

  <?php include __DIR__ . '/modulos/links.php'; ?>



  <!-- jQuery & jQuery UI -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="plugins/jquery-ui/jquery-ui.min.js"></script>
  <script src="plugins/select2/js/select2.full.min.js"></script>

  <style>
    .password-container {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      cursor: pointer;
    }
  </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
  <div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center"
      style="background-color:rgba(244,246,249,0.86);">
      <img class="animation__shake" src="dist/img/loading.gif" alt="Preloader" height="150" width="150">
    </div>

    <!-- Navbar -->

    <?php
    include 'modulos/header.php';
    // Agregar modales por ruta
    if (isset($_GET['ruta'])) {
      $modalFile = __DIR__ . "/modulos/modales/modales-" . $_GET["ruta"] . ".php";
      if (file_exists($modalFile)) {
        include $modalFile;
      }
    }
    ?>

    <!-- Sidebar Menu -->
    <?php include __DIR__ . '/modulos/menu.php'; ?>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <?php
      // Enrutamiento de páginas
      if (isset($_GET['ruta'])) {
        $enrutar = new ControllerEnrutamiento();
        $enrutar->enrutamiento();
      }
      ?>
    </div>
    <!-- /.content-wrapper -->
    <script type="text/javascript">
      /// Url actual
      let url = window.location.href;
      /// Elementos d e li
      const tabs = ["dashboard", "personas", "estandareseditar", "estandaresgestion", "porcentajeseditar", "porcentajesvisualizar", "usuarios", "areas", "reportes", "nivelesusuario", "usuarioajustes", "unidades"];
      tabs.forEach(e => {
        /// Agregar .php y ver si lo contiene en la url
        if (url.indexOf(e) !== -1) {
          /// Agregar tab- para hacer que coincida la Id
          if (e == "estandareseditar" || e == "estandaresgestion") {
            setActive("tab-menu");
            ExpandirMenu("tab-menup")
          }
          if (e == "nivelesusuario" || e == "usuarios") {
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


  <!-- Select2 -->

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
  <!-- <script src="plugins/chart.js/Chart2.min.js"></script> -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>-->
  <!--<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    -->



  <!--<script src="plugins/chart.js/Chart4.min.js"></script> -->

  <!--<script src="plugins/chart.js/plugin.js"></script> -->

  <!--<script src="https://cdn.jsdelivr.net/npm/chart.js@3.0.0/dist/chart.min.js"></script>-->
  <!--<script src="plugins/chart.js/datalabel2.js"></script> -->


  <!--<script type="text/javascript" src="views/dist/js/secciones.js"></script>-->
  <script type="text/javascript" src="views/dist/js/personas.js"></script>
  <script type="text/javascript" src="views/dist/js/area.js"></script>
  <script type="text/javascript" src="views/dist/js/usuario.js"></script>
  <script type="text/javascript" src="views/dist/js/nivelusuario.js"></script>
  <script type="text/javascript" src="views/dist/js/estandar.js"></script>
  <script type="text/javascript" src="views/dist/js/unidades.js"></script>
  <script type="text/javascript" src="views/dist/js/planta.js"></script>



  <!-- Chart.js-->
  <script src="plugins/chart.js/Chart.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.7.0"></script>





  <script src="plugins/jvectormap/jquery-jvectormap-2.0.5.min.js"></script>
  <script src="plugins/jvectormap/jquery-jvectormap-us-aea-en.js"></script>


  <script src="views/dist/js/dashboard.js"></script>

  <script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>
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
      //Initialize Select2 Elements
      $('.select2').select2();

      //Existing DataTables initialization
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        "pageLength": 25
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
        "pageLength": 25
      });
    });
  </script>
</body>

</html>