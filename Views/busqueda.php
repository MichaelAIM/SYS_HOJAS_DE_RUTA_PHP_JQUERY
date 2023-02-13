<?php
require_once("../Controller/funcionariosArchivados.php");
?>
<div class="mb-4">
    <span class="text-uppercase page-subtitle">Hojas de ruta</span>
    <h3 class="page-title">Buscar Funcionario</h3>
</div>
<div class="bd-example my-md-5">
    <div class="table-responsive">
        <table class="table table-striped table-hover table-borderless text-center" id="TablaFinalizadas">
            <thead class="table-primary text-white">
                <tr>
                    <th>Run</th>
                    <th>Nombre</th>
                    <th>F. de Ingreso</th>
                    <th>Unidad</th>
                    <th>Estamento</th>
                    <th>Calidad Jurídica</th>
                    <th>N° Hoja de Ruta</th>
                    <th>Jefatura</th>
                    <th>Avance Total</th>
                </tr>
            </thead>
            <tbody style="cursor:pointer;font-size: 12px;">
                <?php 
                    for ($i=0; $i < count($f); $i++) {
                        if ($f[$i]['es_jefe'] == "") {$f[$i]['es_jefe'] = 0;}
                        if ($f[$i]['es_inductor'] == "") {$f[$i]['es_inductor'] = 0;}
                        if ($f[$i]['depto'] == "") {$f[$i]['depto'] = 0;}
                 ?>
                    <tr onclick="Set_Modal_busqueda(<?=$f[$i]['id'];?>,<?=$f[$i]['Hoja_ruta_id'];?>);">
                        <td><?=$f[$i]['rut'];?></td>
                        <td class="text-uppercase"><?=$f[$i]['funcionario'];?></td>
                        <td class="text-uppercase"><?=$f[$i]['fecha_ingreso'];?></td>
                        <td class="text-uppercase"><?=$f[$i]['unidad'];?></td>
                        <td class="text-uppercase"><?=$f[$i]['estamento'];?></td>
                        <td class="text-uppercase"><?=$f[$i]['c_juridica'];?></td>
                        <td><?=$f[$i]['Hoja_ruta_id'];?></td>
                        <td class="text-uppercase"><?=$f[$i]['jefetura'];?></td>
                        <td><?=$f[$i]['porcentaje'];?> %</td>
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
