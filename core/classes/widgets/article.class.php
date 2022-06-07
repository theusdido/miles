<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Article
    * Data de Criacao: 14/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)		
*/	
class Article Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 14/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag Article
	*/
	public function __construct(){
		parent::__construct('article');
	}
}