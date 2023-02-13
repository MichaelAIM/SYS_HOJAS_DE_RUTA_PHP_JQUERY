$(document).ready(function() {
    $('.nav-link').each(function(index, element) {
        $(this).click(function(e) {
            e.stopPropagation();
            $('#content').empty();
            if ($(this).is(':not(.salir)')) {
                $('.nav-link').removeClass('active');
                var url = $(this).attr('href');
                $(this).addClass('active');
                if (url == 'Views/mistareas.php') {
                    showLoadingImage();
                }
                $('#content').load(url, '', function(e) {
                    if (url == 'Views/mistareas.php') {
                        cargarPendientes();
                    }
                    if (url == 'Views/form_ingreso.php') {
                        setForm();
                    }
                    if (url == 'Views/busqueda.php') {                        
                        cargarFinalizadas();
                    }
                });
                e.preventDefault();
            } else {
                window.close();
            }
        });
    });
    const sidebarToggle = document.body.querySelector('#sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
            $('#btn-menu').toggleClass("bi-layout-text-window-reverse");
        });
    }
});

function setForm() {
    $('#otraJefatura').hide();
    var options = {
        url: "Controller/ListarPersonaJSONController.php",
        getValue: "per_nombre",
        list: {
            match: {
                enabled: true
            },
            onSelectItemEvent: function() {
                var value = $("#nombreFuncionario").getSelectedItemData().per_rut;
                $("#rutFuncionario").val(value).trigger("change");
            },
            sort: {
                enabled: true
            }
        }
    };

    $("#nombreFuncionario").easyAutocomplete(options);

    $('#inpJefatura').change(function(e) {
        if ($(this).val() == "nuevo") {
            $(".easy-autocomplete").addClass("w-100");
            $('#otraJefatura').show();
        } else {
            $('#otraJefatura').hide();
        }
    });

    var opt = {
        error: function() {
            Swal.fire(
                'Error!',
                'no se agrego el nuevo funcionario, por favor intente otra vez.',
                'error'
            );
        },
        success: function(response) {
            if (response) {
                hideLoadingImage();
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'El archivo cargo exitosamente!',
                    showConfirmButton: false,
                    timer: 1500
                });
                // $("#formIngresoFuncionario").reset();
                $(":input").val('');
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'No se agregó el Funcionario!',
                });
            }
        }
    };

    $("#btnSendForm").click(function(e) {
        e.preventDefault();
        showLoadingImage();        
        $("#formIngresoFuncionario").attr('action', 'Controller/insert_form.php');
        if(validaRut($("#inpRut").val())){
            $("#formIngresoFuncionario").ajaxSubmit(opt);
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: 'No se agregó el Funcionario, debe ingresar un rut Valido!',
            });
            hideLoadingImage();
        }
        return false;
    });
}

function cargarPendientes() {

    var table = $('#TablaPendientes').DataTable({
        "pageLength": 50,
        "language": {
            "url": "assets/DataTables/lenguajeDatatable.js"
        },
        "initComplete": function(settings, json) {
            hideLoadingImage();
        }
    });

    var opt = {
        error: function() {
            Swal.fire(
                'Error!',
                'Por favor intente denuevo!',
                'error'
            );
        },
        success: function(response) {
            if (response != 'false') {
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'El archivo cargo exitosamente!',
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#staticBackdrop').modal('hide');
                $('#menuPendientes').click();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Por favor intente denuevo!',
                });
            }
        }
    };

    $("#btnGuardarAvance").click(function(e) {
        e.preventDefault();
        $("#form-upData").attr('action', 'Controller/guardar_avance.php');
        Swal.fire({
            title: "La información ingresada, no puede ser modificada posteriormente",
            text: "¿Está seguro de que desea Guardar los cambios?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "Sí, Guardar",
            cancelButtonText: "Cancelar",
        }).then(resultado => {
            if (resultado.value) {
                $("#form-upData").ajaxSubmit(opt);
            }
        });
        return false;
    });
}

function cargarFinalizadas() {

    var table = $('#TablaFinalizadas').DataTable({
        "pageLength": 50,
        "language": {
            "url": "assets/DataTables/lenguajeDatatable.js"
        }
    });
}

function cerrarProceso(fun) {
    let params = {
      funcionario:fun
    }
    $.post('controller/finalizarProceso.php', params, function(response) {
        if (response) {
          $('#menuPendientes').click();
        }
    });
}

function Set_Modal(fun, id_hr, depto, es_jefe, es_inductor) {
    let params = '';
    $('#mdl_tittle_nom').text('');
    $('#idFuncionarioActivity').val(fun);

    $('#mdl_tittle_hr').text(id_hr);
    params = {
        'id_func': fun,
        'id_HR': id_hr
    }

    $('#btn_send_email_jefe').unbind().click(function(e){
        $(this).prop('disabled', true);
        $.post('controller/darAvisoJefatura.php', params, function(response) {
            if (response) {
                 Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: 'El correo fue enviado exitosamente!',
                    showConfirmButton: false,
                    timer: 1500
                });
            }else{
                Swal.fire(
                    'Error!',
                    'No se envio el correo porfavor intente denuevo.',
                    'error'
                );
            }
            $(this).prop('disabled', false);
        });
    });

    $("#modad_body_dt").empty();
    $.post('controller/detalle_funcionario.php', params, function(response) {
        let nombreFuncionarioEnProceso = response['funcionario'][0]['nombre'] + ' ' + response['funcionario'][0]['apellido'] + ' ' + response['funcionario'][0]['apellido_2'];
        $('#mdl_tittle_nom').text(nombreFuncionarioEnProceso);
        if (response != null && response != "") {
            var max_soc = 0;
            var var_soc = 0;
            var max_entre = 0;
            var var_entre = 0;
            var max_fide = 0;
            var var_fide = 0;
            var max_sup = 0;
            var var_sup = 0;
            var max_eva = 0;
            var var_eva = 0;
            var porcentaje = {};
            $.each(response['actividades'], function(index, value) {
                if (typeof porcentaje[value.Etapa_id] == "undefined") {
                    porcentaje[value.Etapa_id] = {
                        max: 0,
                        var: 0
                    }
                }
                porcentaje[value.Etapa_id].max++;
                if (value.respuesta != null) {
                    porcentaje[value.Etapa_id].var++;
                }
            });

            var porcents = {};
            for (var indx in porcentaje) {
                porcents[indx] = cal_porcentaje(porcentaje[indx].max, porcentaje[indx].var);
            }
            response['porcent'] = porcents;
            response['depto'] = depto;
            response['es_jefe'] = es_jefe;
            response['es_inductor'] = es_inductor;

            var depto_comiento = 0;
            if (depto > 0) {
                depto_comiento = depto;
            } else if (es_jefe > 0) {
                depto_comiento = es_jefe;
            } else if (es_inductor > 0) {
                depto_comiento = es_inductor;
            } else {
                depto_comiento = 0;
            }

            $("#modad_body_dt").load('Views/body_modal.php', response, function(e) {
                // script for tab steps
                $('.order-card').click(function(e) {
                    $('.bg-c-gray.order-card').unbind("click");                    
                    var etapa = $(this).children('.id-etapa').val();
                    var etapa0 = $('#idetapa0').val();
                    $('#process-tab').empty();
                    $("#process-tab").load('Views/view_activity.php', { response, num: etapa, etapainicial: etapa0 }, function(e) {
                        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                            var href = $(e.target).attr('href');
                            var $curr = $(".process-model  a[href='" + href + "']").parent();
                            $('.process-model li').removeClass();
                            $curr.addClass("active");
                            $curr.prevAll().addClass("visited");
                            // console.log(("#tabMD"+etapa_en_curso));
                            $('#btn_add_inductor').unbind('click');
                            $('.verAnexo568').unbind('click');
                            $('.enviarAnexo').unbind('click');

                            $('#btn_add_inductor').click(function(e) {
                                e.preventDefault();
                                showLoadingImage();
                                var params = {
                                    id_func: $('#idFuncionarioActivity').val(),
                                    rut: $('#inp_inductor').val(),
                                    nombreFunc: $('#mdl_tittle_nom').text()
                                }
                                $.post('controller/add_agente_inductor.php', params, function(resp) {
                                    if (resp) {
                                        hideLoadingImage();
                                        Swal.fire({
                                            position: 'top-end',
                                            icon: 'success',
                                            title: 'El angente inductor se agregó exitosamente!',
                                            showConfirmButton: false,
                                            timer: 1500
                                        });
                                    }
                                });
                            });

                            $('.verAnexo568').each(function(x) {
                                $(this).click(function(e) {
                                    const anexo = $(this).attr('id').split('-');
                                    const idFun = $("#idFuncEP").val();
                                    $("#capaidAnexo").val(id_anexo = anexo[1]);
                                    $("#idfuncAnexo").val(idFun);
                                    $('#formSendAnexos2').submit();
                                });
                            });

                            $('.enviarAnexo').each(function(x) {
                                $(this).click(function(e) {
                                    showLoadingImage();
                                    const anexo = $(this).attr('id').split('-');
                                    const idFun = $("#idFuncEP").val();
                                    var params = {
                                        'id': idFun,
                                        'anexo': anexo[1],
                                        'rut': $("#rutFuncEP").val()
                                    }
                                    $.post('Controller/Correo_anexo.php', params, function(response) {
                                        hideLoadingImage();
                                        // console.log(response);
                                        if (response) {
                                            Swal.fire({
                                                position: 'top-end',
                                                icon: 'success',
                                                title: 'Se envió un correo al funcionario para realizar el Anexo!',
                                                showConfirmButton: false,
                                                timer: 2500
                                            });
                                        // }else if(response == 'sf'){
                                        //     Swal.fire(
                                        //         'Error!',
                                        //         'No se envio el correo, el rut ingresado no coincide con el funcionario.',
                                        //         'error'
                                        //     );
                                        }else {
                                            Swal.fire(
                                                'Error!',
                                                'No se envio el correo porfavor intente denuevo.',
                                                'error'
                                            );
                                        }
                                    });
                                });
                            });
                        });
                        var etapa_en_curso = $(".card.pointer:last").children('.id-etapa').val() + "" + depto_comiento;
                        $("#tabMD" + etapa_en_curso).tab("show", function(e) {});
                    });
                });
                // end  script for tab steps
                $(".card.pointer:last").trigger("click");
                $('#staticBackdrop').modal('show');
            });
        }
    }, 'json');
}

function Set_Modal_busqueda(fun,id_hr) {
    $('#mdl_tittle_nom').text('');
    $('#idFuncionarioActivity').val(fun);

    $('#mdl_tittle_hr').text(id_hr);
    var params = {
        'id_func': fun,
        'id_HR': id_hr
    }
    $("#modad_body_dt").empty();
    $.post('controller/detalle_funcionario.php', params, function(response) {
        let nombreFuncionarioEnProceso = response['funcionario'][0]['nombre'] + ' ' + response['funcionario'][0]['apellido'] + ' ' + response['funcionario'][0]['apellido_2'];
        $('#mdl_tittle_nom').text(nombreFuncionarioEnProceso);
        if (response != null && response != "") {
            var max_soc = 0;
            var var_soc = 0;
            var max_entre = 0;
            var var_entre = 0;
            var max_fide = 0;
            var var_fide = 0;
            var max_sup = 0;
            var var_sup = 0;
            var max_eva = 0;
            var var_eva = 0;
            var porcentaje = {};
            let lastActivity = response['actividades'][response['actividades'].length-1]['Depto_ejecutante_id'];
            $.each(response['actividades'], function(index, value) {
                if (typeof porcentaje[value.Etapa_id] == "undefined") {
                    porcentaje[value.Etapa_id] = {
                        max: 0,
                        var: 0
                    }
                }
                porcentaje[value.Etapa_id].max++;
                if (value.respuesta != null) {
                    porcentaje[value.Etapa_id].var++;
                }
            });
                console.log(lastActivity);
            var porcents = {};
            for (var indx in porcentaje) {
                porcents[indx] = cal_porcentaje(porcentaje[indx].max, porcentaje[indx].var);
            }
            response['porcent'] = porcents;
            var depto_comiento = 5;

            $("#modad_body_dt").load('Views/body_modal.php', response, function(e) {
                // script for tab steps
                $('.order-card').click(function(e) {
                    $('.bg-c-gray.order-card').unbind("click");
                    var etapa = $(this).children('.id-etapa').val();
                    var etapa0 = $('#idetapa0').val();
                    $('#process-tab').empty();
                    $("#process-tab").load('Views/view_activity_2.php', { response, num: etapa, etapainicial: etapa0 }, function(e) {
                        $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                            var href = $(e.target).attr('href');
                            var $curr = $(".process-model  a[href='" + href + "']").parent();
                            $('.process-model li').removeClass();
                            $curr.addClass("active");
                            $curr.prevAll().addClass("visited");
                            // console.log(("#tabMD"+etapa_en_curso));
                            $('#btn_add_inductor').unbind('click');
                            $('.verAnexo568').unbind('click');
                            $('.enviarAnexo').unbind('click');

                            // $('#btn_add_inductor').click(function(e) {
                            //     e.preventDefault();
                            //     showLoadingImage();
                            //     var params = {
                            //         id_func: $('#idFuncionarioActivity').val(),
                            //         rut: $('#inp_inductor').val(),
                            //         nombreFunc: $('#mdl_tittle_nom').text()
                            //     }
                            //     $.post('controller/add_agente_inductor.php', params, function(resp) {
                            //         if (resp) {
                            //             hideLoadingImage();
                            //             Swal.fire({
                            //                 position: 'top-end',
                            //                 icon: 'success',
                            //                 title: 'El angente inductor se agregó exitosamente!',
                            //                 showConfirmButton: false,
                            //                 timer: 1500
                            //             });
                            //         }
                            //     });
                            // });

                            $('.verAnexo568').each(function(x) {
                                $(this).click(function(e) {
                                    const anexo = $(this).attr('id').split('-');
                                    const idFun = $("#idFuncEP").val();
                                    $("#capaidAnexo").val(id_anexo = anexo[1]);
                                    $("#idfuncAnexo").val(idFun);
                                    $('#formSendAnexos2').submit();
                                });
                            });

                            // $('.enviarAnexo').each(function(x) {
                            //     $(this).click(function(e) {
                            //         showLoadingImage();
                            //         const anexo = $(this).attr('id').split('-');
                            //         const idFun = $("#idFuncEP").val();
                            //         var params = {
                            //             'id': idFun,
                            //             'anexo': anexo[1],
                            //             'rut': $("#rutFuncEP").val()
                            //         }
                            //         $.post('Controller/Correo_anexo.php', params, function(response) {
                            //             hideLoadingImage();
                            //             if (response) {
                            //                 Swal.fire({
                            //                     position: 'top-end',
                            //                     icon: 'success',
                            //                     title: 'Se envió un correo al funcionario para realizar el Anexo!',
                            //                     showConfirmButton: false,
                            //                     timer: 2500
                            //                 });
                            //             } else {
                            //                 Swal.fire(
                            //                     'Error!',
                            //                     'No se envio el correo porfavor intente denuevo.',
                            //                     'error'
                            //                 );
                            //             }
                            //         });
                            //     });
                            // });
                        });
                        var etapa_en_curso = $(".card.pointer:last").children('.id-etapa').val() + "" + depto_comiento;
                        $("#tabMD" + etapa_en_curso).tab("show", function(e) {});
                    });
                });
                // end  script for tab steps
                $(".card.pointer:last").trigger("click");
                $('#staticBackdrop').modal('show');
            });
        }
    }, 'json');
}

/** Funcion para ejecutar el loader**/
function showLoadingImage() {
    $('#wrapper').append('<div id="loading-image"></div>');
    $("#loading-image").animate({
        height: '100%',
        opacity: '0.9',
        width: '100%'
    }, 800);
}

/** Funcion para quitar el loader**/
function hideLoadingImage() {
    $("#loading-image").animate({
        height: '0%',
        opacity: '0.9',
        width: '0%'
    }, 1200);
}

function cal_porcentaje(n_max, valor) {
    var porcentaje = Math.round((valor * 100) / n_max);
    return (isNaN(porcentaje)) ? 0 : porcentaje;
}

function activaTab(tab) {
    $('.nav-tabs a[href="#' + tab + '"]').tab('show');
};

$('#formSendAnexos2').submit(function(e) {
    console.log(e);
});

function sendAnexos() {
    console.log($('#formSendAnexos2'));

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
