<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Div
    * Data de Criacao: 15/12/2014
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class Div Extends Elemento {
	/*
		* Método construct
	    * Data de Criacao: 15/12/2014
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag Div
	*/
	public function __construct(){
		parent::__construct('div');
	}
}