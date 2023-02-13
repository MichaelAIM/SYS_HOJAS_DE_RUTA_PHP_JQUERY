 <?php 
require_once("../Controller/buscarAnexo.php");
// highlight_string(print_r($datosFunc,true));
?>
<div class="row">
    <div class="col-md-5 d-inline-flex">
        <img src="../Assets/img/logo.jpg" style="width: 15%;" alt="">
        <p class="align-middle ps-3 pe-4 my-auto text-justify">
            Servicio de Salud Arica Subdirección de Recursos Humanos Subdepto. de Calidad de Vida Laboral Sección de Desarrollo Organizacional.
        </p>
    </div>
    <div class="col-md-7" style="align-self: center;">
        <h5 class="ps-5 text-center">PROCEDIMIENTO DE INDUCCIÓN DIRECCIÓN DEL SERVICIO DE SALUD ARICA</h5>
    </div>
</div>
<hr>
<div class="row py-5">
    <div class="col-12">
        <div class="text-center">
            <h3>ANEXO 11</h3>
            <h4>PAUTA DE ENTREVISTA PARA EL PERSONAL INDUCIDO DEL SERVICIO DE SALUD ARICA</h4>
        </div>
    </div>
</div>
<div class="row text-center my-5">
    <div class="col-md-4 text-uppercase">Funcionario: <strong><?=$datosFunc[0]['funcionario'];?></strong></div>
    <div class="col-md-4 text-uppercase">Jefatura: <strong><?=$datosFunc[0]['jefe'];?></strong></div>
    <div class="col-md-4">Fecha: <strong>
        <?php 
        if($id_realizado['fecha'] != ''){
            echo date("d/m/Y",strtotime($id_realizado['fecha']));
        }else{
            echo date('d/m/Y');
        }?>
            
        </strong></div>
</div>
<div class="row m-5 p-4 rounded-3" style="background-color: #e0e0e0;">
    <form action="../Controller/guardar_anexo.php" method="post" id="formAnexo">
        <input type="hidden" name="id_func" value="<?=$idF;?>">
        <input type="hidden" name="id_anexo" value="<?=$anexo;?>">
        <input type="hidden" name="tipo" value="<?=$tipo;?>">
        <input type="hidden" id="firmante" name="firmante" value="">
        <input type="hidden" id="rut_func" name="rut_func" value="<?=$datosFunc[0]['rut'];?>">
        <input type="hidden" id="nombreFunc" name="nombreFunc" value="<?=$datosFunc[0]['funcionario'];?>">
        <input type="hidden" id="id_anexoRR" value="<?=$id_realizado['id'];?>">
        <div class="col-sm-2 offset-sm-6 text-center"><strong>Necesidad de reforzamiento <br> (Sí/No)</strong></div>
        <?php for ($i=0; $i < count($preg); $i++) { ?>
            <h5 class="my-4"><?=$preg[$i]["nombre"];?></h5>
                <?php 
                    for ($x=0; $x < count($preg[$i]["preguntas"]); $x++) { 
                        $in = $x+1;
                        $sm = '';
                        $dm = '';
                        if ($preg[$i]['preguntas'][$x]['Anexo_respuesta'] != "") {
                            if ($preg[$i]['preguntas'][$x]['Anexo_respuesta'] == 1) {
                                $sm = "bsuccess";
                                $chkS = "";
                            }else if ($preg[$i]['preguntas'][$x]['Anexo_respuesta'] == 2){
                                $dm = "bdanger";
                            }
                        }
                ?>
                        <div class="row my-4">
                            <div class="col-6">
                                <p><?=$in.". ".$preg[$i]["preguntas"][$x]["pregunta"];?></p>
                            </div>
                            <div class="col-2 text-center">
                                <input type="radio" class="btn-check" <?if ($sm=='bsuccess') { echo "checked='checked' disabled='disabled'"; };?> <?if ($tipo==1 || $dm=='bdanger') { echo "disabled='disabled'"; };?> name="options-<?=$preg[$i]["preguntas"][$x]["id"];?>" id="success<?=$preg[$i]["preguntas"][$x]["id"];?>" autocomplete="off" value="on">
                                <label class="btn btn-outline-success <?=$sm;?> contar" for="success<?=$preg[$i]["preguntas"][$x]["id"];?>">SI</label>
                                <input type="radio" class="btn-check" <?if ($dm=='bdanger') { echo "checked='checked' disabled='disabled'"; };?> <?if ($tipo==1 || $sm=='bsuccess') { echo "disabled='disabled'"; };?> name="options-<?=$preg[$i]["preguntas"][$x]["id"];?>" id="danger<?=$preg[$i]["preguntas"][$x]["id"];?>" autocomplete="off" value="off">
                                <label class="btn btn-outline-danger <?=$dm;?> contar" for="danger<?=$preg[$i]["preguntas"][$x]["id"];?>">NO</label>
                            </div>
                            <div class="col-4">
                                <textarea name="obs-<?=$preg[$i]["preguntas"][$x]["id"];?>" <?if ($tipo==1 || $preg[$i]['preguntas'][$x]['Anexo_obs']!= '') { echo "disabled='disabled'"; };?> class="form-control" placeholder="Observación..." id="obs-<?=$preg[$i]["preguntas"][$x]["id"];?>" rows="2"><?=$preg[$i]['preguntas'][$x]['Anexo_obs'];?></textarea>
                            </div>
                        </div>
        <?php       }
            }
        ?>
        <hr class="mt-5">
        <div class="row my-5">
            <div class="d-flex justify-content-center">
                <?php if ($id_realizado != '') { 
                        $hay_f_jefe = 0;
                        for ($i=0; $i < count($firmas); $i++) { 
                ?>
                            <div class="py-4 px-5 text-center">
                                <img src="<?=$firmas[$i]['qr'];?>"/>
                                <h5 class="pt-4">
                            <?php  
                                if($firmas[$i]['tipo'] == 1){
                                    echo "Firma Funcionario";
                                    $hay_f_jefe = 1;
                                }else{
                                    echo "Firma Jefatura";
                                }
                            ?>
                                </h5>
                            </div>
                <?php   }
                        if ($hay_f_jefe == 0 && $tipo == 1) { ?>
                            <div class="px-5 align-self-center">
                                <a class="btn btn-primary btn-lg" id="firmarJefe">Firmar Anexo</a>
                            </div>
                <?php   } 
                    }else{ 
                        if ($tipo == 2) {
            ?>
                    <div class="px-5 align-self-center">
                        <button type="button" id="btnTerminar" class="btn btn-primary btn-lg">Enviar Anexo</button>
                    </div>
            <?php 
                        }
                    } 
            ?>
            </div>
        </div>
    </form>
</div>
