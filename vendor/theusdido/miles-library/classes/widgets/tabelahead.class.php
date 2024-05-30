<?php	
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Tabela Head
    * Data de Criacao: 12/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class TabelaHead Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 12/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Instância uma nova linha do tipo Head
	*/		
	public function __construct(){		
		parent::__construct('th');
	}	
}