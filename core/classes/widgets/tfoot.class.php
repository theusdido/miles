<?php 
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe TFoot
    * Data de Criacao: 05/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class TFoot Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 05/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag TBody		
	*/		
	public function __construct(){		
		parent::__construct('tfoot');
	}	

}