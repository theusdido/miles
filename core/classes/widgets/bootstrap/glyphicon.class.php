<?php
include_once PATH_WIDGETS . 'span.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe GlyphIcon
    * Data de Criacao: 11/06/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class GlyphIcon Extends Span {
	public $type_format;
	public $icon;
	public $span;
	/*  
		* M�todo construct 
	    * Data de Criacao: 11/06/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Mensagem de alerta
	*/		
	public function __construct($icon = "gryphicon-edit",$type="btn-default"){		
		parent::__construct('button');		
		$this->icon = $icon;
		$this->span = tdClass::Criar("span");		
		$this->type_format = $type;
	}
	/*  
		* M�todo construct 
	    * Data de Criacao: 11/06/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Mostrar
	*/		
	public function mostrar(){
		$this->class = "glyphicon " . $this->icon;
		$this->aria_hidden = "true";
		parent::mostrar();
	}
}