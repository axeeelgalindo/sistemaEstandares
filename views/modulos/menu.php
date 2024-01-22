       <!-- Main Sidebar Container -->
  
  
       <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="dashboard" class="brand-link d-block" style="display: flex;
    justify-content: center; 
    align-items: center;">
      <img src="dist/img/logoaquachile.png" alt="AdminLTE Logo" class="brand-text" style="opacity: .8;width:170px;margin-left : 15%;">
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">

        <div class="info">
          <a href="#" class="d-block"><?php echo " Bienvenido!</br>". $_SESSION["nombre"]; ?></a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
<!--       <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> -->
     <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
      
               <?php
                $estado1Inicial = false;
          $tabla = ModeloNivelusuario::listarMenuNivelMdl($_SESSION["nombre"]);
          foreach ($tabla as $key => $value) {
            if($value["menu"]=="dashboard" && $value["estado"] != 3)
            {
        echo' 
         <li class="nav-item" >
            <a href="dashboard" class="nav-link" id="tab-dashboard">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Inicio
              </p>
            </a>
          </li>';
          }
        if($value["menu"]=="personas" && $value["estado"] != 3)
            {
        echo' 
          <li class="nav-item">
            <a href="personas" class="nav-link"  id="tab-personas">
            <i class="fas fa-users nav-icon"></i>
              <p>
                Personas
              </p>
            </a>
          </li>';}

          if($estado1Inicial == false){
          $estandareditar;
          $estandaresgestion;
          $tabla1 = ModeloNivelusuario::listarMenuNivelMdl($_SESSION["nombre"]);
          foreach ($tabla1 as $key => $value1) {
            if($value1["menu"]=="estandareseditar"){
              $estandareditar = $value1["estado"];
            }
            if($value1["menu"]=="estandaresgestion"){
              $estandaresgestion = $value1["estado"];
            }
          }
            if(($estandareditar != 3) || ($estandaresgestion != 3))
                {       
                echo' 
              <li class="nav-item"  id="tab-menup">
                <a href="#" class="nav-link" id="tab-menu">
                  <i class="nav-icon fas fa-table"></i>
                  <p>
                    Estandares
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">';

            if($estandareditar != 3){
              echo'
            <li class="nav-item" >
                <a href="estandareseditar" class="nav-link" id="tab-estandareseditar">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Editar</p>
                </a>
              </li>';}
              if($estandaresgestion != 3){
                echo'
              <li class="nav-item">
                <a href="estandaresgestion" class="nav-link"  id="tab-estandaresgestion">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Entrenamiento</p>
                </a>
              </li>';    
            } 
              echo'
                </ul>
              </li>';
              $estado1Inicial = true;
              }}

            if($value["menu"]=="areas" && $value["estado"] != 3)
                {
            echo' 
          <li class="nav-item" >
            <a href="areas" class="nav-link" id="tab-areas">
            <i class="nav-icon fas fa-square"></i>
              <p>
                Areas
              </p>
            </a>
          </li>';}
          if($value["menu"]=="unidades" && $value["estado"] != 3)
          {
          echo' 
        <li class="nav-item" >
          <a href="unidades" class="nav-link" id="tab-unidades">
          <i class="fas fa-building nav-icon"></i>
            <p>
               Unidades
            </p>
          </a>
        </li>';}
            if($value["menu"]=="usuarioajustes" && $value["estado"] != 3)
                {
            echo' 
          <li class="nav-item" >
            <a href="usuarioajustes" class="nav-link" id="tab-usuarioajustes">
       
            <i class="fa-solid fa-user-gear nav-icon"></i>
              <p>
                Perfil
              </p>
            </a>
          </li>';}
            if($value["menu"]=="reportes" && $value["estado"] != 3)
                {
            echo' 
          <li class="nav-item" >
            <a href="reportes" class="nav-link" id="tab-reportes">
            <i class="fa-solid fa-file-arrow-down nav-icon"></i>
             <p>
                Reportes
              </p>
            </a>
          </li>';} } ?>

          <?php
          $usuarios;
          $nivelesusuario;
          $tabla = ModeloNivelusuario::listarMenuNivelMdl($_SESSION["nombre"]);
          foreach ($tabla as $key => $value) {
            if($value["menu"]=="usuarios"){
              $usuarios = $value["estado"];
            }
            if($value["menu"]=="nivelesusuario"){
              $nivelesusuario = $value["estado"];
            }
          }
            if(($usuarios != 3) || ($nivelesusuario != 3) )
                {
                                echo' 
                <li class="nav-item"  id="tab-menup2">
                  <a href="#" class="nav-link" id="tab-menu2">
                    <i class="fas fa-lock nav-icon "></i>
                    <p>
                      Administrador
                      <i class="fas fa-angle-left right"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">';
        
            if($usuarios != 3){
              echo'
            <li class="nav-item" >
            <a href="usuarios" class="nav-link" id="tab-usuarios">
            <i class="far fa-circle nav-icon"></i>
              <p>
                Usuarios
              </p>
            </a>
          </li>';}
          if($nivelesusuario != 3){
            echo'
              <li class="nav-item">
                <a href="nivelesusuario" class="nav-link"  id="tab-nivelesusuario">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Niveles de usuario</p>
                </a>
              </li>';
            }
          echo'
            </ul>
          </li>';
          } ?>

          <li class="nav-item" >
            <a href="salir" class="nav-link">

            <i class="fa-solid fa-share-from-square nav-icon"></i>
              <p>
                Salir
              </p>
            </a>
          </li>

        </ul>
      </nav>

            <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
