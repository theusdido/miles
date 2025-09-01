<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Bloco
    * Data de Criacao: 14/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

*/	
class Bloco Extends Elemento {
	/*
		* Método construct 
	    * Data de Criacao: 14/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria um bloco para ser adicionado dentro de um conteiner do Layout
	*/
	public function __construct($id=null){		
		parent::__construct('div');
		$this->class="bloco";
		if ($id!=null) $this->id = $id;
	}	

}