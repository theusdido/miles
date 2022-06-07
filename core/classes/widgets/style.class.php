<?php	
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Elemento
    * Data de Criacao: 30/08/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Style Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 30/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag Style		
	*/		
	public function __construct(){		
		parent::__construct('style');
	}	
}