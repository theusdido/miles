<?php	
include PATH_ADO . 'sqlexpressao.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que implementa registro de LOG em HTML
    * Data de Criacao: 04/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class BdLoggerHTML extends BdLogger{	
	/*  
		* Método escrever 
	    * Data de Criacao: 04/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Escreve uma mensagem no arquivo de log
	*/		
	public function escrever($mensagem){		
		$datahora = date("Y-m-d H:i:s");
		$texto  = "<log>\n";
		$texto .= "		<datahora>$datahora</datahora>\n";
		$texto .= "		<mensagem>$mensagem</mensagem>\n";
		$texto .= "</log>\n";
		
		$ref = fopen($this->arquivo,'a');
		fwrite ($ref,$texto);
		fclose($ref);
	}	
}