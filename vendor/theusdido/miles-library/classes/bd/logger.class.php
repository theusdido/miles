<?php	
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que implementa a interface para definição de registro de log
    * Data de Criacao: 04/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
abstract class BdLogger{
	protected $arquivo;
	/*  
		* Método construtor 
	    * Data de Criacao: 04/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		@params $arquivo	
	*/	
	public function __construct($arquivo = null){
		$this->arquivo = $arquivo;
		#file_put_contents($arquivo,'');
	}	
	
	/*  
		* Método escrever 
	    * Data de Criacao: 04/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Será criado como abstrado para ser implementado nas classes filhas
	*/
	abstract function escrever($mensagem);
}