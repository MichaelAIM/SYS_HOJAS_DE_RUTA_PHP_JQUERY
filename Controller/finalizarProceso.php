<?php
@session_start();
require_once("../Models/funcionario.php");
require_once("../Models/Personas.class.php");
require_once("../Models/actividades.php");
require_once("../Models/phpmailer/class.phpmailer.php");
require_once("../Models/phpmailer/class.smtp.php");
$act = new Activity();
$func = new Funcionario();
    //actualizar estado funcionario
    $func->actualizarEstado($_POST['funcionario'],3);
    $datosFun = $func->Buscar_correos_funcionario($_POST['funcionario']);
    $datosJefe = $func->Buscar_correos_funcionario($datosFun[0]['jefatura']);
    $email = $datosFun[0]['per_email']; 
    $emailJefe = $datosJefe[0]['per_email']; 
    if ($email != '') {
        ob_start();
?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            </head>
            <body>
                <div style="max-width: 50rem;">
                    <P>
                        <p>Estimado Sr(a): <strong><?=$datosFun[0]['per_nombre'];?></strong></p>
                        <p>Jefatura: <?=$datosFun[0]['dep_nombre'];?></p>
                        <p>
                            Junto con saludar, en el contexto de la inducción año <?=date('Y');?>  descrito en la Resolución Exenta N°1398 de fecha 13 de junio del año 2019, informo a usted que el proceso  inducción del (la) funcionario(a) a su cargo <strong>D. <?=$datosFun[0]['NombreFunc'];?> </strong> ha finalizado exitosamente con fecha <?=date("d/m/Y");?> del presente año y se procedió a cerrar su inducción en la plataforma intranet.
                        </p>

                        <p>Ante cualquier consulta puede contactar a la Sección Desarrollo de Competencias y Educación Continua a los anexos 584741- 584742 o a los correos electrónicos elizabeth.escobar@saludarica.cl / maribel.bravo@saludarica.cl / pilar.vargas1@saludarica.cl </p>

                        <p>Un cordial saludo,</p>
                        <p><strong>Sección Desarrollo de Competencias y Educación Continua</strong></p>
                        <p><strong>Servicio de Salud Arica</strong></p>
                    </P>
                </div>
            </body>
        </html>
<?php
    $body = ob_get_clean();
    $mail = new PHPMailer; 
    try {
        //////SERVER CONFIG/////    
        $mail->IsSMTP();
        $mail->IsHTML(true);    
        $mail->SMTPDebug = 3;
        $mail->SMTPSecure = "ssl"; // sets the prefix to the server    
        $mail->Port = 465;

        $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ));
        
        $mail->SMTPAuth = true;    
        $mail->Host = "smtp.gmail.com";
        $mail->Hostname = "saludarica.cl";    
        $mail->Username = "capacitacionssa@saludarica.cl";
        $mail->Password = "salud2020";
        $mail->CharSet = "US-ASCII";
        $mail->Encoding = "7BIT";    
        //////END SERVER CONFIG/////

        ///////////RECIPIENTS/////////////  
        $mail->SetFrom("capacitacionssa@saludarica.cl",utf8_decode("Capacitación SSA"));
        $mail->addAddress($emailJefe);
        $mail->addCC($email); //COPIA
        // $mail->addBCC('michael.aguirre@saludarica.cl'); //COPIA
        // $mail->addCC('capacitacionssa@saludarica.cl'); //COPIA

        //////////////CONTENT/////////////////////
        $asunto = utf8_decode('PROCESO DE INDUCCIÓN LABORAL SERVICIO DE SALUD ARICA.');
        $mail->Subject = $asunto;
        $mail->Body = $body;
        
        // var_dump($mail);
        if(!$mail->Send()){
            error_log('No envio correo');
            echo false;
        }else{
            echo true;
        }
    }catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    }catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
    }
}
?>
