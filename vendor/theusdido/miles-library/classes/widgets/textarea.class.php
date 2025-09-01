<?php 
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Title
    * Data de Criacao: 03/02/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Textarea Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 03/02/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag textarea
	*/		
	public function __construct(){		
		parent::__construct('textarea');
	}	

}