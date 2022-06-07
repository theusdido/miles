<?php 
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Nav
    * Data de Criacao: 15/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
	Tag Nav utilizada no BootStrap	
*/	
class Nav Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 15/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag nav
	*/		
	public function __construct(){		
		parent::__construct('nav');
		
	}	
}