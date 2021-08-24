<?php	
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que implementa conex�o com o banco de dados
    * Data de Criacao: 04/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
final class Conexao{
	public static $currentdatabase = 0;
	private static $dados = array();
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

		Efetua a conexão com o banco de dados, recebe no  base criada
		@parms $banco
	*/
	public static function abrir($banco){
		
		/*
			Tratamento para a transação principal do sistema.
			Nesse caso deve chamar o arquivo do projeto selecionado
		*/		
		if ($banco == "current") {
			$arq_config = Session::Get("PATH_CURRENT_CONFIG_PROJECT").DATABASECONNECTION."_mysql.ini";
		}else if($banco == "miles"){
			$arq_config = PATH_CONFIG."miles_mysql.ini";
		}else{
			if (file_exists($_SESSION["PATH_CURRENT_CONFIG_PROJECT"] . $banco."_mysql.ini")){
				$arq_config = $_SESSION["PATH_CURRENT_CONFIG_PROJECT"] . $banco."_mysql.ini";
			}else{
				$arq_config = PATH_CURRENT_CONFIG_PROJECT . $banco."_mysql.ini";
			}
		}

		if (!file_exists($arq_config)){
			echo "Arquivo <b>{$banco}</b> de configuração com o banco de dados não existe. => " . $arq_config . " <= ";
			return null;
		}
		try{
			if (!$bd = parse_ini_file($arq_config)){
				throw new Exception("Arquivo <b>{$banco}</b> de configuração com o banco de dados n�o existe.");
			}
			self::$dados = $bd;
		}catch(Exception $e){
			if (IS_SHOW_ERROR_MESSAGE){
				echo utf8_encode($e->getMessage());
			}
			return false;
			exit;
		}

		$usuario 	= $bd["usuario"];
		$senha 		= $bd["senha"];
		$base 		= $bd["base"];
		$host		= $bd["host"];
		$tipo		= ($bd["tipo"]==""?"mysql":$bd["tipo"]);
		$porta		= ($bd["porta"]=="")?"3306":$bd["porta"];

		if ($usuario == ''){
			echo 'Usuário no arquivo de configuração com o banco de dados não pode ser vazio';
			exit;
		}
		
		if ($base == ''){
			echo 'Base de dados no arquivo de configuração com o banco de dados não pode ser vazio';
			exit;
		}
		
		if ($host == ''){
			echo 'Host no arquivo de configuração com o  banco de dados não pode ser vazio';
			exit;
		}
		switch(trim($tipo)){
			case 'mysql':
				try{
					$conn = Conexao::getConnection($tipo,$host,$base,$usuario,$senha,$porta);
				}catch(Throwable $t){
					if (IS_SHOW_ERROR_MESSAGE){
						var_dump($t->getMessage());
					}
				}
			break;
		}
		// Define o Schema
		if (!defined("SCHEMA")) define('SCHEMA',$bd["base"]);
		return $conn;
	}
	public static function getConnection($type = null,$host,$base,$user,$password = null,$port = null){
		if ($type == null) $type = "mysql";
		if ($type == "mysql"){
			try{
				$conn = new PDO(
					"$type:host=$host;port=$port;dbname=$base;",$user,$password
					,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
				);
				$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				return $conn;
			}catch(Exception $e){
				if (IS_SHOW_ERROR_MESSAGE){
					echo $e->getMessage();
				}
				return false;
			}
		}
	}
	public static function getDados(){
		return self::$dados;
	}
}