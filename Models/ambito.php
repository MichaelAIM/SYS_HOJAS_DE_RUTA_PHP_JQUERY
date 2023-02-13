<?php
    require_once('conector.class.php');
    class Ambito{
        public function __construct(){ 
        }
        
        public function Index(){

        }

        public function Buscar_por_HR($id){
            $sql  = "SELECT actividad.Ambito_id,ambito.nombre,actividad.Depto_ejecutante_id,actividad.Etapa_id FROM hoja_ruta_has_actividad INNER JOIN actividad ON hoja_ruta_has_actividad.Actividad_id=actividad.id INNER JOIN ambito ON actividad.Ambito_id=ambito.id WHERE hoja_ruta_has_actividad.Hoja_ruta_id = ".$id." GROUP BY Ambito_id, Depto_ejecutante_id";

            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            
            if($cuantos > 0){
                $response['ambito'] = array();
                for($i = 0; $i < $cuantos; $i++){
                    $conector->set_fila();
                    array_push($response['ambito'],$conector->get_fila());
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
