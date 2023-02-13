<?php
@session_start();
require_once("../Models/funcionario.php");
$func = new Funcionario();
$rut = $_SESSION['rut'];
$depto_id = $_SESSION['sesion'];
$depto = 0;
switch ($depto_id) {
    case 51:
        $depto = 1;
        break;
    case 40:
        $depto = 2;
        break;
    case 42:
        $depto = 3;
        break;
    default:
        $depto = 0;
}
if ($depto > 0) {
    $f = $func->Index($rut,$depto);
}else{
    $pendientes = $func->Index2($rut);
        $f = array();
    for($i = 0; $i < count($pendientes); $i++){
        $idx = $pendientes[$i]['id'];
        if(isset($f[$idx])){
            $f[$idx]['es_jefe'] = 4;
            $f[$idx]['es_inductor'] = 5;
        }else{
            $f[$idx] = $pendientes[$i];
        }
    }
}
$f = array_values($f);

// $f = $pendientes['func'];
// highlight_string(print_r($f,true));
// highlight_string(print_r($_SESSION,true));
?>
