<?php	
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Tabela
    * Data de Criacao: 31/08/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Tabela Extends Elemento {

	/*  
		* M�todo construct 
	    * Data de Criacao: 31/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Inst�ncia o objeto tabela
	*/		
	public function __construct(){		
		parent::__construct('table');		
	}		
}