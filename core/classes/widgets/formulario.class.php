<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Formulario
    * Data de Criacao: 15/12/2014
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)		
*/
class Formulario Extends Elemento {
	/*
		* Método construct
	    * Data de Criacao: 15/12/2014
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag Form
	*/
	function __construct(){
		parent::__construct('form');
	}
}