<?php 
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Div
    * Data de Criacao: 20/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Select Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 20/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag Select
	*/		
	public function __construct(){		
		parent::__construct('select');
	}	

}