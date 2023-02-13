<?php
require_once("../Controller/funcionariosPendientes.php");
// highlight_string(print_r($f,true));
?>
<div class="mb-4">
    <span class="text-uppercase page-subtitle">Hojas de ruta</span>
    <h3 class="page-title">Mis Pendientes</h3>
</div>
<div class="bd-example my-md-5">
    <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless text-center" id="TablaPendientes">
            <thead class="table-primary text-white">
                <tr>
                    <th>Run</th>
                    <th>Nombre</th>
                    <th>F. de Ingreso</th>
                    <th>Unidad</th>
                    <th>Fono</th>
                    <th>Correo</th>
                    <th>Estamento</th>
                    <th>Calidad Jurídica</th>
                    <th>N° Hoja de Ruta</th>
                    <th>Jefatura</th>
                    <th>Avance Total</th>
                    <?php if($f[0]['depto'] == 2){ ?>
                    <th></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody style="cursor:pointer;font-size: 12px;">
                <?php 
                    for ($i=0; $i < count($f); $i++) {
                        if ($f[$i]['es_jefe'] == "") {$f[$i]['es_jefe'] = 0;}
                        if ($f[$i]['es_inductor'] == "") {$f[$i]['es_inductor'] = 0;}
                        if ($f[$i]['depto'] == "") {$f[$i]['depto'] = 0;}
                 ?>
                    <tr>
                        <td onclick="Set_Modal(<?=$f[$i]['id'];?>,<?=$f[$i]['Hoja_ruta_id'];?>,<?=$f[$i]['depto'];?>,<?=$f[$i]['es_jefe'];?>,<?=$f[$i]['es_inductor'];?>);"><?=$f[$i]['rut'];?></td>
                        <td onclick="Set_Modal(<?=$f[$i]['id'];?>,<?=$f[$i]['Hoja_ruta_id'];?>,<?=$f[$i]['depto'];?>,<?=$f[$i]['es_jefe'];?>,<?=$f[$i]['es_inductor'];?>);" class="text-uppercase"><?=$f[$i]['funcionario'];?></td>
                        <td onclick="Set_Modal(<?=$f[$i]['id'];?>,<?=$f[$i]['Hoja_ruta_id'];?>,<?=$f[$i]['depto'];?>,<?=$f[$i]['es_jefe'];?>,<?=$f[$i]['es_inductor'];?>);" class="text-uppercase"><?=$f[$i]['fecha_ingreso'];?></td>
                        <td onclick="Set_Modal(<?=$f[$i]['id'];?>,<?=$f[$i]['Hoja_ruta_id'];?>,<?=$f[$i]['depto'];?>,<?=$f[$i]['es_jefe'];?>,<?=$f[$i]['es_inductor'];?>);" class="text-uppercase"><?=$f[$i]['unidad'];?></td>
        <td onclick="Set_Modal(<?=$f[$i]['id'];?>,<?=$f[$i]['Hoja_ruta_id'];?>,<?=$f[$i]['depto'];?>,<?=$f[$i]['es_jefe'];?>,<?=$f[$i]['es_inductor'];?>);" class="text-uppercase"><?=$f[$i]['fono'];?></td>
        <td onclick="Set_Modal(<?=$f[$i]['id'];?>,<?=$f[$i]['Hoja_ruta_id'];?>,<?=$f[$i]['depto'];?>,<?=$f[$i]['es_jefe'];?>,<?=$f[$i]['es_inductor'];?>);" class="text-uppercase"><?=$f[$i]['per_email'];?></td>
                        <td onclick="Set_Modal(<?=$f[$i]['id'];?>,<?=$f[$i]['Hoja_ruta_id'];?>,<?=$f[$i]['depto'];?>,<?=$f[$i]['es_jefe'];?>,<?=$f[$i]['es_inductor'];?>);" class="text-uppercase"><?=$f[$i]['estamento'];?></td>
                        <td onclick="Set_Modal(<?=$f[$i]['id'];?>,<?=$f[$i]['Hoja_ruta_id'];?>,<?=$f[$i]['depto'];?>,<?=$f[$i]['es_jefe'];?>,<?=$f[$i]['es_inductor'];?>);" class="text-uppercase"><?=$f[$i]['c_juridica'];?></td>
                        <td onclick="Set_Modal(<?=$f[$i]['id'];?>,<?=$f[$i]['Hoja_ruta_id'];?>,<?=$f[$i]['depto'];?>,<?=$f[$i]['es_jefe'];?>,<?=$f[$i]['es_inductor'];?>);"><?=$f[$i]['Hoja_ruta_id'];?></td>
                        <td onclick="Set_Modal(<?=$f[$i]['id'];?>,<?=$f[$i]['Hoja_ruta_id'];?>,<?=$f[$i]['depto'];?>,<?=$f[$i]['es_jefe'];?>,<?=$f[$i]['es_inductor'];?>);" class="text-uppercase"><?=$f[$i]['jefetura'];?></td>
                        <td onclick="Set_Modal(<?=$f[$i]['id'];?>,<?=$f[$i]['Hoja_ruta_id'];?>,<?=$f[$i]['depto'];?>,<?=$f[$i]['es_jefe'];?>,<?=$f[$i]['es_inductor'];?>);"><?=$f[$i]['porcentaje'];?> %</td>
                        <?php if($f[$i]['depto'] == 2){ ?>
                        <td>
                            <button type="button" onclick="cerrarProceso(<?=$f[$i]['id'];?>);" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Archivar Proceso"><i class="bi bi-archive-fill"></i></button>                            
                        </td>
                        <?php } ?>
                    </tr>
                <?php 
                    }
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title m-auto ps-5 text-uppercase" id="staticBackdropLabel">Funcionario: <strong id="mdl_tittle_nom"></strong> - Hoja de Ruta N°:  <strong id="mdl_tittle_hr">2</strong></h5>
                <?php
                    if ($depto_id == 40) { 
                ?>
                    <a href="#" id="btn_send_email_jefe" class="btn btn-sm btn-info"><i class="bi bi-envelope-fill"></i> Dar aviso a jefatura</a>
                <?php
                    }
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="post" id="form-upData">
                <input type="hidden" id="idFuncionarioActivity" name="funcionario">
                <div class="modal-body" id="modad_body_dt">
                </div>
                <div class="modal-footer">
                    <button type="button" id="btnGuardarAvance" class="btn btn-primary">Guardar Cambios</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
            <form id="formSendAnexos2"  action="Views/anexo.php" method="post" target="_blank">
                <input type="hidden" id="idfuncAnexo" name="id" />
                <input type="hidden" id="capaidAnexo" name="anexo"/>
                <input type="hidden" name="tipo" value="2"/>
            </form>
        </div>
    </div>
</div>
