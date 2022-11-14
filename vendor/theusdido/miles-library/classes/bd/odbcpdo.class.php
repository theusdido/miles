<?php	
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe que implementa conexão com o banco de dados ODBC e retorna (simula) conexão PDO
    * Data de Criacao: 17/07/2019
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class ODBCPDO{
	private $conn;
	/*  
		* Método construtor 
	    * Data de Criacao: 17/07/2019
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria uma conexão ODBC 
	*/	
	public function __construct($host,$user,$pwd){			
		$this->conn = odbc_connect($host,$user,$pwd); // Cria conexão ODBC CACHÉ
		return $this->conn;
	}
	public function query($sql){
		if ($sql != '' && $sql != null && is_string($sql)){
			$result = odbc_exec($this->conn , $sql);
			return $result;
		}else{
			return false;
		}
	}
}