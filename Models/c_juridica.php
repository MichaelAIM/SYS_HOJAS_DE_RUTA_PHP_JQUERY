<?php
    require_once('conector.class.php');
    class calidad_juridica{
        public function __construct(){ 
        }
        
        public function Index(){
            $sql  = "SELECT * FROM calidad_juridica";

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
