<?php 
// highlight_string(print_r($_POST['response'],true));
$dep = $_POST['response']['deptos'];
$agentes_disponibles = $_POST['response']['agentes'];
$ambOLD = $_POST['response']['ambitos'];

$amb = array();
for($j=0; $j < count($ambOLD); $j++){
    $amb[$ambOLD[$j]['Ambito_id']] = $ambOLD[$j];
}

$activity = $_POST['response']['actividades'];

$todo =[];
for ($i=0; $i < count($activity); $i++) { 
    $todo[$activity[$i]['Depto_ejecutante_id']][$activity[$i]['Etapa_id']][$activity[$i]['Ambito_id']]["ambito_nombre"] = $amb[$activity[$i]['Ambito_id']]['nombre'];
    $todo[$activity[$i]['Depto_ejecutante_id']][$activity[$i]['Etapa_id']][$activity[$i]['Ambito_id']]['actividades'][] = $activity[$i];

}
ksort($todo);
foreach($todo as $idx => $value){
    ksort($todo[$idx]);
}
$func = $_POST['response']['funcionario'];
// highlight_string(print_r($activity,true));
$total_preguntas = count($activity);
$cont = 0;
for ($i=0; $i < count($dep); $i++) { 
    if ($dep[$i]['Etapa_id'] === $_POST['num']) {
        $cont++;
    }
}
?>
<input type="hidden" name="total_preguntas" value="<?=$total_preguntas;?>">
<input type="hidden" id="idFuncEP" value="<?=$func[0]['id'];?>">
<input type="hidden" id="rutFuncEP" value="<?=$func[0]['rut'];?>">
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <!-- design process steps-->

            <!-- Nav tabs -->
            <ul class="nav nav-tabs process-model more-icon-preocess porcent-<?=$cont;?>" role="tablist">
                <?php 
                    for ($i=0; $i < count($dep); $i++) { 
                        if ($dep[$i]['Etapa_id'] === $_POST['num']) {
                            $icono = "";
                            switch ($dep[$i]['Depto_ejecutante_id']) {
                                case 1:
                                $icono = 'bi-person-lines-fill';
                                break;
                                case 2:
                                $icono = 'bi-book';
                                break;
                                case 3:
                                $icono = 'bi-megaphone';
                                break;
                                case 4:
                                $icono = 'bi-person-bounding-box';
                                break;
                                case 5:
                                $icono = 'bi-list-check';
                                break;
                                case 6:
                                $icono = 'bi-award';
                                break;
                            }
                ?>
                            <li role="presentation" class="">
                                <a href="#tabMD<?=$dep[$i]['Depto_ejecutante_id'];?>" id="tabMD<?=$dep[$i]['Etapa_id'].''.$dep[$i]['Depto_ejecutante_id'];?>" class="text-decoration-none" aria-controls="tabMD<?=$dep[$i]['Depto_ejecutante_id'];?>" role="tab" data-bs-toggle="tab">
                                    <i class="<?=$icono;?>" aria-hidden="true"></i>
                                    <p><?=$dep[$i]['nombre'];?></p>
                                </a>
                            </li>
                <?php 
                        }
                    }
                ?>
            </ul>
            <!-- end design process steps-->
            <!-- Tab panes -->
            <div class="tab-content">
                <?php 
                    foreach ($todo as $key => $value) {
                ?>
                    <div role="tabpanel" class="tab-pane" id="tabMD<?=$key;?>">
                        <div class="design-process-content">
                            <?php 
                                $array = array_values($value[$_POST["num"]]);
                                for ($i=0; $i < count($array); $i++) { 
                            ?>
                                    <h3 class="semi-bold mt-3"><?=$array[$i]['ambito_nombre'];?></h3>
                            <?php 
                                    if ($_POST["num"] == $_POST["etapainicial"] && $key == 4 && $i ==0) {
                            ?>
                                        <div class="row my-5">
                                            <div class="col-md-5 text-center">
                                                <p class="mb-0 text-uppercase" style="align-self: center;"><strong>Agente Inductor.</strong></p>
                                            </div>
                                            <div class="col-md-7">
                                                <div class="input-group">
                                                    <select id="inp_inductor" name="inp_inductor" class="form-select" aria-describedby="btn_add_inductor" <? if ($_POST['response']['es_jefe'] != 4){ echo 'disabled="disabled"';}?>>
                                                        <option selected>Seleccionar...</option>
                                                      <?php for ($a=0; $a < count($agentes_disponibles); $a++) { 
                                                            if ($_POST['response']['agente_inductor'] != '') {
                                                      ?>
                                                                <option value="<?=$agentes_disponibles[$a]['per_rut'];?>" <? if ($_POST['response']['agente_inductor'] == $agentes_disponibles[$a]['per_rut']){ echo 'selected';}?> ><?=$agentes_disponibles[$a]['per_nombre'];?></option>
                                                      <?php
                                                            }else{
                                                      ?>
                                                                <option value="<?=$agentes_disponibles[$a]['per_rut'];?>"><?=$agentes_disponibles[$a]['per_nombre'];?></option>
                                                      <?php
                                                            }
                                                      } ?>
                                                    </select>
                                                    <?php if( $_POST['response']['es_jefe'] == 4 ){ ?>
                                                        <button class="btn btn-primary" type="button" id="btn_add_inductor">Agregar Inducctor</button>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </div>
                            <?php
                                    }
                            ?>
                            <?php
                                    for ($x=0; $x < count($array[$i]['actividades']); $x++) {
                                        $sm = "";
                                        $dm = "";
                                        if ($key == $_POST['response']['depto'] || $key == $_POST['response']['es_jefe'] || $key == $_POST['response']['es_inductor']) {
                                            if($array[$i]['actividades'][$x]['respuesta'] != ""){
                                                $is_chk = "disabled";
                                                $chkNO = "disabled";
                                                $chkSI = "disabled";
                                            }else{
                                                $chkSI = "";
                                                $chkNO = "";
                                                $is_chk = "";
                                            }
                                        }else{
                                            $is_chk = "disabled";
                                            $chkNO = "disabled";
                                            $chkSI = "disabled";
                                        }

                                        if ($array[$i]['actividades'][$x]['respuesta'] == 1) {
                                            $sm = "bsuccess";
                                        }else if ($array[$i]['actividades'][$x]['respuesta'] == 2){
                                            $dm = "bdanger";
                                        }
                            ?>
                                        <div class="row mt-2">
                                            <div class="col-md-6" style="align-self: center;">
                                                <p class="mb-0"><strong><?=$x+1;?>. </strong> <?=$array[$i]['actividades'][$x]['actividad_especifica'];?></p>
                                                <?php if ($array[$i]['actividades'][$x]['anexo']!= "") {
                                                ?>
                                                    <button type="button" id="verAnexo-<?=$array[$i]['actividades'][$x]['anexo'];?>"  class="btn btn-primary btn-sm ms-3 mt-2 verAnexo568">Ir a <?=$array[$i]['actividades'][$x]['nom_anexo'];?></button>
                                                    <?php if ($array[$i]['actividades'][$x]['anexo'] == 2) { ?>
                                                        <button type="button" id="enviarAnexo-<?=$array[$i]['actividades'][$x]['anexo'];?>"  class="btn btn-primary btn-sm ms-3 mt-2 enviarAnexo">Enviar el <?=$array[$i]['actividades'][$x]['nom_anexo'];?> al funcionario</button>
                                                    <?php } ?>
                                                <?php
                                                } ?>
                                            </div>
                                            <div class="col-md-2 text-center" style="align-self: center;">
                                                <input type="radio" class="btn-check" <?=$chkSI;?> name="options-<?=$array[$i]['actividades'][$x]['Actividad_id'];?>" id="success<?=$array[$i]['actividades'][$x]['Actividad_id'];?>" autocomplete="off" value="on">
                                                <label class="btn btn-outline-success  <?=$sm;?>" for="success<?=$array[$i]['actividades'][$x]['Actividad_id'];?>">SI</label>
                                                <input type="radio" class="btn-check" <?=$chkNO;?> name="options-<?=$array[$i]['actividades'][$x]['Actividad_id'];?>" id="danger<?=$array[$i]['actividades'][$x]['Actividad_id'];?>" autocomplete="off" value="off">
                                                <label class="btn btn-outline-danger <?=$dm;?>" for="danger<?=$array[$i]['actividades'][$x]['Actividad_id'];?>">NO</label>
                                            </div>
                                            <div class="col-md-4" style="align-self: center;">
                                                <textarea name="obs-<?=$array[$i]['actividades'][$x]['Actividad_id'];?>" class="form-control" placeholder="Observación..." id="obs-<?=$array[$i]['actividades'][$x]['Actividad_id'];?>" rows="2" <?=$is_chk;?>> <?=$array[$i]['actividades'][$x]['observacion'];?> </textarea>
                                            </div>
                                        </div>
                            <?php
                                    }
                                } 
                            ?>
                        </div>
                    </div>
                <?php
                    }
                ?>
            </div>
        </div>
    </div>
</div>                                            
