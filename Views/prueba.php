            <div class="tab-content">
                <?php 
                    for ($i=0; $i < count($dep); $i++) {
                ?>
                    <div role="tabpanel" class="tab-pane" id="tabMD<?=$dep[$i]['Depto_ejecutante_id'];?>">
                        <div class="design-process-content">
                            <?php
                                for ($x=0; $x < count($amb); $x++) { 
                                    if ($dep[$i]['Depto_ejecutante_id'] == $amb[$x]['Depto_ejecutante_id'] && $amb[$x]['Etapa_id'] == $_POST['num']) { ?>
                                        <h3 class="semi-bold mt-3"><?=$amb[$x]['nombre'];?></h3>
                            <?php 
                                        $ind = 1;
                                        // echo " contador = ".count($activity);
                                        for ($y=0; $y < count($activity); $y++) {
                                            if ($activity[$y]['Ambito_id'] == $amb[$x]['Ambito_id'] && $activity[$y]['Depto_ejecutante_id'] == $amb[$x]['Depto_ejecutante_id']) {
                                                // $chkSI = "";
                                                // $chkNO = "";
                                                // $is_chk = "";
                                                $sm = "";
                                                $dm = "";

                                                if ($activity[$y]['Depto_ejecutante_id'] == $_POST['response']['depto'] || $activity[$y]['Depto_ejecutante_id'] == $_POST['response']['es_jefe'] || $activity[$y]['Depto_ejecutante_id'] == $_POST['response']['es_inductor']) {
                                                    if($activity[$y]['respuesta'] != ""){
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
                                                // echo $activity[$y]['Depto_ejecutante_id']." = ".$_POST['response']['depto']."<br>";
                                                // echo $activity[$y]['Depto_ejecutante_id']." = ".$_POST['response']['es_jefe']."<br>";
                                                // echo $activity[$y]['Depto_ejecutante_id']." = ".$_POST['response']['es_inductor']."<br>";

                                                if ($activity[$y]['respuesta'] == 1) {
                                                    $sm = "bsuccess";
                                                }else if ($activity[$y]['respuesta'] == 2){
                                                    $dm = "bdanger";
                                                }
                            ?>
                                            <div class="row mt-2">
                                                <div class="col-md-6" style="align-self: center;">
                                                    <p class="mb-0"><strong><?=$ind;?>. </strong> <?=$activity[$y]['actividad_especifica'];?></p>
                                                    <?php if ($activity[$y]['anexo']!= "") {
                                                    ?>
                                                        <button class="btn btn-primary btn-sm ms-3">ver <?=$activity[$y]['nom_anexo'];?></button>
                                                    <?php
                                                    } ?>
                                                </div>
                                                <div class="col-md-2 text-center" style="align-self: center;">
                                                    <input type="radio" class="btn-check" <?=$chkSI;?> name="options-<?=$activity[$y]['Actividad_id'];?>" id="success<?=$activity[$y]['Actividad_id'];?>" autocomplete="off" value="on">
                                                    <label class="btn btn-outline-success  <?=$sm;?>" for="success<?=$activity[$y]['Actividad_id'];?>">SI</label>
                                                    <input type="radio" class="btn-check" <?=$chkNO;?> name="options-<?=$activity[$y]['Actividad_id'];?>" id="danger<?=$activity[$y]['Actividad_id'];?>" autocomplete="off" value="off">
                                                    <label class="btn btn-outline-danger <?=$dm;?>" for="danger<?=$activity[$y]['Actividad_id'];?>">NO</label>
                                                </div>
                                                <div class="col-md-4" style="align-self: center;">
                                                    <textarea name="obs_<?=$activity[$y]['Actividad_id'];?>" class="form-control" placeholder="Observación..." id="obs_<?=$activity[$y]['Actividad_id'];?>" rows="2" <?=$is_chk;?> > <?=$activity[$y]['observacion'];?> </textarea>
                                                </div>
                                            </div>
                            <?php
                                        $ind++;
                                            }
                                        }
                                    }
                                }
                            ?>
                        </div>
                    </div>
                <?php
                    }
                ?>
            </div>
