<?php 
// highlight_string(print_r($_POST,true));
$func = $_POST['funcionario'];
$color = 'bg-c-purple pointer';
// if ($func[0]['id'] != 1) {
    // $_POST['porcent'][1] = 100;
// }
?>
<section>
    <div class="row justify-content-md-center text-center">
        <?php 
        // for ($i=1; $i <= count($_POST['etapas']); $i++) {
            $colorear = true;
            foreach ($_POST['etapas'] as $index => $value) {
                // echo " = ".$index;
                $id_etapa = $value['id'];
                // $anterior = $id_etapa - 1;
                switch ($index) {
                    case 1:
                    $color = 'bg-c-green pointer';
                    break;
                    case 2:
                    $color = 'bg-c-blue pointer';
                    break;
                    case 3:
                    $color = 'bg-c-yellow pointer';
                    break;
                    case 4:
                    $color = 'bg-c-pink pointer';
                    break;
                }
                // if ($i > 0 && $_POST['porcent'][$anterior] < 100 && $func[0]['id'] != 1) {
                if ($colorear == false) {
                    $color = 'bg-c-gray';
                    $value['id'] = 0;
                }
            ?>
            <div class="col-md-auto">
                <div class="card <?=$color;?> order-card">
                    <input type="hidden" value="<?=$value['id'];?>"class="id-etapa">
                    <div class="card-block">
                        <h5 class="mb-02"><?=$_POST['porcent'][$id_etapa];?>%</h5>
                        <p class="mb-0 px-45 px-4 text-uppercase"><?=$value['nombre'];?></p>
                    </div>
                </div>
            </div>
        <?php 
                if ($_POST['porcent'][$id_etapa] == 100) {
                    $colorear = TRUE;
                }else{
                    $colorear = false;
                }
            } ?>
        <input type="hidden" value="<?=$_POST['etapas'][0]['id'];?>"id="idetapa0">
    </div>
</section>

<section class="design-process-section" id="process-tab"></section>
