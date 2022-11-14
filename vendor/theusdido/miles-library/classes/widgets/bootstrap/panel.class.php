<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Modal
    * Data de Criacao: 15/06/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Panel Extends Elemento {
	public $tipo 	= "default";
	public $head 	= null;
	public $body 	= null;
	public $footer 	= null;
	private $title 	= null;
	/*  
		* Método construct 
	    * Data de Criacao: 15/06/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Construct 
	*/		
	public function __construct(){		
		parent::__construct('div');
		$this->head 	= tdClass::Criar("div");
		$this->body 	= tdClass::Criar("div");
		$this->footer 	= tdClass::Criar("div");
	}	
	public function botaoFechar(){
		$button 					= tdClass::Criar("button");
		$button->class				="close";
		$button->aria_label 		= "Close";		
		$span_button 				= tdClass::Criar("span");
		$span_button->aria_hidden 	= "true";
		$span_button->add("&times;");
		$button->add($span_button);
		if ($this->onclick!="") $button->onclick = $this->onclick;
		return $button;
	}
	public function head($obj){
		$this->head->class = "panel-heading";
		$this->head->add($obj);
	}
	public function body($obj){		
		$this->body->class = "panel-body";
		
		if (gettype($obj) == "array"){
			foreach($obj as $i){
				$this->body->add($i);
			}
		}else{
			$this->body->add($obj);
		}
		
	}
	public function footer($obj){
		$this->footer->class = "panel-footer";
		$this->footer->add($obj);
	}
	public function mostrar(){
		$this->class = "panel panel-" . $this->tipo;
		if ($this->title) $this->head($this->title);
		if ($this->head->qtde_filhos>0) $this->add($this->head);
		if ($this->body->qtde_filhos>0) $this->add($this->body);
		if ($this->footer->qtde_filhos>0) $this->add($this->footer);
		parent::mostrar();
	}

	/*  
		* Método title
	    * Data de Criacao: 06/11/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Adiciona um titulo
		@title: string/object
	*/
	public function title($title){
		$h4 		= tdc::o('h',array(4));
		$h4->class 	= 'panel-title';
		$h4->add($title);

		$this->title = $h4;
		return $this->title;
	}
}