<?php
require_once("../Controller/buscarAnexo.php");
// highlight_string(print_r($preg,true));
$chk = '';

?>
<div class="row">
    <div class="col-md-5 d-inline-flex">
        <img src="../Assets/img/logo.jpg" style="width: 15%;" alt="">
        <p class="align-middle ps-3 pe-4 my-auto text-justify">
            Servicio de Salud Arica Subdirección de Recursos Humanos Subdepto. de Calidad de Vida Laboral SECCIÓN DESARROLLO  DE COMPETENCIAS Y EDUCACIÓN CONTINUA.
        </p>
    </div>
    <div class="col-md-7" style="align-self: center;">
        <h5 class="ps-5 text-center">PROGRAMA DE INDUCCIÓN DEL PERSONAL DE LA DIRECCIÓN DEL SERVICIO DE SALUD ARICA.</h5>
    </div>
</div>
<hr>
<div class="row py-5">
    <div class="col-12">
        <div class="text-center">
            <h3>ANEXO 12</h3>
            <h4>ENCUESTA DE EVALUACIÓN DE SATISFACCIÓN DEL PROCESO DE INDUCCIÓN.</h4>
        </div>
    </div>
</div>
<div class="row m-5">
    <p>El objetivo de esta encuesta es conocer el grado de satisfacción con el proceso de inducción recibido al ingresar al Servicio de Salud Arica. Se solicita responder solo las actividades en las que usted haya sido partícipe, marcando la alternativa que lo interprete mejor. Es importante que considere que esta encuesta no constituye una evaluación, que los datos serán tratados bajo confidencialidad, que no condiciona su prórroga de contrato y solo se busca obtener información para mejorar este proceso</p>
    <table class="table table-striped my-5 text-center">
            <thead class="bg-primary">
                <tr class="text-white">
                    <th>En total desacuerdo</th>
                    <th>En desacuerdo</th>
                    <th>Neutral</th>
                    <th>De acuerdo</th>
                    <th>En total acuerdo</th>
                    <th>Ninguna de las anteriores</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>N/A</td>
                </tr>
            </tbody>
        </table>
</div>
<div class="row text-center m-5">
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
    <div class="col-12">
        <h2 class="text-center">EVALUACIÓN</h2>
        <form action="../Controller/guardar_anexo.php" method="post" id="formAnexo">
            <input type="hidden" name="id_func" value="<?=$idF;?>">
            <input type="hidden" name="id_anexo" value="<?=$anexo;?>">
            <input type="hidden" name="tipo" value="<?=$tipo;?>">
            <input type="hidden" id="firmante" name="firmante" value="">
            <input type="hidden" id="rut_func" name="rut_func" value="<?=$datosFunc[0]['rut'];?>">
            <input type="hidden" id="nombreFunc" name="nombreFunc" value="<?=$datosFunc[0]['funcionario'];?>">
            <input type="hidden" id="id_anexoRR" value="<?=$id_realizado['id'];?>">
            <div class="row my-5">
            <?php for ($i=0; $i < count($preg); $i++) { ?>
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary">
                        <tr class="text-white text-center">
                            <th style="width: 80%;text-align: left;"><?=$preg[$i]['nombre'];?></th>
                            <th>1</th>
                            <th>2</th>
                            <th>3</th>
                            <th>4</th>
                            <th>5</th>
                            <th>N/A</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $preguntas = $preg[$i]['preguntas'];
                        for ($x=0; $x < count($preguntas); $x++) { ?>
                        <tr>
                            <td class="respEnc"><?=$preguntas[$x]['pregunta'];?></td>
                        <?php if ($preguntas[$x]['id'] != 37) { ?>
                            <td><input style="cursor: pointer;" type="radio"  name="options-<?=$preguntas[$x]['id'];?>" value="1"
                                <?php 
                                    if ($preguntas[$x]['Anexo_respuesta']  == 1) {
                                        echo "checked";
                                    }else{
                                        if($preguntas[$x]['Anexo_respuesta'] != ""){
                                            echo "disabled";
                                        }else{
                                            if ($tipo != 1) {
                                                echo "disabled";                                                
                                            }
                                        } 
                                    }
                                ?>
                                ></td>
                            <td><input style="cursor: pointer;" type="radio"  name="options-<?=$preguntas[$x]['id'];?>" value="2"
                                <?php 
                                    if ($preguntas[$x]['Anexo_respuesta']  == 2) {
                                        echo "checked";
                                    }else{
                                        if($preguntas[$x]['Anexo_respuesta'] != ""){
                                            echo "disabled";
                                        }else{
                                            if ($tipo != 1) {
                                                echo "disabled";                                                
                                            }
                                        } 
                                    }
                                ?>
                                ></td>
                            <td><input style="cursor: pointer;" type="radio"  name="options-<?=$preguntas[$x]['id'];?>" value="3"
                                <?php 
                                    if ($preguntas[$x]['Anexo_respuesta']  == 3) {
                                        echo "checked";
                                    }else{
                                        if($preguntas[$x]['Anexo_respuesta'] != ""){
                                            echo "disabled";
                                        }else{
                                            if ($tipo != 1) {
                                                echo "disabled";                                                
                                            }
                                        } 
                                    }
                                ?>
                                ></td>
                            <td><input style="cursor: pointer;" type="radio"  name="options-<?=$preguntas[$x]['id'];?>" value="4"
                                <?php 
                                    if ($preguntas[$x]['Anexo_respuesta']  == 4) {
                                        echo "checked";
                                    }else{
                                        if($preguntas[$x]['Anexo_respuesta'] != ""){
                                            echo "disabled";
                                        }else{
                                            if ($tipo != 1) {
                                                echo "disabled";                                                
                                            }
                                        } 
                                    }
                                ?>
                                ></td>
                            <td><input style="cursor: pointer;" type="radio"  name="options-<?=$preguntas[$x]['id'];?>" value="5"
                                <?php 
                                    if ($preguntas[$x]['Anexo_respuesta']  == 5) {
                                        echo "checked";
                                    }else{
                                        if($preguntas[$x]['Anexo_respuesta'] != ""){
                                            echo "disabled";
                                        }else{
                                            if ($tipo != 1) {
                                                echo "disabled";                                                
                                            }
                                        }                                        
                                    }
                                ?>
                                ></td>
                            <td><input style="cursor: pointer;" type="radio"  name="options-<?=$preguntas[$x]['id'];?>" value="0"
                                <?php 
                                    if ($preguntas[$x]['Anexo_respuesta']  === '0') {
                                        echo "checked";
                                    }else{
                                        if($preguntas[$x]['Anexo_respuesta'] != ""){
                                            echo "disabled";
                                        }else{
                                            if ($tipo != 1) {
                                                echo "disabled";                                                
                                            }
                                        } 
                                    }
                                ?>
                                ></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>

                    </tbody>
                </table>
            <?php } 
            if($preg[4]['preguntas'][1]['Anexo_obs'] != ''){
            ?>
                <div class="col-12">
                    <textarea class="form-control" disabled rows="10"><?=$preg[4]['preguntas'][1]['Anexo_obs'];?></textarea>
                </div>
            <?php 
            }else{
            ?>
            <div class="col-12">
                <textarea name="obs-37" class="form-control" rows="10"></textarea>
            </div>
                <div class="d-none">
                <input style="cursor: pointer;" type="checkbox" checked name="options-37" value="1">
                </div>
            <?php } ?>
            </div>
            <hr class="mt-5">
            <div class="row my-5">
                <div class="d-flex justify-content-center">
                    <?php 
                    if ($id_realizado != '') { 
                            $hay_f_jefe = 0;
                            for ($i=0; $i < count($firmas); $i++) { 
                    ?>
                            <div class="py-4 px-5 text-center">
                                <img src="<?=$firmas[$i]['qr'];?>"/>
                                <h5 class="pt-4">
                                <?php  
                                    if($firmas[$i]['tipo'] == 1){
                                        echo "Firma Funcionario";
                                    }else{
                                        echo "Firma Encargad@";
                                        $hay_f_jefe = 1;
                                    }
                                ?>
                                </h5>
                            </div>
                    <?php   } 
                            if ($hay_f_jefe == 0 && $tipo==2) { 
                    ?>
                                <div class="px-5 align-self-center">
                                    <a class="btn btn-primary btn-lg" id="firmarJefe">Firmar Anexo</a>
                                </div>
                    <?php   } 
                    }else{ ?>
                        <div class="px-5 align-self-center">
                            <button type="button" id="btnTerminar" <?php if ($tipo != 1) { echo "disabled"; } ?> class="btn btn-primary btn-lg">Terminar Anexo</button>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </form>
    </div>
</div>
