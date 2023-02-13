<?php 
//	error_reporting(0);
	class Conector{

		// ATRIBUTOS

		private $host;
		private $user;
		private $psswd;


		// ATRIBUTOS PARA BD

		private $MYSQLI = TRUE;
		private $SERVER = TRUE;
		private $conexion;
		private $rs;
		private $fila;
		private $db;
		public $debug;

		// CONSTRUCTOR

		public function __construct($debug = FALSE){
			$this->debug = $debug;
			switch($this->get_server()){
				case FALSE:
#					Local
					$this->set_host("localhost");
					$this->set_user("root");
					$this->set_psswd("serviciodesalud");
					break;
				case TRUE:
#					Servidor 
					$this->set_host("SERVER_BD");
					$this->set_user("contingencia");
					$this->set_psswd("123456");
					break;
			}
		}

		// SET - GET HOST

		public function set_host($host){
			$this->host = $host;
		}

		public function get_host(){
			return $this->host;
		}

		// SET - GET SERVER

		public function set_server($server){
			$this->SERVER = $server;
		}

		public function get_server(){
			return $this->SERVER;
		}

		// SET - GET USUARIO

		public function set_user($user){
			$this->usuario = $user;
		}

		public function get_user(){
			return $this->usuario;
		}

		// SET - GET PSSWD

		public function set_psswd($psswd){
			$this->psswd = $psswd;
		}

		public function get_psswd(){
			return $this->psswd;
		}

		// SET - GET CONEXION

		public function set_conexion(){
			switch($this->MYSQLI){
				case (TRUE):
					$this->conexion = mysqli_connect($this->get_host(), $this->get_user(), $this->get_psswd()) or die("Error: ".mysqli_error($this->get_conexion()));
					break;
				default:
					$this->conexion = mysql_connect($this->get_host(), $this->get_user(), $this->get_psswd()) or die(utf8_encode("Error: ".mysql_error()));
					break;
			}
		}

		public function get_conexion(){
			return $this->conexion;
		}

		// SET - GET RS

		public function set_rs($rs){
			$this->rs = $rs;
		}

		public function get_rs(){
			return $this->rs;
		}

		// SET - GET DB

		public function set_db($bd){
			$this->db = $bd;
		}

		public function get_db(){
			return $this->db;
		}

		// SET - GET FILA

		public function set_fila(){
			switch($this->MYSQLI){
				case (TRUE):
					$this->fila = mysqli_fetch_assoc($this->get_rs());
					break;
				default:
					$this->fila = mysql_fetch_assoc($this->get_rs());
					break;
			}
		}

		public function get_fila(){
			return ($this->fila);
		}

		// FUNCION PARA CONECTAR

		public function conectar($bd){
			$this->set_conexion();
			$this->set_db($bd);
			switch($this->MYSQLI){
				case (TRUE):
					mysqli_select_db( $this->get_conexion(),$this->get_db() ) or die(mysqli_error($this->get_conexion()));
					break;
				default:
					mysql_select_db( $this->get_db(), $this->get_conexion() ) or die(mysql_error());
					break;
			}
		}

		// FUNCION PARA DESCONECTAR

		public function desconectar(){
			switch($this->MYSQLI){
				case (TRUE):
					mysqli_close($this->get_conexion()) or die ("Error: ".mysqli_error($this->get_conexion()));
					break;
				default:
					mysql_close($this->get_conexion()) or die ("Error: ".mysql_error());
					break;
			}
		}

		//  EJECUTAR LA SQL

		public function ejecutar($sql) {
			switch($this->debug){
				case (TRUE):
					echo $sql.'<br />';
					break;
				default:
					break;
			}
			switch($this->MYSQLI){
				case (TRUE):
					mysqli_query($this->get_conexion(),'SET NAMES UTF8');
					$this->set_rs( mysqli_query($this->get_conexion(),$sql) );
					break;
				default:
					mysql_query('SET NAMES UTF8');
					$this->set_rs( mysql_query($sql) );
					break;
			}
		}

		// RECUPERAR LAS CANTIDAD FILAS AFECTADAS

		public function recuperar_afectadas() {
			switch($this->MYSQLI){
				case (TRUE):
					$cuantos = mysqli_num_rows( $this->get_rs() );
					break;
				default:
					$cuantos = mysql_num_rows( $this->get_rs() );
					break;
			}
			return $cuantos;
		}

		public function recuperar_afectadas_insert() {
			switch($this->MYSQLI){
				case (TRUE):
					$cuantos = mysqli_affected_rows($this->get_conexion());
					break;
				default:
					$cuantos = mysql_affected_rows();
					break;
			}
			return $cuantos;
		}

		// RECUPERAR LA COLUMNA

		public function recuperar_dato($posicion){
			$fila = $this->get_fila();
			$dato = $fila[$posicion];
			return $dato;
		}
		
		public function recuperar_ultimo_id(){
			switch($this->MYSQLI){
				case (TRUE):
					$ultimo_id = mysqli_insert_id($this->get_conexion());
					break;
				default:
					$ultimo_id = mysql_insert_id($this->get_conexion());
					break;
			}
			return $ultimo_id;
		}

	}
?>
