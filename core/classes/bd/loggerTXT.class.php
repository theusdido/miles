<?php	
include PATH_BD . 'logger.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que implementa registro de LOG em TXT
    * Data de Criacao: 04/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class BdLoggerTXT extends BdLogger{

	/*  
		* Método escrever 
	    * Data de Criacao: 04/06/2012
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
		$ref = fopen($this->arquivo,'a');
		fwrite ($ref,$texto);
		fclose($ref);
	}	
}