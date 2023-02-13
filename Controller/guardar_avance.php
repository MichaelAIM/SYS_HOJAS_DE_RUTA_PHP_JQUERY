<?php
@session_start();
require_once("../Models/actividades.php");
require_once("../Models/funcionario.php");
require_once("../Models/Personas.class.php");
require_once("../Models/phpmailer/class.phpmailer.php");
require_once("../Models/phpmailer/class.smtp.php");
$act = new Activity();
$func = new Funcionario();
$indices = array_keys($_POST);
$arr=[];
for ($i=0; $i < count($indices); $i++) { 
    $index = explode('-', $indices[$i]);
    if ($index[0] === "options") {
        $arr[$i]['n_act'] = $index[1];
        if ($_POST[$indices[$i]] == "on") {
            $arr[$i]['resp'] = 1;            
        }else{
            $arr[$i]['resp'] = 2;
        }
        if ($_POST['obs-'.$index[1]] != "") {
            $arr[$i]['obs'] = $_POST['obs-'.$index[1]];
        }else{
            $arr[$i]['obs'] = "Sin Observación";
        }
    }
}
$arr = array_values($arr);
for ($i=0; $i < count($arr); $i++) { 
    $ins = $act-> Actividad_Realizada($_POST['funcionario'],$arr[$i]['n_act'],$arr[$i]['resp'],$arr[$i]['obs'],$_SESSION['rut']);
}
$pre_real = $act->countActividadRealizada($_POST['funcionario']);
$porcentaje = $pre_real*100/$_POST['total_preguntas'];
$updatePorcentaje = $func->actualizarPorcentaje($_POST['funcionario'],$porcentaje);
if ($porcentaje >= 100) {
    //actualizar estado funcionario
    $func->actualizarEstado($_POST['funcionario'],3);
    $datosFun = $func->Buscar_correos_funcionario($_POST['funcionario']);    
    $mailJefe = $datosFun[0]['per_email']; 
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
                            <p>Estimado Sr(a): <strong><?=$datosFun[0]['per_nombre'];?></strong></p>
                            <p>Jefatura: <?=$datosFun[0]['dep_nombre'];?></p>
                            <p>
                                Junto con saludar, en el contexto de la inducción año <?=date('Y');?>  descrito en la Resolución Exenta N°1398 de fecha 13 de junio del año 2019, informo a usted que el proceso  inducción del (la) funcionario(a) a su cargo D. <?=$datosFun[0]['NombreFunc'];?>  ha finalizado exitosamente con fecha <?=date("d/m/Y");?> del presente año y se procedió a cerrar su inducción en la plataforma intranet.
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
}
?>
