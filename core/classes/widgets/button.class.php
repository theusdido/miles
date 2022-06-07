<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Nav
    * Data de Criacao: 19/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
	Tag Button utilizada no BootStrap
*/	
class Button Extends Elemento {
	/*
		* Método construct
	    * Data de Criacao: 19/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag button
	*/
	public function __construct(){
		parent::__construct('button');
	}
}