<?php
@session_start();
require_once("../Models/anexo.php");
require_once("../Assets/encript.php");
require_once("../Assets/phpqrcode/qrlib.php");
require_once("../Models/Personas.class.php");
require_once("../Models/phpmailer/class.phpmailer.php");
require_once("../Models/phpmailer/class.smtp.php");
// highlight_string(print_r($_POST,true));
$persona = new Personas();
$email = 'capacitacionssa@saludarica.cl';
$anx = 12;
if ($_POST['id_anexo'] == 1) {
    $email = $persona->buscar_email($_POST['rut_func']);
    $anx = 11;
}

$validar = 'false';

if ($_POST['id_anexo'] == 2) {
    if ($_POST['tipo'] == 1){
        if ($_POST['firmante'] == $_POST['rut_func']){
            $validar = 'true';            
        }
    }
}else{
    if ($_POST['tipo'] == 2) {
        if ($_POST['firmante'] == $_SESSION['rut']){
            $validar = 'true';
        }
    }
}
// echo "valor = ".$validar;
if (!$validar) {
    echo 'false';
}else{
    $an = new Anexo();
    $indices = array_keys($_POST);
    $arr=[];
    for ($i=0; $i < count($indices); $i++) { 
        $index = explode('-', $indices[$i]);
        if ($index[0] === "options") {
            $arr[$i]['n_act'] = $index[1];
            if ($_POST[$indices[$i]] == "on") {
                $arr[$i]['resp'] = 1;            
            }else if ($_POST[$indices[$i]] == "off"){
                $arr[$i]['resp'] = 2;
            }else{
                $arr[$i]['resp'] = $_POST[$indices[$i]];
            }
            if ($_POST['obs-'.$index[1]] != "") {
                $arr[$i]['obs'] = $_POST['obs-'.$index[1]];
            }else{
                $arr[$i]['obs'] = "Sin Observación";
            }
        }
    }
    $arr = array_values($arr);
    $id_realizado = $an->Ingresar_anexo_realizado( $_POST );
    $id_firma = $an->Ingresar_anexo_firma( $_POST, $id_realizado, $_SERVER['REMOTE_ADDR'] );
    for ($i=0; $i < count($arr); $i++) { 
        $ins = $an->Ingresar_anexo_respuestas($_POST,$arr[$i]['n_act'],$arr[$i]['resp'],$arr[$i]['obs'],$id_realizado);
    }
    // // echo $id_firma;
    if ($id_firma != '') {
    // //     //Encriptar ID firma
        $datoencript = encrypt($id_firma);
    //     // Crear QR
        $content = "https://www.ssarica.cl/induccion/views/firma.php?id=".$datoencript;
        ob_start();
        QRcode::png($content);
        $result_qr_content_in_png = ob_get_contents();
        ob_end_clean();
        header("Content-type: text/html");
        $result_qr_in_base64 = "data:image/jpeg;base64,".base64_encode($result_qr_content_in_png);
        // UPDATE
        $an->actualizar_firma_anexo( $id_firma, $result_qr_in_base64 );
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
                         <p>Por favor, dar visto bueno y firmar el anexo.</p>
                         <form action="https://www.ssarica.cl/induccion/Views/anexo.php" method="post">
                             <input type="hidden" value="<?=$_POST['id_func'];?>" name="id" />
                             <input type="hidden" value="<?=$_POST['id_anexo'];?>" name="anexo"/>
                             <input type="hidden" name="tipo" value="1"/>
                             <input type="submit" value="Revisar Anexo <?=$anx;?>" style="display: inline-block;font-weight: 400; color: white;    text-align: center; vertical-align: middle; cursor: pointer; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-color: #0f69b4 !important; border: 1px solid transparent; padding: 0.375rem 0.75rem; font-size: 1rem; line-height: 1.5; border-radius: 0.25rem; transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;">
                             <br /><br />
                             <strong>Saludos Coordiales.</strong> 
                             <br /><br />
                         </form>
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
        }else{
            echo 'ok';
        }
    }catch (phpmailerException $e) {
        echo $e->errorMessage(); //Pretty error messages from PHPMailer
    }catch (Exception $e) {
        echo $e->getMessage(); //Boring error messages from anything else!
    }
 }
}
?>
