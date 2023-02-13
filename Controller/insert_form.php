<?php
@session_start();
require_once("../Models/funcionario.php");
require_once("../Models/Personas.class.php");
require_once("../Models/phpmailer/class.phpmailer.php");
require_once("../Models/phpmailer/class.smtp.php");

$unidad = explode(";", $_POST['inpUnidad']);
$_POST['inpUnidad'] = $unidad[0];
$_POST['nombre_unidad'] = $unidad[1];
if ($_POST['inpJefatura'] != "nuevo") {    
    $jefe = explode(";", $_POST['inpJefatura']);
    $_POST['jefe'] = $jefe[0];
    $_POST['nombre_jefe'] = $jefe[1];
}else{
    $_POST['jefe'] = $_POST['nuevo_jefe'];
    $_POST['nombre_jefe'] = $_POST['nuevo_jefe_nom'];
}
$_POST['session'] = $_SESSION['rut'];
// highlight_string(print_r($_POST,true));
$func = new Funcionario();
$persona = new Personas();
$ing = $func->Ingresar($_POST);
$dataPersona = $persona->datos_persona($_POST['inpRut']);
if ($dataPersona == NULL || $dataPersona == "") {
    $pass = explode('-', $_POST['inpRut']);
    $clave = md5($pass[0]);
    $insertPersona = $persona->ingresar_Persona($_POST['inpRut'],$_POST['inpNombre']." ".$_POST['inpPaterno']." ".$_POST['inpMaterno'],$clave,$_POST['inpF_nac'],1, $_POST['inpEmail']);
    $insertDepto = $persona->ingresar_datos_funcionario($_POST['inpRut'], $_POST['inpUnidad'], 4, 1, NULL);
    $permiso = $persona->ingresar_Permiso($_POST['inpRut'], 1);
}
$mailJefe = $persona->buscar_email($_POST['jefe']);
$mailFuncionario = $_POST['inpEmail'];
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
                <p>
                    Estimado Sr(a): <strong><?=$_POST['nombre_jefe'];?></strong>
                </p>
                <p>Jefatura: <?=$_POST['nombre_unidad'];?></p>
                <p>
                    Junto con saludar, en el contexto del proceso de inducción año <?=date('Y');?> contenido en la Resolución Exenta N°1398 de fecha 13 de junio del año 2019, informo a usted que ha ingresado con fecha <strong><?=date("d/m/Y",strtotime($_POST['inp_fIng']));?></strong> del presente año a su Subdepto./Sección/Unidad el (la) funcionario(a) <strong> D. <?=$_POST['inpNombre'].' '.$_POST['inpPaterno'].' '.$_POST['inpMaterno'];?></strong> Por lo anterior, se informa que usted debe ingresar a Intranet del SS Arica con su contraseña y dirigirse a ícono “Hoja de Ruta” para completar lo requerido en relación a los pasos de inducción del funcionario a su cargo, asimismo puede designar a un agente inductor (funcionario de su dependencia) para realizar ciertas actividades de la hoja de ruta correspondiente.
                </p>

                <p>Lo descrito se debe cumplir en un plazo no superior a 15 días desde la recepción de la presente notificación, con la finalidad de dar cumplimiento a los lineamientos de la Dirección del Servicio Civil.</p>

                <p>Se solicita, si correspondiera por su hoja de ruta; el completar el anexo 11 el cual se encuentra en la plataforma, en la etapa de Supervisión , el cual va con firma digital y corresponde a una encuesta que permita crear un espacio de confianza para finalizar dicha actividad.</p>

                <p>Ante cualquier consulta puede contactar a la Sección Desarrollo de Competencias y Educación Continua a los anexos 584741- 584742 o a los correos electrónicos elizabeth.escobar@saludarica.cl / maribel.bravo@saludarica.cl / pilar.vargas1@saludarica.cl </p>

                <p>Saludos cordiales.</p>
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
        $mail->addCC($mailFuncionario); //COPIA
        $mail->addCC('capacitacionssa@saludarica.cl'); //COPIA
        $mail->addCC('barbara.codoceo1@saludarica.cl'); //COPIA
        $mail->addCC('raquel.reyes@saludarica.cl'); //COPIA
        $mail->addCC('rene.villablanca@saludarica.cl'); //COPIA
        $mail->addCC('patricia.silva@saludarica.cl'); //COPIA
        $mail->addCC('christian.alzamora@saludarica.cl'); //COPIA
        $mail->addBCC('michael.aguirre@saludarica.cl'); //COPIA

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
