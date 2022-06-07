<?php	
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Script
    * Data de Criacao: 11/09/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Script Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 11/09/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria um link para uma página de script externa
	*/		
	public function __construct($type="text/javascript"){	
		parent::__construct('script');
		$this->type = $type;
	}
}
