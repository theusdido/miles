<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Bloco
    * Data de Criacao: 03/10/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Titulo Extends Elemento {
	/*  
		* Método construct 
	    * Data de Criacao: 03/10/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria um bloco para ser adicionado dentro de um conteiner do Layout
	*/		
	public function __construct($titulo = ""){		
		parent::__construct('div');
		$this->class="titulo-pagina";
		if ($titulo != "")	$this->add($titulo);
	}	

}