<?php 
    require_once("../Models/funcionario.php");
    require_once("../Models/hoja_ruta.php");
    require_once("../Models/estamentos.php");
    require_once("../Models/unidad.php");
    require_once("../Models/c_juridica.php");

    $func = new Funcionario();
    $hoja = new HR();
    $uni = new unidad();
    $est = new estamentos();
    $cj = new calidad_juridica();

    $hr = $hoja->index();
    $esta = $est->index();
    $uni = $uni->index();
    $calidad = $cj->index();
    $func = $func->getJefaturas();
   
    $data = array(
        'funcionario' => $func['jef'],
        'unidad' => $uni['data'],
        'hr' => $hr['data'],
        'est' => $esta['data'],
        'cj' => $calidad['data']
    );

    // highlight_string(print_r($data,true));
    // echo json_encode($data);
?>
