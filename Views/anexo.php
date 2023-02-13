<?php
// $_POST['id'] = 2;
// $_POST['anexo'] = 1;
// $_POST['tipo'] = 2;
// highlight_string(print_r($_POST,true));
if ( isset($_POST['id']) && isset($_POST['anexo']) && isset($_POST['tipo']) ) {
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv='cache-control' content='no-cache'>
        <meta http-equiv='expires' content='0'>
        <meta http-equiv='pragma' content='no-cache'>
        <!-- Bootstrap CSS -->
        <link href="../Assets/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../Assets/bootstrap-icons.css">
        <link rel="stylesheet" href="../Assets/css/sweetalert2.css">
        <link rel="stylesheet" href="../Assets/css/app.css">
        <title>Anexo  - Hoja de Ruta</title>
        <style>
            .inputError {
                border: 1px solid #dc3545 !important;
                outline: none !important;
                box-shadow: 0 0 3px #dd6f7a5e !important;
            }
        </style>    
    </head>
    <body>
        <div class="container mt-5">
            <input type="hidden" id="idFhdr" value="<?=$_POST['id'];?>">
            <input type="hidden" id="idAhdr" value="<?=$_POST['anexo'];?>">
            <input type="hidden" id="idThdr" value="<?=$_POST['tipo'];?>">
            <div id="contentAnexo"></div>
        </div>

        <script src="../Assets/js/jquery-3.6.0.min.js"></script>
        <script src="../Assets/js/bootstrap.bundle.min.js"></script>
        <script src="../Assets/js/sweetalert2.min.js"></script>
        <script src="../Assets/js/jquery.form.min.js"></script>
        <script>
            $(document).ready(function(){
                var params = {
                    id : $('#idFhdr').val(),
                    anexo : $('#idAhdr').val(),
                    tipo : $('#idThdr').val()
                }
                var url = 'contentAnexo11.php';
                if ($('#idAhdr').val() == 2) {
                    url = 'contentAnexo12.php';
                }

                $('#contentAnexo').load(url, params , function(response){
                    $('#btnTerminar').click(function(e){
                        e.preventDefault();
                        $('#btnTerminar').html('<span id="spanBtn" class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Enviando...').prop('disabled', true);
                        var opt = {
                            error: function() {
                                Swal.fire(
                                    'Error!',
                                    'No se agrego el anexo, vuelva a intentarlo',
                                    'error'
                                );
                                $('#spanBtn').remove();
                                $('#btnTerminar').text('Terminar Anexo').prop('disabled', false);
                                console.log('error 1');
                            },
                            success: function(response){
                                if (response) {
                                    Swal.fire({
                                        position: 'top-end',
                                        icon: 'success',
                                        title: 'El archivo cargo exitosamente!',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                    $('#contentAnexo').empty();
                                    $('#contentAnexo').load(url, params);
                                }else{
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: 'No se guardo el anexo, por favor vuelva a intentarlo',
                                    });
                                    $('#spanBtn').remove();
                                    $('#btnTerminar').text('Terminar Anexo').prop('disabled', false);
                                    console.log('error 2');
                                }
                            }
                        };
                        if(validar_respuestas()){
                            Swal.fire({
                                title: "Ingrese su rut (EJ:11222333-4)",
                                input: "text",
                                showCancelButton: true,
                                backdrop: false,
                                allowOutsideClick: false,
                                confirmButtonText: "Guardar",
                                cancelButtonText: "Cancelar",
                                preConfirm: (rut) => {
                                    if (!validaRut(rut)){
                                        $(".swal2-input").addClass('inputError');
                                        return false;
                                    }else{
                                        return rut;
                                    }
                                },
                                allowOutsideClick: () => !Swal.isLoading()
                            }).then(resultado => {
                                if (resultado) {
                                    if(resultado.dismiss != 'cancel'){
                                        $('#firmante').val(resultado.value);
                                        $("#formAnexo").ajaxSubmit(opt);
                                    }
                                }
                            });
                        }else{
                            Swal.fire(
                                'Error!',
                                'Debe marcar todas preguntas',
                                'error'
                            );
                            $('#spanBtn').remove();
                            $('#btnTerminar').text('Terminar Anexo').prop('disabled', false);
                            console.log('error 3');
                        }
                    });

                    //boton firmar jefatura
                    $('#firmarJefe').click(function(e){
                        $('#firmarJefe').html('<span id="spanBtn" class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span>Enviando...').prop('disabled', true);                        
                        var send = {
                            id : $('#idFhdr').val(),
                            anexo_R : $('#id_anexoRR').val(),
                            anexo : $('#idAhdr').val(),
                            tipo : $('#idThdr').val(),
                            nombreFunc: $('#nombreFunc').val()
                        }
                        Swal.fire({
                            title: "Ingrese su rut (EJ:11222333-4)",
                            input: "text",
                            showCancelButton: true,
                            backdrop: false,
                            allowOutsideClick: false,
                            confirmButtonText: "Guardar",
                            cancelButtonText: "Cancelar",
                            preConfirm: (rut) => {
                                if (!validaRut(rut)){
                                    $(".swal2-input").addClass('inputError');
                                    return false;
                                }else{
                                    return rut;
                                }
                            },
                            allowOutsideClick: () => !Swal.isLoading()
                        }).then(resultado => {
                            if (resultado) {
                                if(resultado.dismiss != 'cancel'){
                                    send.firmante = resultado.value;
                                    let enviar = true
                                    // console.log($('#rut_func').val()+' != '+resultado.value);
                                    if ($('#idAhdr').val() == 1 && $('#rut_func').val() != resultado.value) {
                                        enviar = false;
                                    }
                                    if (enviar) {
                                        $.post('../Controller/firma_jefatura.php',send,function(response){
                                            if (response) {
                                                 Swal.fire({
                                                    position: 'top-end',
                                                    icon: 'success',
                                                    title: 'El anexo se firmó exitosamente!',
                                                    showConfirmButton: false,
                                                    timer: 1500
                                                });
                                                $('#contentAnexo').empty();
                                                $('#contentAnexo').load(url, send);
                                            }else{
                                                Swal.fire(
                                                    'Error!',
                                                    'Por favor, Comuníquese con el administrador del sistema',
                                                    'error'
                                                );
                                            }
                                        });
                                    }else{
                                        Swal.fire(
                                            'Error!',
                                            'El rut es incorrecto',
                                            'error'
                                        );
                                        $('#spanBtn').remove();
                                        $('#firmarJefe').text('Firmar Anexo');
                                    }
                                }
                            }
                        });
                    });
                });
            });

            function validar_respuestas(){
                var response = true;
                jQuery.ajaxSetup({async:false});
                $('[name^="options"]').each(function( index, element) {
                     if ($('[name="'+element.name+'"]:checked').length == 0) {
                        console.log(($('[name="'+element.name+'"]:checked').length));
                        response = false;
                     }
                });
                jQuery.ajaxSetup({async:true});
                return response;
            }

            function validaRut(rut){
                var rexp = new RegExp(/^([0-9])+\-([kK0-9])+$/);
                if(rut.match(rexp)){
                    var valor = rut.replace('.','');
                    valor = valor.replace('--','-');
                    valor = valor.replace('-','');
                    var elRut = valor.slice(0,-1);
                    var digitov = valor.slice(-1).toUpperCase();
                    var factor = 2;
                    var suma = 0;
                    var dv;
                    for(i=(elRut.length-1); i>=0; i--){
                     factor = factor > 7 ? 2 : factor;
                     suma += parseInt(elRut[i])*parseInt(factor++);
                    }
                    dv = 11 -(suma % 11);
                    if(dv == 11){
                     dv = 0;
                    }else if (dv == 10){
                     dv = "K";
                    }
                    if(dv == digitov){
                     return true;
                    }else{
                     return false;
                    }
                }else{     
                 return false;
                }
            }

        </script>
    </body>
</html>
<?php 
}
?>
