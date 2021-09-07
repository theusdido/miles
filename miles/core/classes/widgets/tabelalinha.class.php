<?php	
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Tabela Linha
    * Data de Criacao: 31/08/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class TabelaLinha Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 31/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Instância uma nova linha
	*/		
	public function __construct(){		
		parent::__construct('tr');
	}	
}