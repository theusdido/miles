<?php 
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Label
    * Data de Criacao: 15/12/2014
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Label Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 15/12/2014
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag Label		
	*/		
	public function __construct(){		
		parent::__construct('label');
	}	

}