<?php
@session_start();
require_once("../Models/Personas.class.php");
require_once("../Models/funcionario.php");
require_once("../Models/phpmailer/class.phpmailer.php");
require_once("../Models/phpmailer/class.smtp.php");

$persona = new Personas();
$func = new Funcionario();
$mailJefe = $persona->buscar_email($_POST['rut']); 
if ($mailJefe != '') {
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
                <p>En el contexto del proceso de inducción laboral del servicio de salud Arica. Es necesario que realice la siguiente encuesta.</p>
                <br />
                <form action="https://www.ssarica.cl/induccion/Views/anexo.php" method="post">
                    <input type="hidden" value="<?=$_POST['id'];?>" name="id" />
                    <input type="hidden" value="<?=$_POST['anexo'];?>" name="anexo"/>
                    <input type="hidden" name="tipo" value="1"/>
                    <input type="submit" name="btnReset" value="ir a la Encuesta" style="display: inline-block;font-weight: 400; color: white;    text-align: center; vertical-align: middle; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-color: #0f69b4 !important; border: 1px solid transparent; padding: 0.375rem 0.75rem; font-size: 1rem; line-height: 1.5; border-radius: 0.25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;">
                    <br /><br />
                    <strong>Saludos Coordiales.</strong> 
                    <br /><br />
                </form>
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
        $mail->SMTPDebug = false;
        $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ));
        
        $mail->SMTPAuth = true;    
        $mail->SMTPSecure = "tls"; // sets the prefix to the server    
        $mail->Host = "smtp.gmail.com";
        $mail->Hostname = "saludarica.cl";    
        $mail->Username = "capacitacionssa@saludarica.cl";
        $mail->Password = "salud2020";
        $mail->Port = 587;
        $mail->CharSet = "US-ASCII";
        $mail->Encoding = "7BIT";    
        //////END SERVER CONFIG/////

        ///////////RECIPIENTS/////////////  
        $mail->SetFrom("capacitacionssa@saludarica.cl",utf8_decode("Capacitación SSA"));
        $mail->addAddress($mailJefe);
        // $mail->addCC('michael.aguirre@saludarica.cl'); //COPIA
        // $mail->addCC('capacitacionssa@saludarica.cl'); //COPIA
        // $mail->addBCC('michael.aguirre@saludarica.cl'); //COPIA

        //////////////CONTENT/////////////////////
        $asunto = utf8_decode('PROCESO DE INDUCCIÓN LABORAL SERVICIO DE SALUD ARICA.');
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
}

?>
