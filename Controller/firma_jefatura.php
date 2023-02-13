<?php 
// highlight_string(print_r($_POST,true));
@session_start();
require_once("../Models/anexo.php");
require_once("../Assets/encript.php");
require_once("../Models/funcionario.php");
require_once("../Assets/phpqrcode/qrlib.php");
require_once("../Models/phpmailer/class.phpmailer.php");
require_once("../Models/phpmailer/class.smtp.php");
$an = new Anexo();
$func = new Funcionario();

$id_firma = $an->Ingresar_anexo_firma( $_POST, $_POST['anexo_R'], $_SERVER['REMOTE_ADDR'] );
if ($id_firma != '') {
    //Encriptar ID firma
    $datoencript = encrypt($id_firma);
    // Crear QR
    $content = "https://www.ssarica.cl/induccion/views/firma.php?id=".$datoencript;
    ob_start();
    QRcode::png($content);
    $result_qr_content_in_png = ob_get_contents();
    ob_end_clean();
    header("Content-type: text/html");
    $result_qr_in_base64 = "data:image/jpeg;base64,".base64_encode($result_qr_content_in_png);
    // UPDATE
    $an->actualizar_firma_anexo( $id_firma, $result_qr_in_base64 );
    if ($_POST['anexo'] == 1) {
        $datosFun = $func->Buscar_correos_funcionario($_POST['id']);
        $email = $datosFun[0]['per_email'];
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
                         <p>Junto con saludar, mediante el presente se informa a usted que hoy <?=date('d/m/Y');?> D. <?=$_POST['nombreFunc'];?> completó su anexo correctamente.</p>
                         <p>Por favor, continuar con la hoja de ruta.</p>
    <br>
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
            $mail->SetFrom("capacitacionssa@saludarica.cl",utf8_decode("SISTEMA DE HOJAS DE RUTA SSA"));
            $mail->addAddress($email);
            $mail->addCC('capacitacionssa@saludarica.cl'); //COPIA

            //////////////CONTENT/////////////////////
            $asunto = utf8_decode('PROCESO DE INDUCCIÓN LABORAL SERVICIO DE SALUD ARICA.');
            $mail->Subject = $asunto;
            $mail->Body = $body;
            
            // var_dump($mail);
            if(!$mail->Send()){
                error_log(strtoupper($mail->ErrorInfo));
                echo false;
            }else{
                echo true;
            }
        }catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
        }catch (Exception $e) {
            echo $e->getMessage(); //Boring error messages from anything else!
        }
    }else{
        echo true;
    }

}else{
    echo false;
}

?>
