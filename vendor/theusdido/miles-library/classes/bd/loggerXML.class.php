<?php	
include PATH_BD . 'logger.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que implementa registro de LOG em XML
    * Data de Criacao: 04/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class BdLoggerXML extends BdLogger{

	/*  
		* Método escrever 
	    * Data de Criacao: 04/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Escreve uma mensagem no arquivo de log
	*/		
	public function escrever($mensagem){		
		$datahora = date("Y-m-d H:i:s");
		$texto  = "<p>\n";
		$texto .= "		<b>$datahora</b>\n";
		$texto .= "		<i>$mensagem</i>\n";
		$texto .= "</p>\n";
		
		$ref = fopen($this->arquivo,'a');
		fwrite ($ref,$texto);
		fclose($ref);
	}	
}