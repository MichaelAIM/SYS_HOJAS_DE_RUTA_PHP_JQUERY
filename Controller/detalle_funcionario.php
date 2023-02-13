<?php 
    require_once("../Models/funcionario.php");
    require_once("../Models/deptos.php");
    require_once("../Models/etapa.php");
    require_once("../Models/ambito.php");

    $func = new Funcionario();
    $etapa = new Etapa();
    $depto = new Deptos();
    $ambito = new Ambito();

    $id_HR = $_POST['id_HR'];
    $id_func = $_POST['id_func'];
    // $id_HR = 2;
    // $id_func = 1;

    $func_deptos = $depto->Buscar_por_HR($id_HR);
    $func_ambito = $ambito->Buscar_por_HR($id_HR);
    $func_etapa = $etapa->Buscar_por_HR($id_HR);
    $func_activity = $func->Buscar_actividades($id_func,$id_HR);
    $inductor = $func->buscar_inductor($id_func);
    $func_data = $func->Buscar_funcionario($id_func);
    $funcs_depto = $func->agentes_Disponibles($func_data['func'][0]['Unidad_id']);
    // highlight_string(print_r($funcs_depto,true));
    $existeAgente = searcharray( $inductor[0]['rut'], 'per_rut', $funcs_depto);

    if ($existeAgente === 'false') {
        $arr = array(
            'per_rut' => $inductor[0]['rut'], 
            'per_nombre' => $inductor[0]['per_nombre']
        );
        array_push($funcs_depto, $arr);
    }
   
    $data = array(
        'agente_inductor' => $inductor[0]['rut'],
        'agentes' => $funcs_depto,
        'funcionario' => $func_data['func'],
        'etapas' => $func_etapa['etapas'],
        'deptos' => $func_deptos['deptos'],
        'ambitos' => $func_ambito['ambito'],
        'actividades' => $func_activity['activity']
    );

    // highlight_string(print_r($data,true));
    echo json_encode($data);

    function searcharray($value, $key, $array) {
        foreach ($array as $k => $val) {
            if ($val[$key] == $value) {
                return $val;
            }
        }
        return "false";
    }
?>
