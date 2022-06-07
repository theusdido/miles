<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Body
    * Data de Criacao: 05/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Body Extends Elemento {
	/*  
		* Método construct
	    * Data de Criacao: 05/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag Body		
	*/
	public function __construct(){
		parent::__construct('body');
	}
}