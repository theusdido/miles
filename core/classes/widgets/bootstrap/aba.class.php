<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Bloco
    * Data de Criacao: 31/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Aba Extends Elemento {
	private $conteudo;
	private $menu;
	private $indice 	= 0;
	public $contexto 	= "";
	private $tipo 		= "tabs";
	private $idkey		= '';

	/*  
		* Método construct 
	    * Data de Criacao: 31/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria o componente ABA
	*/
	public function __construct($id = ''){
		parent::__construct('div');

		$this->idkey			= $id;
		$this->data_id 			= $id;
		$this->role 			= "tabpanel";
		$this->class			= "td-aba";

		$this->menu = tdClass::Criar("ul");
		$this->menu->class 		= "nav";
		$this->menu->role 		= "tablist";		

		$this->conteudo 		= tdClass::Criar("div");
		$this->conteudo->class 	= "tab-content";
		
	}

	public function addItem($item,$conteudo="",$classABA = "",$indiceAba = ""){
		$id = $indiceAba . "-" . $this->contexto . "-conteudo-aba" . $this->indice;
		$li = tdClass::Criar("li");
		$li->role = "presentation";		
		if ($classABA!=""){
			$li->class = $classABA;
		}
		if ($this->indice == 0){
			$li->class = " active";
		}
		
		$a = tdClass::Criar("hyperlink");
		$a->add($item);
		$a->href				= '#'.$id;
		$a->data_toggle			= "tab";
		$a->role 				= "tab";
		$a->aria_controls		= $id;
		$a->aria_expanded 		= ($this->indice==0)?"true":"false";
		$li->add($a);
		$this->menu->add($li);

		$div 					= tdClass::Criar("div");
		$div->id 				= $id;
		$div->role 				= "tabpanel";
		$div->class 			= "tab-pane fade in ";
		$div->aria_labelledby	= $id;
		$div->add($conteudo);

		switch($this->getTipo()){
			case 'pills':
				$this->addPills($div);
			break;
			default:
				$this->conteudo->add($div);
		}
		$this->indice++;
	}
	public function addPills($conteudo){
		$li = tdClass::Criar("li");
		$li->class =  "li-aba-pills";
		$li->add($conteudo);
		$this->menu->add($li);
	}

	public function camposOBJ($campos){		
		$campos = explode(",",$campos);
		foreach($campos as $campo){
			$obj = tdClass::Criar("persistent",array(ATRIBUTO,(int)$campo));
			$camposOBJ[] = $obj->contexto;
		}
		return $camposOBJ;
	}

	public function mostrar(){		
		$this->menu->class = 'nav-' . $this->getTipo() . ($this->getTipo() == 'pills'? ' nav-stacked': '');
		$this->add($this->menu,$this->conteudo,$this->jsscript());
		parent::mostrar();
	}

	public function jsscript(){
		$script = tdClass::Criar("script");
		$script->add('
			$(".nav-tabs li:first, .tab-content div:first","[data-id=\"'.$this->idkey.'\"]").addClass("active in");
		');
		return $script;
	}

	/*  
		* Método setTipo 
	    * Data de Criacao: 18/08/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Seta o tipo
	*/
	public function setTipo($tipo){
		if ($tipo != ''){
			$this->tipo = $tipo;
		}
	}

	/*  
		* Método getTipo
	    * Data de Criacao: 18/08/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorno o tipo
	*/
	public function getTipo(){
		return $this->tipo;
	}

	/*  
		* Método addPage
	    * Data de Criacao: 09/12/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona uma página com IFrame
	*/
	public function addPage($item,$src){
		$iframe      = tdc::html('iframe');
		$iframe->src = $src;
		$this->addItem($item,$iframe);
	}
}