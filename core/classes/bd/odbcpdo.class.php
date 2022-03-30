 <?php	
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe que implementa conex�o com o banco de dados ODBC e retorna (simula) conexx�o PDO
    * Data de Criacao: 17/07/2019
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class ODBCPDO{
	private $conn;
	/*  
		* M�todo construtor 
	    * Data de Criacao: 17/07/2019
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria uma conex�o ODBC 
	*/	
	public function __construct($host,$user,$pwd){			
		$this->conn = odbc_connect($host,$user,$pwd); // Cria conex�o ODBC CACH�
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