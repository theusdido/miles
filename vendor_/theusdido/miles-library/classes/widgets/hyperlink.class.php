<?php 
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Tabela HyperLink
    * Data de Criacao: 12/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class HyperLink Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 12/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria um HyperLink
	*/		
	public function __construct(){		
		parent::__construct('a');
	}	
}