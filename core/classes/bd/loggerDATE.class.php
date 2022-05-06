<?php	
include PATH_BD . 'logger.class.php';
/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online
		
    * Classe que implementa registro de LOG em TXT divido por Data
    * Data de Criacao: 23/04/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class LoggerDATE extends BdLogger{

	/*  
		* Método escrever 
	    * Data de Criacao: 23/04/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Escreve uma mensagem no arquivo de log
	*/		
	public function escrever($mensagem){
		$datahora = date("d/m/Y H:i:s");
		$usuario = "Autenticação do Usuário";		
		if (Session::get()){
			if (isset(Session::get()->username)){
				$usuario = Session::get()->username;
			}else{
				
			}	
		}
		$texto  = "/* $datahora^{$usuario} */ $mensagem;
";

		$pathlog = tdFile::pathDateDir(PATH_CURRENT_LOG);

		if ($pathlog != false){
			$ref = fopen($pathlog . date("Y-d-m") . ".txt",'a+');
			fwrite ($ref,$texto);
			fclose($ref);
		}else{
			Debug::put("LoggerDATE->escrever: Erro ao criar o diretório do log [".PATH_CURRENT_LOG."].");
		}
	}
}