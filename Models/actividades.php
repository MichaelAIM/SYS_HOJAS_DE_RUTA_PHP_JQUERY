<?php
    require_once('conector.class.php');
    class Activity{
        public function __construct(){ 
        }
        
        public function Index(){
            $sql  = "";

            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            
            if($cuantos > 0){
                $response['data'] = array();
                for($i = 0; $i < $cuantos; $i++){
                    $conector->set_fila();
                    array_push($response['data'],$conector->get_fila());
                }
            }else{
                $response = null;
            }
            
            $conector->desconectar();
            return($response);
        }

        public function Ingresar($post){
            $sql  = 'INSERT';

            $conector = new Conector(); 
            $conector->conectar('hoja_de_ruta');
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

        public function Actualizar($post){
        }

        public function countActividadRealizada($fun){
            $sql  = "SELECT COUNT(actividad_realizada.id) AS realizadas FROM actividad_realizada WHERE actividad_realizada.Funcionario_id=".$fun;
            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            $conector->set_fila();
            $response = $conector->recuperar_dato('realizadas');
            $conector->desconectar();
            return($response);
        }

        public function Actividad_Realizada($fun, $act, $resp, $obs, $responsable){
            $sql  = 'INSERT INTO actividad_realizada(Funcionario_id, Actividad_id, respuesta, observacion, responsable) VALUES ('.$fun.','.$act.',"'.$resp.'","'.$obs.'","'.$responsable.'")';

            $conector = new Conector(); 
            $conector->conectar('hoja_de_ruta');
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

        public function Eliminar($id){       
        }
    }
?>
