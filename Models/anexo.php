<?php
    require_once('conector.class.php');
    class Anexo{
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

        public function buscar_por_anexo($id_anexo, $id_func){

            $sql  = "SELECT anexo_pregunta.id,anexo_pregunta.pregunta,anexo_pregunta.anexo_categoria,anexo_categoria.nombre,anexo_respuesta.Anexo_respuesta,anexo_respuesta.Anexo_obs FROM anexo_pregunta LEFT JOIN anexo_respuesta ON anexo_pregunta.anexo_id=anexo_respuesta.id_anexo AND anexo_respuesta.id_func = ".$id_func." AND anexo_pregunta.id = anexo_respuesta.Anexo_pregunta_id INNER JOIN anexo_categoria ON anexo_pregunta.anexo_categoria=anexo_categoria.id WHERE anexo_pregunta.anexo_id = ".$id_anexo;

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

        public function buscar_anexo_realizado($id_anexo, $id_func){
            $sql  = "SELECT anexo_realizado.id, anexo_realizado.created_at FROM anexo_realizado WHERE anexo_realizado.id_anexo = ".$id_anexo." AND anexo_realizado.id_funcionario = ".$id_func;
            $conector = new Conector();
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas();
            
            if($cuantos > 0){
                $response = array();
                for($i = 0; $i < $cuantos; $i++){
                    $conector->set_fila();
                    $response['id'] = $conector->recuperar_dato('id');
                    $response['fecha'] = $conector->recuperar_dato('created_at');
                }
            }else{
                $response = null;
            }

            $conector->desconectar();
            return($response);
        }

        public function buscar_firmas_anexo($id_anexo_realizado){
            $sql  = "SELECT hoja_de_ruta.anexo_realizado_firma.*,ssalud.persona.per_nombre FROM hoja_de_ruta.anexo_realizado_firma INNER JOIN ssalud.persona ON hoja_de_ruta.anexo_realizado_firma.firmante=ssalud.persona.per_rut COLLATE 'utf8_general_ci' WHERE anexo_realizado_firma.id_anexo_realizado = ".$id_anexo_realizado;
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

        public function buscar_firmas_id($id){
            $sql  = "SELECT hoja_de_ruta.anexo_realizado_firma.*,ssalud.persona.per_nombre FROM hoja_de_ruta.anexo_realizado_firma INNER JOIN ssalud.persona ON hoja_de_ruta.anexo_realizado_firma.firmante=ssalud.persona.per_rut COLLATE 'utf8_general_ci' WHERE anexo_realizado_firma.id = ".$id;
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

        public function Ingresar_anexo_realizado( $post ){
            $sql  = 'INSERT INTO anexo_realizado(id_anexo, id_funcionario) VALUES ('.$post["id_anexo"].','.$post["id_func"].')';

            $conector = new Conector(); 
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas_insert();
            if($cuantos > 0){
                $respuesta = $conector->recuperar_ultimo_id();
                
            }else{
                $respuesta = false;
            }
            $conector->desconectar();
            return($respuesta);
        }


        public function Ingresar_anexo_firma( $post, $id_an_realizado, $ip ){
            $sql = 'INSERT INTO anexo_realizado_firma(id_anexo_realizado, firmante, ip, tipo) VALUES ('.$id_an_realizado.',"'.$post["firmante"].'","'.$ip.'",'.$post["tipo"].')';
            $conector = new Conector(); 
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas_insert();
            if($cuantos > 0){
                $respuesta = $conector->recuperar_ultimo_id();
            }else{
                $respuesta = false;
            }
            $conector->desconectar();
            return($respuesta);
        }

        public function Ingresar_anexo_respuestas( $post, $preg, $resp, $obs, $id_anexo_realizado ){
            $sql  = 'INSERT INTO anexo_respuesta(Anexo_pregunta_id, id_func, id_anexo_realizado, Anexo_respuesta, Anexo_obs, responsable, id_anexo) VALUES ('.$preg.','.$post["id_func"].','.$id_anexo_realizado.','.$resp.',"'.$obs.'","'.$post["firmante"].'",'.$post["id_anexo"].')';

            $conector = new Conector(); 
            $conector->conectar('hoja_de_ruta');
            $conector->ejecutar($sql);
            $cuantos = $conector->recuperar_afectadas_insert();
            if($cuantos > 0){
                $respuesta = $conector->recuperar_ultimo_id();
            }else{
                $respuesta = false;
            }
            $conector->desconectar();
            return($respuesta);
        }

        public function actualizar_firma_anexo($id,$img){
            $conector = new Conector();         
            $conector->conectar('hoja_de_ruta');
            $sql = "UPDATE anexo_realizado_firma SET anexo_realizado_firma.qr = '".$img."' WHERE anexo_realizado_firma.id = ".$id;
            $conector->ejecutar($sql);
            $conector->desconectar();
        }

        public function Eliminar($id){       
        }
    }
?>
