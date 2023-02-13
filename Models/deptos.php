<?php
    require_once('conector.class.php');
    class Deptos{
        public function __construct(){ 
        }
        
        public function Index(){
            $sql  = "SELECT * FROM depto_ejecutante";

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

        public function Buscar_por_HR($id){
            $sql  = "SELECT actividad.Depto_ejecutante_id,depto_ejecutante.nombre,actividad.Etapa_id,actividad.Ambito_id FROM hoja_ruta_has_actividad INNER JOIN actividad ON hoja_ruta_has_actividad.Actividad_id = actividad.id INNER JOIN depto_ejecutante ON actividad.Depto_ejecutante_id = depto_ejecutante.id WHERE hoja_ruta_has_actividad.Hoja_ruta_id = ".$id." GROUP BY Depto_ejecutante_id, Etapa_id";

            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            
            if($cuantos > 0){
                $response['deptos'] = array();
                for($i = 0; $i < $cuantos; $i++){
                    $conector->set_fila();
                    array_push($response['deptos'],$conector->get_fila());
                }
            }else{
                $response = null;
            }
            
            $conector->desconectar();
            return($response);
        } 

        public function Ingresar($post){
            $sql  = 'INSERT';

            $conector = new Conector(true); 
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

        public function Eliminar($id){       
        }
    }
?>