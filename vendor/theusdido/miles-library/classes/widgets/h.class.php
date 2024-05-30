<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br		
    * Classe H
    * Data de Criacao: 20/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)	
*/
class H Extends Elemento {
	/*
		* Método construct
	    * Data de Criacao: 20/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag h1,h2,h3,h4,h4=5,h6
	*/
	public function __construct($tamanho){
		parent::__construct('h' . $tamanho);
	}
}