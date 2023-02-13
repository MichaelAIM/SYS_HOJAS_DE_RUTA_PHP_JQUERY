<?php
@session_start();
require_once("../Models/funcionario.php");
$func = new Funcionario();

$f = $func->index_buscador();

// $f = $pendientes['func'];
// highlight_string(print_r($f,true));
// highlight_string(print_r($_SESSION,true));
?>
