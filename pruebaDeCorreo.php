<?php
require_once("Models/phpmailer/class.phpmailer.php");
require_once("Models/phpmailer/class.smtp.php");
ob_start();
?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        </head>
        <body>
            <p>
                <p><strong>Estimad@:</strong></p>
                <p>Correo de Prueba desde agresiones.ssa@gmail.com.</p>
                <br />
            </p>
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
        $mail->Hostname = "gmail.com";    
        $mail->Username = "agresiones.ssa@gmail.com";
        $mail->Password = "salud2022";
        $mail->CharSet = "US-ASCII";
        $mail->Encoding = "7BIT";    
        //////END SERVER CONFIG/////

        ///////////RECIPIENTS/////////////  
        $mail->SetFrom("agresiones@gmail.com",utf8_decode("Sistema de Agresiones SSA"));
        $mail->addAddress('michael.aguirre.saavedra@gmail.com');
        $mail->addCC("yerko.miranda1@saludarica.cl"); //COPIA

        //////////////CONTENT/////////////////////
        $asunto = utf8_decode('SISTEMA DE AGRESIONES - APROBACIÓN DE SOLICITUD N° ' . $det[0]['id']);

        $mail->Subject = $asunto;
        $mail->Body = $body;
        
        // var_dump($mail);
        if(!$mail->Send()){
            error_log('No envio correo');
        }else{
            echo true;
        }
    }catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    }catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
    }
?>
