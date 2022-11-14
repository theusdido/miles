<?php	
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que implementa conexão com o banco de dados
    * Data de Criacao: 04/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
final class Conexao{
	public static $currentdatabase 	= 0;
	private static $dados 			= array();
	private $base					= '';
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
		$bd = self::getDados($banco);
		if ($bd != false){
			$usuario 	= $bd["usuario"];
			$senha 		= $bd["senha"];
			$base 		= $bd["base"];
			$host		= $bd["host"];
			$tipo		= ($bd["tipo"]==""?"mysql":$bd["tipo"]);
			$porta		= ($bd["porta"]=="")?"3306":$bd["porta"];

			$erros 		= array();
			if ($usuario == ''){
				array_push($erros,
					'Usuário no arquivo de configuração com o banco de dados não pode ser vazio'
				);
			}
			
			if ($base == ''){
				array_push($erros,
					'Base de dados no arquivo de configuração com o banco de dados não pode ser vazio'
				);
			}
			
			if ($host == ''){
				array_push($erros,
					'Host no arquivo de configuração com o  banco de dados não pode ser vazio'
				);
			}

			if (sizeof($erros) > 0 && IS_SHOW_ERROR_MESSAGE){
				foreach($erros as $e){
					echo $e . "\n <br>";
				}
				return null;
			}else{
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
		}else{
			return null;
		}
	}

	public static function getConnection($type = 'mysql',$host='localhost',$base='',$user = 'root',$password = '',$port = '3306')
	{
		if ($type == "mysql"){
			try{
				$conn = new PDO(
					"$type:host=$host;port=$port;dbname=$base;",$user,$password
				);
				$conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
				return $conn;
			}catch(PDOException $e){
				if (IS_SHOW_ERROR_MESSAGE){
					echo $e->getMessage();
				}
				return false;
			}
		}
	}
	public static function getDados($banco = '',$sgdb = 'mysql'){
		/*
			Tratamento para a transação principal do sistema.
			Nesse caso deve chamar o arquivo do projeto selecionado
		*/
		$database 	= ($banco == '' || $banco == 'current') ? DATABASECONNECTION : $banco;
		$arq_config = PATH_CURRENT_CONFIG_PROJECT.$database.'_'.$sgdb.'.ini';
		$arq_temp	= PATH_CONFIG . 'temp_'.$sgdb.'.ini';
		$is_exists 	= !file_exists($arq_config) ? file_exists($arq_temp) ? $arq_config = $arq_temp : false : true;

		if (!$is_exists){
			if (IS_SHOW_ERROR_MESSAGE){
				echo "Arquivo <b>{$database}</b> de configuração com o banco de dados não existe. => " . $arq_config . " <= ";
			}
		}else{
			try{
				if (!$bd = parse_ini_file($arq_config)){
					throw new Exception("Arquivo <b>{$database}</b> de configuração com o banco de dados não existe.");
				}
				return $bd;
			}catch(Exception $e){
				if (IS_SHOW_ERROR_MESSAGE){
					echo utf8_encode($e->getMessage());
				}
				return false;
			}
		}
	}

	/*
		* Método getTemp
	    * Data de Criacao: 06/11/2022
	    * Autor @theusdido

		Retorna conexão do arquivo temporário do banco de dados
	*/
	public static function getTemp(){
		$_data = self::getDados('temp');
		$conn_temp = self::getConnection(
			$_data['tipo'],
			$_data['host'],
			$_data['base'],
			$_data['usuario'],
			$_data['senha'],
			$_data['porta']
		);
		return $conn_temp;
	}
}