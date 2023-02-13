<?php
    require_once('conector.class.php');
    class Funcionario{

        public function __construct(){ 
        }

        public function Index($rut,$depto){
            $sql  = 'SELECT hoja_de_ruta.funcionario.id, hoja_de_ruta.funcionario.rut, CONCAT( hoja_de_ruta.funcionario.nombre, " ", hoja_de_ruta.funcionario.apellido, " ", hoja_de_ruta.funcionario.apellido_2 ) AS funcionario, hoja_de_ruta.funcionario.Hoja_ruta_id, hoja_de_ruta.funcionario.fecha_ingreso, hoja_de_ruta.estamento.nombre AS estamento, hoja_de_ruta.calidad_juridica.nombre AS c_juridica, ssalud.departamento.dep_nombre AS unidad, hoja_de_ruta.funcionario.estado, hoja_de_ruta.funcionario.porcentaje, hoja_de_ruta.funcionario.jefatura AS rut_jefatura, Jefe.per_nombre AS jefetura, hoja_de_ruta.funcionario.fono, if(Func.per_email <> "@saludarica.cl", Func.per_email, "SIN CORREO") AS per_email FROM hoja_de_ruta.funcionario INNER JOIN hoja_de_ruta.estamento ON hoja_de_ruta.funcionario.Estamento_id = hoja_de_ruta.estamento.id INNER JOIN hoja_de_ruta.calidad_juridica ON hoja_de_ruta.funcionario.Calidad_juridica_id = hoja_de_ruta.calidad_juridica.id INNER JOIN ssalud.departamento ON hoja_de_ruta.funcionario.Unidad_id = ssalud.departamento.dep_id INNER JOIN ssalud.persona AS Jefe ON hoja_de_ruta.funcionario.jefatura = Jefe.per_rut COLLATE "utf8_general_ci" INNER JOIN ssalud.persona AS Func ON hoja_de_ruta.funcionario.rut = Func.per_rut COLLATE "utf8_general_ci" WHERE  hoja_de_ruta.funcionario.estado = 1 ORDER BY hoja_de_ruta.funcionario.id DESC';

            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            if($cuantos > 0){
                $response = array();
                for($i = 0; $i < $cuantos; $i++){
                    $conector->set_fila();
                    $id = $conector->recuperar_dato('id');
                    $jefe = $this->es_jefe($rut,$id);
                    $inductor = $this->es_inductor($rut,$id);
                    $row = $conector->get_fila();
                    if ($jefe == 1) {
                        $row['es_jefe'] = 4;
                    }else{
                        $row['es_jefe'] = NULL;
                    }
                    if ($inductor == 1) {
                        $row['es_inductor'] = 5;
                    }else{
                        $row['es_inductor'] = NULL;
                    }
                    if ($depto > 0) {
                        $row['depto'] = $depto;
                    }
                    array_push($response,$row);
                }
            }else{
                $response = null;
            }
            $conector->desconectar();
            return($response);
        }

        public function es_jefe($rut ,$id){
            $sql  = "SELECT COUNT(funcionario.id) AS jefe FROM funcionario WHERE funcionario.id = ".$id." AND funcionario.jefatura='".$rut."'";
            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            $conector->set_fila();
            $response = $conector->recuperar_dato('jefe');
            $conector->desconectar();
            return($response);
        }

        public function es_inductor($rut ,$id){
            $sql  = "SELECT COUNT(agente_inductor.id) AS inductor FROM agente_inductor WHERE agente_inductor.id_funcionario = ".$id." AND agente_inductor.rut = '".$rut."' AND agente_inductor.estado = 1";
            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            $conector->set_fila();
            $response = $conector->recuperar_dato('inductor');
            $conector->desconectar();
            return($response);
        }

        public function Index2($rut){
            $sql  = "(SELECT hoja_de_ruta.funcionario.id,hoja_de_ruta.funcionario.rut,CONCAT( hoja_de_ruta.funcionario.nombre,' ', hoja_de_ruta.funcionario.apellido,' ', hoja_de_ruta.funcionario.apellido_2 ) AS funcionario,hoja_de_ruta.funcionario.Hoja_ruta_id,hoja_de_ruta.funcionario.fecha_ingreso,hoja_de_ruta.estamento.nombre AS estamento,hoja_de_ruta.calidad_juridica.nombre AS c_juridica,ssalud.departamento.dep_nombre AS unidad,hoja_de_ruta.funcionario.estado,hoja_de_ruta.funcionario.porcentaje,hoja_de_ruta.funcionario.jefatura AS rut_jefatura,ssalud.persona.per_nombre AS jefetura,4 AS es_jefe,5 AS es_inductor FROM hoja_de_ruta.funcionario INNER JOIN hoja_de_ruta.estamento ON hoja_de_ruta.funcionario.Estamento_id=hoja_de_ruta.estamento.id INNER JOIN hoja_de_ruta.calidad_juridica ON hoja_de_ruta.funcionario.Calidad_juridica_id=hoja_de_ruta.calidad_juridica.id INNER JOIN ssalud.departamento ON hoja_de_ruta.funcionario.Unidad_id = ssalud.departamento.dep_id INNER JOIN ssalud.persona ON hoja_de_ruta.funcionario.jefatura=ssalud.persona.per_rut COLLATE 'utf8_general_ci' WHERE hoja_de_ruta.funcionario.estado=1 AND hoja_de_ruta.funcionario.jefatura='".$rut."' ORDER BY hoja_de_ruta.funcionario.id DESC) UNION (SELECT hoja_de_ruta.funcionario.id,hoja_de_ruta.funcionario.rut,CONCAT( hoja_de_ruta.funcionario.nombre,' ', hoja_de_ruta.funcionario.apellido,'', hoja_de_ruta.funcionario.apellido_2 ) AS funcionario,hoja_de_ruta.funcionario.Hoja_ruta_id,hoja_de_ruta.funcionario.fecha_ingreso,hoja_de_ruta.estamento.nombre AS estamento,hoja_de_ruta.calidad_juridica.nombre AS c_juridica,ssalud.departamento.dep_nombre AS unidad,hoja_de_ruta.funcionario.estado,hoja_de_ruta.funcionario.porcentaje,hoja_de_ruta.funcionario.jefatura AS rut_jefatura,ssalud.persona.per_nombre AS jefetura,NULL AS es_jefe,5 AS es_inductor FROM hoja_de_ruta.funcionario INNER JOIN hoja_de_ruta.estamento ON hoja_de_ruta.funcionario.Estamento_id=hoja_de_ruta.estamento.id INNER JOIN hoja_de_ruta.calidad_juridica ON hoja_de_ruta.funcionario.Calidad_juridica_id=hoja_de_ruta.calidad_juridica.id INNER JOIN ssalud.departamento ON hoja_de_ruta.funcionario.Unidad_id = ssalud.departamento.dep_id INNER JOIN ssalud.persona ON hoja_de_ruta.funcionario.jefatura=ssalud.persona.per_rut COLLATE 'utf8_general_ci' INNER JOIN hoja_de_ruta.agente_inductor ON hoja_de_ruta.funcionario.id=hoja_de_ruta.agente_inductor.id_funcionario WHERE hoja_de_ruta.funcionario.estado=1 AND hoja_de_ruta.agente_inductor.rut='".$rut."' ORDER BY hoja_de_ruta.funcionario.id DESC)";

            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            if($cuantos > 0){
                $response = array();
                for($i = 0; $i < $cuantos; $i++){
                    $conector->set_fila();
                    $row = $conector->get_fila();
                    array_push($response,$row);
                }
            }else{
                $response = null;
            }

            $conector->desconectar();
            return($response);
        }

        public function index_buscador(){
            $sql  = "SELECT hoja_de_ruta.funcionario.id,hoja_de_ruta.funcionario.rut,CONCAT(hoja_de_ruta.funcionario.nombre,' ',hoja_de_ruta.funcionario.apellido,' ',hoja_de_ruta.funcionario.apellido_2) AS funcionario,hoja_de_ruta.funcionario.Hoja_ruta_id,hoja_de_ruta.funcionario.fecha_ingreso,hoja_de_ruta.estamento.nombre AS estamento,hoja_de_ruta.calidad_juridica.nombre AS c_juridica,ssalud.departamento.dep_nombre AS unidad,hoja_de_ruta.funcionario.estado,hoja_de_ruta.funcionario.porcentaje,hoja_de_ruta.funcionario.jefatura AS rut_jefatura,ssalud.persona.per_nombre AS jefetura,4 AS es_jefe,5 AS es_inductor FROM hoja_de_ruta.funcionario INNER JOIN hoja_de_ruta.estamento ON hoja_de_ruta.funcionario.Estamento_id=hoja_de_ruta.estamento.id INNER JOIN hoja_de_ruta.calidad_juridica ON hoja_de_ruta.funcionario.Calidad_juridica_id=hoja_de_ruta.calidad_juridica.id INNER JOIN ssalud.departamento ON hoja_de_ruta.funcionario.Unidad_id=ssalud.departamento.dep_id INNER JOIN ssalud.persona ON hoja_de_ruta.funcionario.jefatura=ssalud.persona.per_rut COLLATE 'utf8_general_ci' WHERE hoja_de_ruta.funcionario.estado=3 ORDER BY hoja_de_ruta.funcionario.id DESC";

            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            if($cuantos > 0){
                $response = array();
                for($i = 0; $i < $cuantos; $i++){
                    $conector->set_fila();
                    $row = $conector->get_fila();
                    array_push($response,$row);
                }
            }else{
                $response = null;
            }

            $conector->desconectar();
            return($response);
        }

        public function Buscar_actividades($id_func,$id_hr){
            $sql  = "SELECT hoja_ruta_has_actividad.Actividad_id,actividad.Depto_ejecutante_id,actividad.Ambito_id,actividad.Etapa_id,actividad.actividad_especifica,actividad.plazo,actividad_realizada.respuesta,actividad_realizada.observacion,actividad_realizada.created_at,actividad_realizada.Funcionario_id,actividad_realizada.Funcionario_id,anexo.nombre AS nom_anexo,anexo.id AS anexo FROM hoja_ruta_has_actividad INNER JOIN actividad ON hoja_ruta_has_actividad.Actividad_id=actividad.id LEFT JOIN actividad_realizada ON actividad.id=actividad_realizada.Actividad_id AND actividad_realizada.Funcionario_id=".$id_func." LEFT JOIN anexo ON actividad.id=anexo.Actividad_id WHERE hoja_ruta_has_actividad.Hoja_ruta_id=".$id_hr;
            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            
            if($cuantos > 0){
                $response['activity'] = array();
                for($i = 0; $i < $cuantos; $i++){
                    $conector->set_fila();
                    array_push($response['activity'],$conector->get_fila());
                }
            }else{
                $response = null;
            }
            
            $conector->desconectar();
            return($response);               
        }

        public function getJefaturas(){
            $sql = "SELECT persona.per_rut,persona.per_nombre FROM persona INNER JOIN funcionario_depto ON persona.per_rut=funcionario_depto.func_rut COLLATE 'utf8_general_ci' AND funcionario_depto.func_activo=1 WHERE persona.per_estado=1 AND funcionario_depto.func_tipo_jefatura> 0 GROUP BY per_rut ORDER BY persona.per_nombre ASC";
            $conector = new Conector();
            $conector->conectar('ssalud');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            if ($cuantos > 0) {
                $response['jef'] = array();
                for ($i = 0; $i < $cuantos; $i++) {
                    $conector->set_fila();
                    array_push($response['jef'], $conector->get_fila());
                }
            } else {
                $response['jef'] = 'error';
            }

            $conector->desconectar();
            return ($response);
        }

        public function Buscar_funcionario($id_func){
            $sql  = "SELECT * FROM funcionario where id = ".$id_func;
            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            if($cuantos > 0){
                $response['func'] = array();
                for($i = 0; $i < $cuantos; $i++){
                    $conector->set_fila();
                    array_push($response['func'],$conector->get_fila());
                }
            }else{
                $response = null;
            }
            
            $conector->desconectar();
            return($response);
        }

        public function Buscar_correos_funcionario($id_func){
            $sql  = 'SELECT CONCAT(hoja_de_ruta.funcionario.nombre," ",hoja_de_ruta.funcionario.apellido," ",hoja_de_ruta.funcionario.apellido_2) AS NombreFunc,ssalud.departamento.dep_nombre,ssalud.persona.per_email, ssalud.persona.per_nombre, hoja_de_ruta.funcionario.jefatura FROM hoja_de_ruta.funcionario INNER JOIN ssalud.departamento ON hoja_de_ruta.funcionario.Unidad_id=ssalud.departamento.dep_id INNER JOIN ssalud.persona ON hoja_de_ruta.funcionario.jefatura=ssalud.persona.per_rut COLLATE "utf8_general_ci" WHERE id = '.$id_func;
            $conector = new Conector(true);
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            if($cuantos > 0){
                $response = array();
                for($i = 0; $i < $cuantos; $i++){
                    $conector->set_fila();
                    array_push($response,$conector->get_fila());
                }
            }else{
                $response = null;
            }
            
            $conector->desconectar();
            return($response);
        } 

        public function Ingresar($post){
            $sql  = 'INSERT INTO funcionario(rut, nombre, apellido_2, apellido, genero, f_nac, fecha_ingreso, Hoja_ruta_id, Estamento_id, Calidad_juridica_id, Unidad_id, estado, jefatura, creador, porcentaje, fono, profesion, cargo) VALUES ("'.$post['inpRut'].'","'.$post['inpNombre'].'","'.$post['inpMaterno'].'","'.$post['inpPaterno'].'","'.$post['inpGenero'].'","'.$post['inpF_nac'].'","'.$post['inp_fIng'].'",'.$post['inpHR'].','.$post['inpEstamento'].','.$post['inpC_juridica'].','.$post['inpUnidad'].',1,"'.$post['jefe'].'","'.$post['session'].'",0,'.$post['inpFono'].',"'.$post['inpProfesion'].'","'.$post['inpCargo'].'")';
            $conector = new Conector(); 
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas_insert();
            if($cuantos > 0){
                $respuesta = $conector->recuperar_ultimo_id();
                $sqlB  = 'INSERT INTO agente_inductor(rut, estado, id_funcionario, responsable) VALUES ("'.$post['jefe'].'",1,"'.$respuesta.'","'.$post['session'].'")';
                $conector->ejecutar($sqlB);
            }else{
                $respuesta = 'false';
            }
            $conector->desconectar();
            return($respuesta);
        }

        public function Actualizar($post){       
        }

        public function actualizarPorcentaje($fun,$porcentaje){
            $conector = new Conector();         
            $conector->conectar('hoja_de_ruta');
            $delete = 'UPDATE funcionario set porcentaje = '.$porcentaje.' WHERE funcionario.id = '.$fun;
            $conector->ejecutar($delete);
            $conector->desconectar();
        }

        public function actualizarEstado($fun,$est){
            $conector = new Conector(true);         
            $conector->conectar('hoja_de_ruta');
            $upd = 'UPDATE funcionario set estado = '.$est.' WHERE funcionario.id = '.$fun;
            $conector->ejecutar($upd);
            $conector->desconectar();
        }

        public function Eliminar($id){       
        }

        public function agentes_Disponibles($depto){
            $sql = "SELECT persona.per_rut,persona.per_nombre FROM funcionario_depto INNER JOIN persona ON funcionario_depto.func_rut=persona.per_rut COLLATE 'utf8_general_ci' WHERE funcionario_depto.func_dep_id = ".$depto." AND persona.per_estado = 1 ORDER BY persona.per_nombre ASC";
            $conector = new Conector();
            $conector->conectar('ssalud');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            if ($cuantos > 0) {
                $response = array();
                for ($i = 0; $i < $cuantos; $i++) {
                    $conector->set_fila();
                    array_push($response, $conector->get_fila());
                }
            } else {
                $response = $sql;
            }

            $conector->desconectar();
            return ($response);
        }

        public function buscar_inductor($id){
            $sql = "SELECT agente_inductor.*,ssalud.persona.per_nombre FROM agente_inductor INNER JOIN ssalud.persona ON agente_inductor.rut=ssalud.persona.per_rut COLLATE 'utf8_general_ci' WHERE agente_inductor.estado = 1 AND agente_inductor.id_funcionario = ".$id;
            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            if ($cuantos > 0) {
                $response = array();
                for ($i = 0; $i < $cuantos; $i++) {
                    $conector->set_fila();
                    array_push($response, $conector->get_fila());
                }
            } else {
                $response = '';
            }

            $conector->desconectar();
            return ($response);
        }

        public function ingresar_agente_inductor($post){

            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $sqlUp = 'UPDATE agente_inductor set estado = 0 WHERE agente_inductor.id_funcionario = '.$post['id_func'].' AND estado = 1';
            $conector->ejecutar($sqlUp);

            $sql  = 'INSERT INTO agente_inductor(rut, estado, id_funcionario, responsable) VALUES ("'.$post['rut'].'",1,"'.$post['id_func'].'","'.$post['session'].'")';
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas_insert();
            if($cuantos > 0){
                $respuesta = $conector->recuperar_ultimo_id();
            }else{
                $respuesta = 'false';
            }
            $conector->desconectar();
            return($respuesta);
        }

        public function buscarJefatura($id_func){
            $sql  = "SELECT hoja_de_ruta.funcionario.rut,hoja_de_ruta.funcionario.jefatura,func.per_nombre AS jefe,func2.per_nombre AS funcionario FROM hoja_de_ruta.funcionario INNER JOIN ssalud.persona AS func ON hoja_de_ruta.funcionario.jefatura=func.per_rut COLLATE 'utf8_general_Ci' INNER JOIN ssalud.persona AS func2 ON hoja_de_ruta.funcionario.rut=func2.per_rut COLLATE 'utf8_general_Ci' WHERE hoja_de_ruta.funcionario.id = ".$id_func;
            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            if($cuantos > 0){
                $response = array();
                for($i = 0; $i < $cuantos; $i++){
                    $conector->set_fila();
                    array_push($response,$conector->get_fila());
                }
            }else{
                $response = null;
            }
            $conector->desconectar();
            return($response);
        }
    }
?>
