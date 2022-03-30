<?php	
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que implementa transações com o banco de dados
    * Data de Criacao: 04/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
final class Transacao{
	private static $conn;
	private static $logger;
	/*  
		* Método construtor 
	    * Data de Criacao: 04/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Será marcado como private pois não existirão instancias dessa classe
	*/	
		
	private function __construct(){	}
	
	/*  
		* Método abrir 
	    * Data de Criacao: 04/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Abre uma transação com o banco de dados
		@parms $banco	
	*/
	public static function abrir($banco){
		if (!empty(self::$conn)){
			self::rollback(); // Encerra a conexão ativa caso existir
		}

		// Carrega o arquivo da classe de conexão com o banco de dados
		require_once PATH_CLASS.'bd/conexao.class.php';
		include_once PATH_CLASS.'bd/loggerDATE.class.php';
		self::$conn = Conexao::abrir($banco);
		if (self::$conn){
			self::$conn->beginTransaction();
			self::$logger = new LoggerDATE();
			return true;
		}else{
			return false;
		}
	}
	
	/*  
		* Método get
	    * Data de Criacao: 04/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna a conexão ativa da transação
	*/			
	public static function get(){
		return self::$conn;
	}
	
	/*  
		* Método rollback
	    * Data de Criacao: 04/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Desfaz todas as operações realizadas pela transação
	*/			
	public static function rollback(){
		if(self::$conn){
			self::$conn->rollback();
			self::$conn = NULL;
		}
	}

	/*
		* Método commit
	    * Data de Criacao: 04/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Aplica todas as operações e fecha a transação
	*/
	public static function commit(){
		if(self::$conn){
			try{				
				self::$conn->commit();
			}catch(Exception $e){
				self::log($e->getMessage() . PHP_EOL);
				self::$conn = NULL;
			}
		}	
	}

	/*  
		* Método setLogger
	    * Data de Criacao: 04/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Define qual a estratégia de gravação de log
	*/			
	public static function setLogger(BdLogger $logger){
		self::$logger = $logger;
	}
	
	/* 
		* Método log
	    * Data de Criacao: 04/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Armazena uma mensagem no arquivo de log baseada na estratégia de log atual
	*/			
	public static function log($mensagem){
		if(self::$logger){
			if (IS_TRANSACTION_LOG){
				self::$logger->escrever($mensagem);
			}
		}
	}
}