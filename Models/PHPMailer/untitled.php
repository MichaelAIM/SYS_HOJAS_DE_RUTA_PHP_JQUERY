<?php
require("class.phpmailer.php");

	$mail = new PHPMailer();
	$mail->Host = "ssl://smtp.gmail.com";
	$mail->From = "sergio.aguilera@gmail.com";
	$mail->Port = 465;
	$mail->IsSMTP();
	$mail->SMTPDebug = true;
	$mail->SMTPAuth = true;
	$mail->Username = "sergio.aguilera@saludarica.cl";
	$mail->Password = "Ser/170136187";
	$mail->FromName = "Sergio";
	$mail->Subject = 'Asunto Prueba';
	$mail->AddAddress('serginho177@gmail.com');
	$body = "<strong>Mensaje</strong><br><br>hola<br>";
	$body.= "<i>Enviado por http://blog.unijimpe.net</i>";
	$mail->Body = $body;
	$mail->IsHTML(true);
	$mail->Send();
	$msg = "Mensaje enviado correctamente";
?>