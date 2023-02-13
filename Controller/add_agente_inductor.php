<?php
@session_start();
require_once("../Models/Personas.class.php");
require_once("../Models/funcionario.php");
require_once("../Models/phpmailer/class.phpmailer.php");
require_once("../Models/phpmailer/class.smtp.php");

$_POST['session'] = $_SESSION['rut'];

$persona = new Personas();
$func = new Funcionario();

$insert = $func->ingresar_agente_inductor($_POST);
if ($insert) {
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
                <div style="max-width: 50rem;">
                    <P>
                        <p>Estimado(a)</p>
                        <p>Junto con saludar, mediante el presente se informa a usted que hoy <?=date('d/m/Y');?>  su jefatura directa le ha adjudicado la importante tarea de ser el Agente Inductor del (la) nuevo(a) funcionario(a) de su unidad D. <?=$_POST['nombreFunc'];?>. </p>
                        <p>Para revisar las tareas que debe realizar debe ingresar a su intranet ventana Inducción o bien hacer clic el siguiente link <a href="https://www.ssarica.cl/induccion/index.php"> Ir al sistema </a>  , en donde debe completar cada celda de acuerdo a las actividades que vaya completando.</p>
                        <p>Atentamente,</p>    
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
            // $mail->SMTPDebug = false;
            // $mail->SMTPSecure = "tls"; // sets the prefix to the server    
            // $mail->Port = 587;
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
            $mail->addAddress($mailJefe);
            $mail->addCC('michael.aguirre@saludarica.cl'); //COPIA
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
}
?>
