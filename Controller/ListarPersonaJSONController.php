<?php
	require_once("../Models/Personas.class.php");
	$per = new Personas();
	$datos_persona = $per->lista_persona();
	echo json_encode($datos_persona);
?>