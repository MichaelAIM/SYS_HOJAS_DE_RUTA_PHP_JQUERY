<?php
	require_once('conector.class.php');
	class Personas{
		public function __construct(){ 
		}
		
		public function lista_de_permisos(){
			
			$sql  = 'SELECT';
			$sql .= ' *';
			$sql .= ' FROM';
			$sql .= ' servicios';
			$sql .= ' ORDER BY ser_id ASC';

			$conector = new Conector();
			
			$conector->conectar('ssalud');
			
			$conector->ejecutar($sql);
			
			$cuantos = $conector->recuperar_afectadas();
			
			if($cuantos > 0){
				$l_tipos = array();
				for($i=0;$i<$cuantos;$i++){
					$conector->set_fila();
					$l_tipos[] = $conector->get_fila();
				}
			}else{
				$l_tipos = "false";
			}
			$conector->desconectar();

			return($l_tipos);
		}

		public function buscar_email($rut){
			$sql  = 'SELECT persona.per_email FROM persona WHERE persona.per_rut = "'.$rut.'"';
			$conector = new Conector();
			$conector->conectar('ssalud');
			$conector->ejecutar($sql);
			$cuantos = $conector->recuperar_afectadas();
			if($cuantos > 0){
				$conector->set_fila();
				$response = $conector->recuperar_dato('per_email');
			}else{
				$response = NULL;
			}
			$conector->desconectar();
			return($response);
		}
		
		public function lista_func_tipo(){
			
			$sql  = 'SELECT';
			$sql .= ' *';
			$sql .= ' FROM';
			$sql .= ' funcionario_tipo';

			$conector = new Conector();
			
			$conector->conectar('ssalud');
			
			$conector->ejecutar($sql);
			
			$cuantos = $conector->recuperar_afectadas();
			
			if($cuantos > 0){
				$l_tipos = array();
				for($i=0;$i<$cuantos;$i++){
					$conector->set_fila();
					$l_tipos[] = $conector->get_fila();
				}
			}else{
				$l_tipos = "false";
			}
			$conector->desconectar();

			return($l_tipos);
		}
		
		public function lista_tipo_jefatura(){
			
			$sql  = 'SELECT';
			$sql .= ' *';
			$sql .= ' FROM';
			$sql .= ' tipos_jefaturas';

			$conector = new Conector();
			
			$conector->conectar('ssalud');
			
			$conector->ejecutar($sql);
			
			$cuantos = $conector->recuperar_afectadas();
			
			if($cuantos > 0){
				$l_tipos = array();
				for($i=0;$i<$cuantos;$i++){
					$conector->set_fila();
					$l_tipos[] = $conector->get_fila();
				}
			}else{
				$l_tipos = "false";
			}
			$conector->desconectar();

			return($l_tipos);
		}
		
		public function datos_persona($rut){
			
			$sql  = 'SELECT';
			$sql .= ' *';
			$sql .= ' FROM';
			$sql .= ' persona';
			$sql .= ' WHERE';
			$sql .= ' per_rut';
			$sql .= ' =';
			$sql .= ' "'.$rut.'"';

			$conector = new Conector();
			
			$conector->conectar('ssalud');
			
			$conector->ejecutar($sql);
			
			$cuantos = $conector->recuperar_afectadas();
			
			if($cuantos > 0){
				$l_tipos = array();
				for($i=0;$i<$cuantos;$i++){
					$conector->set_fila();
					$l_tipos[] = $conector->get_fila();
				}
			}else{
				$l_tipos = NULL;
			}
			$conector->desconectar();

			return($l_tipos);
		}

		public function lista_persona(){
			
			$sql  = 'SELECT';
			$sql .= ' persona.per_nombre,persona.per_rut';
			$sql .= ' FROM';
			$sql .= ' persona';

			$conector = new Conector();
			
			$conector->conectar('ssalud');
			
			$conector->ejecutar($sql);
			
			$cuantos = $conector->recuperar_afectadas();
			
			if($cuantos > 0){
				$l_tipos = array();
				for($i=0;$i<$cuantos;$i++){
					$conector->set_fila();
					$l_tipos[] = $conector->get_fila();
				}
			}else{
				$l_tipos = "false";
			}
			$conector->desconectar();

			return($l_tipos);
		}
		
		public function seleccionar_motivo_despido(){
			
			$sql = 'select * from motivo_despido';

			$conector = new Conector();
			$conector->conectar('ssalud');
			$conector->ejecutar($sql);
			$cuantos = $conector->recuperar_afectadas();
			
			if($cuantos > 0){
				$l_tipos = array();
				for($i=0;$i<$cuantos;$i++){
					$conector->set_fila();
					$l_tipos[] = $conector->get_fila();
				}
			}else{
				$l_tipos = "false";
			}
			$conector->desconectar();
			return($l_tipos);
		}
		
		public function seleccionar_motivo_ingreso(){
			
			$sql = 'select * from motivo_ingreso';

			$conector = new Conector();
			$conector->conectar('ssalud');
			$conector->ejecutar($sql);
			$cuantos = $conector->recuperar_afectadas();
			
			if($cuantos > 0){
				$l_tipos = array();
				for($i=0;$i<$cuantos;$i++){
					$conector->set_fila();
					$l_tipos[] = $conector->get_fila();
				}
			}else{
				$l_tipos = "false";
			}
			$conector->desconectar();
			return($l_tipos);
		}
		
		public function ingresar_Persona($r, $non, $pas, $fn, $e, $pe){	
			
			$conector = new Conector();
			
			$conector->conectar('ssalud');				
									
						$sql  = 'INSERT';
						$sql .= ' INTO';
						$sql .= ' persona';
						$sql .= ' (';
						$sql .= ' per_rut,';
						$sql .= ' per_nombre,';
						$sql .= ' per_clave,';
						$sql .= ' per_fecha_nacimiento,';
						$sql .= ' per_estado,';
						$sql .= ' per_email';						
						$sql .= ' )';
						$sql .= ' VALUES';
						$sql .= ' (';
						$sql .= ' "'.strtolower($r).'",'; 
						$sql .= ' "'.$non.'",'; 
						$sql .= ' "'.$pas.'",'; 
						$sql .= ' "'.$fn.'",';
						$sql .= ' "'.$e.'",';
						$sql .= ' "'.$pe.'"'; 						
						$sql .= ' )';
						
						//echo $sql.'<br />';
						
						$conector->ejecutar($sql);
			
						$cuantos = $conector->recuperar_afectadas_insert();

			if($cuantos > 0){
				$respuesta = 'true';
			}else{
				$respuesta = 'false';
			}
			$conector->desconectar();

			return($respuesta);
		}
		
		public function ingresar_datos_funcionario($r, $dep, $tip, $est, $jef){	
			
			$conector = new Conector();
			
			$conector->conectar('ssalud');				
									
						$sql  = 'INSERT';
						$sql .= ' INTO';
						$sql .= ' funcionario_depto';
						$sql .= ' (';
						$sql .= ' func_rut,';
						$sql .= ' func_dep_id,';
						$sql .= ' func_tipo,';
						$sql .= ' func_activo,';
						$sql .= ' func_tipo_jefatura';
						$sql .= ' )';
						$sql .= ' VALUES';
						$sql .= ' (';
						$sql .= ' "'.$r.'",'; 
						$sql .= ' "'.$dep.'",'; 
						$sql .= ' "'.(($tip > 0) ? $tip : 4).'",'; 
						$sql .= ' "'.$est.'",';
						$sql .= ' '.(($jef > 0) ? '"'.$jef.'"' : 'NULL').''; 						
						$sql .= ' )';
						
						//echo $sql.'<br />';
						
						$conector->ejecutar($sql);
			
						$cuantos = $conector->recuperar_afectadas_insert();

			if($cuantos > 0){
				$respuesta = 'true';
			}else{
				$respuesta = 'false';
			}
			$conector->desconectar();

			return($respuesta);
		}
		
		public function ingresar_Permiso($r, $p){	
			
			$conector = new Conector();
			
			$conector->conectar('ssalud');				
									
						$sql  = 'INSERT';
						$sql .= ' INTO';
						$sql .= ' funcionario_permiso';
						$sql .= ' (';
						$sql .= ' per_rut,';
						$sql .= ' per_sistema';						
						$sql .= ' )';
						$sql .= ' VALUES';
						$sql .= ' (';
						$sql .= ' "'.$r.'",'; 
						$sql .= ' "'.$p.'"';						
						$sql .= ' )';
						
						//echo $sql.'<br />';
						
						$conector->ejecutar($sql);
			
						$cuantos = $conector->recuperar_afectadas_insert();

			if($cuantos > 0){
				$respuesta = 'true';
			}else{
				$respuesta = 'false';
			}
			$conector->desconectar();

			return($respuesta);
		}
		
		public function actualizar_persona($rut,$nom,$dat,$email){
			$conector = new Conector();			
			$conector->conectar('ssalud');
			$sql = "UPDATE persona SET per_nombre = '".$nom."', per_fecha_nacimiento =  '".$dat."', per_email =  '".$email."' WHERE per_rut = '".$rut."'";
			$conector->ejecutar($sql);
			$conector->desconectar();
		}
		
		public function actualizar_func_tipo($rut,$func,$depto){
			$conector = new Conector();			
			$conector->conectar('ssalud');
			$sql = "UPDATE funcionario_depto SET func_tipo = '".$func."' WHERE func_rut = '".$rut."' AND func_dep_id ='".$depto."'";			
			$conector->ejecutar($sql);
			$conector->desconectar();			
		}
		
		public function actualizar_func_tipo_jefatura($rut,$jef,$depto){
			$conector = new Conector();			
			$conector->conectar('ssalud');
			$sql = "UPDATE funcionario_depto SET func_tipo_jefatura = '".$jef."' WHERE func_rut = '".$rut."' AND func_dep_id ='".$depto."'";			
			$conector->ejecutar($sql);
			$conector->desconectar();			
		}
		
		public function resetear_clave($rut,$clave){
			$conector = new Conector();			
			$conector->conectar('ssalud');
			$sql = "UPDATE persona SET per_clave = '".$clave."' WHERE per_rut = '".$rut."'";			
			$conector->ejecutar($sql);
			$conector->desconectar();			
		}
		
		public function ingresar_log_usuario($afec, $depto, $acc, $t_acc, $d_old, $d_new, $resp){	
			
			$conector = new Conector();
			
			$conector->conectar('ssalud');
									
				$sql = 'INSERT INTO';
				$sql .= ' log_usuario';
				$sql .= ' (afectado,fecha,depto,accion,tipo_accion,dato_old,dato_new,responsable,ip)';
				$sql .= ' VALUES';
				$sql .= ' ("'.$afec.'",now(),'.$depto.',"'.$acc.'",'.$t_acc.','.$d_old.','.$d_new.',"'.$resp.'","'.$_SERVER['REMOTE_ADDR'].'")';
				
				$conector->ejecutar($sql);
			
				$cuantos = $conector->recuperar_afectadas_insert();

			if($cuantos > 0){
				$respuesta = 'true';
			}else{
				$respuesta = 'false';
			}
			$conector->desconectar();

			return($respuesta);
		}
		
	}
	
?>
