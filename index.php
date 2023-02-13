<?php
session_start();
if (isset($_SESSION['rut'])) {
?>
<!doctype html>
<html lang="es">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv='cache-control' content='no-cache'>
  <meta http-equiv='expires' content='0'>
  <meta http-equiv='pragma' content='no-cache'>
  <!-- Bootstrap CSS -->
  <link href="Assets/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="Assets/bootstrap-icons.css">
  <link rel="stylesheet" href="Assets/css/sweetalert2.css">
  <link rel="stylesheet" href="Assets/EasyAutocomplete/easy-autocomplete.css">
  <link rel="stylesheet" href="Assets/EasyAutocomplete/easy-autocomplete.themes.css">
  <link rel="stylesheet" href="Assets/DataTables/datatables.css">
  <link rel="stylesheet" href="Assets/css/app.css">
  <title>Hojas de Ruta</title>
  <style></style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar-->
        <div class="border-end bg-primary bg-gradient" id="sidebar-wrapper">
            <div class="sidebar-heading border-bottom bg-primary bg-gradient text-white">
                <div class="d-inline-block" style="width: 13rem;">
                    <p class="nav-title-ssa mb-0">SERVICIO DE SALUD ARICA</p>
                    <div class="hr-width" style="height: 5px;background: linear-gradient(90deg, #0f69b4 50%, #c0392b 50%);"></div>
                    <p class="nav-title-sys">Sistema de Hojas de Ruta</p>
                </div>
                <div class="btn btn-primary d-inline-block float-end mt-2" id="sidebarToggle">
                    <i class=" fs-4 bi-chevron-double-left" role="button" tabindex="0" id="btn-menu"></i>                    
                </div>
            </div>
            <div class="list-group list-group-flush">
                <a class="list-group-item bg-primary bg-gradient-inverse p-3" style="min-height: 69px;"></a>
                <?php if ($_SESSION['sesion'] == 40 || $_SESSION['sesion'] ==51) { ?>
                    <a class="nav-link list-group-item list-group-item-action list-group-item-light text-white bg-primary bg-gradient p-3" href="Views/form_ingreso.php">
                        Nuevo Funcionario 
                        <i class="fs-4 bi-person-plus float-end me-3"></i>
                    </a>
                <?php } ?>
                <a class="nav-link list-group-item list-group-item-action list-group-item-light text-white bg-primary bg-gradient p-3" id="menuPendientes" href="Views/mistareas.php">
                    Pendientes 
                    <i class="fs-4 bi-list-task float-end me-3"></i>
                </a>
                <a class="nav-link list-group-item list-group-item-action list-group-item-light text-white bg-primary bg-gradient p-3" href="Views/busqueda.php">
                    Finalizados 
                    <i class="fs-4 bi-search float-end me-3"></i>
                </a>
            </div>
        </div>
        <!-- Page content wrapper-->
        <div id="page-content-wrapper">
            <!-- Top navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon me-2"></span> HOJAS DE RUTAS</button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
                            <li class="nav-item pt-0">
                                <a class="pt-0" href="#" onclick="window.location.reload(true);">
                                    <i class="fs-4 bi-house-door float-end me-3"></i>
                                </a>
                            </li>
                            <li class="nav-item dropdown" style="padding-top: 7px;">
                                <a class="dropdown-toggle px-3" id="navbarDropdown" href="#" style="text-decoration: none;" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=$_SESSION['nom'];?></a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
<!--                                     <a class="dropdown-item" href="#!">Action</a>
                                    <a class="dropdown-item" href="#!">Another action</a> -->
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#!">Salir</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
            <!-- Page content-->
            <div class="container p-sm-5 pt-5" id="content">
                <div class="text-center">
                    <h1 class="mt-1">Bienvenid@ al Sistema de Hojas de Ruta</h1>
                    <img src="Assets/img/inicio.jpg" width="50%" alt="" class="mt-4">
                </div>
            </div>
        </div>
    </div>
<script src="Assets/js/jquery-3.6.0.min.js"></script>
<script src="Assets/js/bootstrap.bundle.min.js"></script>
<script src="Assets/js/sweetalert2.min.js"></script>
<script src="Assets/EasyAutocomplete/jquery.easy-autocomplete.min.js"></script>  
<script src="Assets/js/jquery.form.min.js"></script>
<script src="Assets/js/jquery.validate.min.js"></script>
<script src="Assets/DataTables/datatables.js"></script>
<script src="Assets/js/app.js?v=<?=rand();?>"></script>
</body>
</html>
<?php 
}else{
    $GoTo = "../Index.php";
    header(sprintf("Location: %s", $GoTo));
}
?>
