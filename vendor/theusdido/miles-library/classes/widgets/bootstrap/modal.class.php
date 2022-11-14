<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Modal
    * Data de Criacao: 16/03/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Modal Extends Elemento {
	public $exibirbotaofechar = true;
	private $header = "";
	private $body = "";
	private $footer = "";
	public $tamanho = "";
	public $nome = "";
	/*  
		* Método construct 
	    * Data de Criacao: 16/03/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Implementa uma dela Modal
	*/		
	public function __construct(){		
		parent::__construct('div');		
		$this->class = "modal fade";
		$this->tabindex = "-1";
		$this->role = "dialog";
		$this->aria_labelledby = "myModalLabel";
		$this->aria_hidden = "true";
	}	
	public function addHeader($titulo="",$bloco=null){
		$this->header = tdClass::Criar("div");
		$this->header->class = "modal-header";
		
		if ($this->exibirbotaofechar){
			$btn_fechar = tdClass::Criar("button");
			$btn_fechar->class = "close";
			$btn_fechar->data_dismiss = "modal";
			$btn_fechar->aria_hidden = "true";
			$btn_fechar->add("&times;");
			$this->header->add($btn_fechar);
		}
		
		if ($titulo != ""){
			$h = tdClass::Criar("h",array(4));
			$h->class = "modal-title";
			$h->id = "myModalLabel";
			$h->add($titulo);
			$this->header->add($h);
		}
		if ($bloco!=null) $this->header->add($bloco);
	}
	public function AddBody($bloco){
		$this->body = tdClass::Criar("div");
		$this->body->class = "modal-body";
		
		$p = tdClass::Criar("p");		
		$p->add($bloco);				
		$this->body->add($p);
	}
	public function addFooter($bloco){
		$this->footer = tdClass::Criar("div");
		$this->footer->class = "modal-footer";	
		$this->footer->add($bloco);
	}
	public function mostrar(){
		if (!isset($this->id)){
			$this->id = $this->nome == ""?"myModal":$this->nome;
		}
		
		$dialog = tdClass::Criar("div");
		$dialog->class = "modal-dialog " . $this->tamanho;
		$content = tdClass::Criar("div");
		$content->class = "modal-content";
		
		if ($this->header!="") $content->add($this->header);
		if ($this->body!="") $content->add($this->body);
		if ($this->footer!="") $content->add($this->footer);
		
		$dialog->add($content);
		$this->add($dialog);
		parent::mostrar();
	}
}