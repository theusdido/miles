<?php 
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe HTML
    * Data de Criacao: 14/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Html Extends Elemento {
	public $head;
	public $body;
	/*  
		* Método construct 
	    * Data de Criacao: 14/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria a tag html
	*/		
	public function __construct(){		
		parent::__construct('html');
		$this->head = tdClass::Criar("head");
	}	
	
	public function mostrar(){
		parent::mostrar();
	}

}