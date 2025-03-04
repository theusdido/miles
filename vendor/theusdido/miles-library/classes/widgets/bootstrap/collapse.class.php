<?php
require_once PATH_WIDGETS . "div.class.php";
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Collapse
    * Data de Criacao: 12/03/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Collapse Extends Div {	
	private $id_tabs = 0;	
	private $tabs = array();
	private $icollapse = 0;
	/*  
		* Método construct 
	    * Data de Criacao: 12/03/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Componente Collapse
	*/		
	public function __construct(){		
		parent::__construct();
		$this->role = "tablist";
		$this->aria_multiselectable = "true";
		$this->icollapse = $_SESSION["icollapse"]++;
	}	
	private function addHead($dado){
		$panel_head 	= tdClass::Criar("div");	
		$panel_head->id = "heading" . $this->getIdTabs();
		$panel_head->role = "tab";
		$panel_head->class = "panel-heading";
		
		$h = tdClass::Criar("h",array(4));
		$h->class = "panel-title";
		
		$a = tdClass::Criar("hyperlink");
		$a->aria_controls = "collapse" . $this->getIdTabs();
		$a->aria_expanded = "true";
		$a->href = "#collapse" . $this->getIdTabs();
		$a->data_parent = "#accordion";
		$a->data_toggle = "collapse";
		$a->add($dado);
		
		$h->add($a);
		$panel_head->add($h);
		return $panel_head;
	}
	private function addBody($dado){
		$panel_body 	= tdClass::Criar("div");
		$panel_body->aria_labelledby="heading" . $this->getIdTabs();
		$panel_body->role="tabpanel";
		$panel_body->class="panel-collapse collapse";
		$panel_body->id="collapse" . $this->getIdTabs();
		$panel_body->aria_expanded="true";
		
		$body = tdClass::Criar("div");
		$body->class = "panel-body";
		$body->add($dado);
		$panel_body->add($body);
		return $panel_body;
	}
	public function addTab($head,$body){
		$panel		= tdClass::Criar("div");
		$panel->class = "panel panel-default";
		$this->id_tabs++;
		$panel->add($this->addHead($head),$this->addBody($body));
		array_push($this->tabs,$panel);
	}
	public function mostrar(){
		foreach ($this->tabs as $p){			
			$this->add($p);
		}	
		parent::mostrar();
	}
	private function getIdTabs(){
		return $this->icollapse . "-" . $this->id_tabs;
	}
}