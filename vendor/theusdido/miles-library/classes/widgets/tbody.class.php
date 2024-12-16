<?php 
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe TBody
    * Data de Criacao: 05/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class TBody Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 05/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag TBody		
	*/		
	public function __construct(){		
		parent::__construct('tbody');
	}	

}