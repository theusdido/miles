<?php
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe que implementa a instalação do sistema
    * Data de Criacao: 24/04/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class tdInstall {
	public static $projeto = 0;
	private static $local;
	
	/*  
		* Método criarArquivosConfiguracao 
	    * Data de Criacao: 24/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria os arquivos de configuração baseado no Projeto
		@projeto: Código do projeto
	*/
	public static function criarArquivosConfiguracao($dados){
		$projeto = self::$projeto;
		$defaultdatabase = $dados["CURRENT_DATABASE"];
		if (is_numeric($defaultdatabase)){
			switch($defaultdatabase){
				case 1: $defaultdatabase = "desenv"; break;
				case 2: $defaultdatabase = "teste"; break;
				case 3: $defaultdatabase = "homologacao"; break;
				case 4: $defaultdatabase = "producao"; break;
			}
		}
		$fp = fopen(self::getLocalConfigProjeto() . "current_config.inc","w+");
		fwrite($fp,"PROJETO_DESC={$dados["PROJECT_DESC"]}\nPROJETO_FOLDER=miles/\nRAIZ=\nPREFIXO=td\nCURRENT_PROJECT={$projeto}\nCODIGOCLIENTE=0\nTHEME=padrao");
		fclose($fp);
	}
	
	/*  
		* Metodo criarArquivosBDProjeto
	    * Data de Criacao: 24/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria os arquivos de configuração do banco de dados baseado no Projeto
		@projeto: Código do projeto
	*/
	public static function criarArquivosBDProjeto(){
		$projeto = tdc::p(PROJETO,self::$projeto);
		foreach (tdc::d("td_connectiondatabase",tdc::f(PROJETO,"=",$projeto->id)) as $d){
			$host 		= $d->host;
			$base 		= $d->base;
			$usuario 	= $d->user;
			$senha 		= $d->password;
			$tipo		= tdc::p("td_typeconnectiondatabase",$d->type)->nome;
			$sgbd		= tdc::p("td_database",$d->sgdb)->nome;
			$porta 		= $d->port;

			$fp = fopen(self::getLocalConfigProjeto().$tipo."_mysql.ini","w+");
			fwrite($fp,"host={$host}\nbase={$base}\nusuario={$usuario}\nsenha={$senha}\nporta={$porta}\ntipo={$sgbd}");
			fclose($fp);
		}
	}
	/* 
		* Método getLocalProjeto 
	    * Data de Criacao: 25/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna o  diretório do projeto
		@projeto: Código do projeto
	*/
	private static function getLocalConfigProjeto(){
		if ($_SERVER["REQUEST_URI"] == "/miles/sistema/install/instalacaosistema.php"){			
			$local = "../project/config/";
		}else{
			$local = PATH_PROJECT . "config/";
		}
		if (file_exists($local)){
			return $local;
		}else{
			return false;
		}
	}
	/*  
		* Método isInstaled
	    * Data de Criacao: 07/11/2017
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna se o sistema instalado está instalado
	*/
	public static function isInstalled($database){
		$conn = Conexao::getConnection(
			null,
			$database->host,
			$database->base,
			$database->user,
			$database->password,
			$database->port
		);
		
		try{
			$sqlverifica = "select 1 from information_schema.TABLES where table_name = 'td_instalacao' AND table_schema = '{$database->base}';";
			$queryverifica = $conn->query($sqlverifica);
			if ($queryverifica->rowCount() > 0){
				$sql = "SELECT 1 FROM td_instalacao WHERE sistemainstalado = 1";
				$query = $conn->query($sql);
				if ($query->rowCount() > 0){
					return true;
				}else{
					return false;
				}
			}else{
				return false;
			}			
			throw new Exception("Banco de Dados <b>{$database->base}</b> não está instalado.");
		}catch(Exception $e){
			return false;
		}
	}
}