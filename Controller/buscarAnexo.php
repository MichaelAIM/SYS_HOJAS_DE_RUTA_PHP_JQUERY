<?php
require_once("../Models/anexo.php");
require_once("../Models/funcionario.php");
$an = new Anexo();
$func = new Funcionario();
    $firmas = '';
    $idF = $_POST['id'];
    $anexo = $_POST['anexo'];
    $tipo = $_POST['tipo'];
    $f = $an->buscar_por_anexo($anexo, $idF);
    $preg = array();
    for ($i=0; $i < count($f); $i++) { 
        $idx = $f[$i]['anexo_categoria'];
        if(isset($preg[$idx])){
        }else{
            $preg[$idx]['nombre'] = $f[$i]['nombre'];
            $preg[$idx]['preguntas'] = array();
        }
        array_push($preg[$idx]['preguntas'],$f[$i]);
    }
$id_realizado = $an->buscar_anexo_realizado($anexo, $idF);
$datosFunc = $func->buscarJefatura($idF);
if ($id_realizado != '') {
    $firmas = $an->buscar_firmas_anexo($id_realizado['id']);
}
$preg = array_values($preg);
?>
