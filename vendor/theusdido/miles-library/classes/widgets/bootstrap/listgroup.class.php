<?php
require_once PATH_TDC . "elemento.class.php";
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br

    * Classe List Group
    * Data de Criacao: 24/06/2017
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class ListGroup Extends Elemento {
	public $tag;
	protected $itens = Array();
	protected $titulo;
	/*  
		* Método construct 
	    * Data de Criacao: 24/06/2017
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Componente List Group
	*/
	public function __construct($tag = "div"){		
		parent::__construct($tag);
		$this->tag = $tag;
		$this->class = "list-group";
	}	
	public function mostrar(){
		$this->setItens();
		parent::mostrar();
	}
	public function addItem(){
		$args = func_get_args();
		array_push($this->itens,$args);
	}
	protected function setItens(){
		if ($this->titulo != null) $this->setItem($this->titulo,true);

		foreach ($this->itens as $i){
			$this->setItem($i);
		}
	}
	protected function setItem($item,$titulo=false){
		$listitem = tdClass::Criar("hyperlink");
		if (gettype($item) == "array"){			
			foreach($item as $it){
				if (gettype($it) == "array"){
					foreach($it as $i){
						$this->add($i);
						$listitem->add($i);
					}
				}else if(gettype($it) == "object"){
					$this->add($it);
					$listitem->add($it);
				}else{
					$listitem->add($it);
				}	
			}
		}else{
			$listitem->class = "list-group-item" . ($titulo?" active ":"");
			$listitem->href = "#";
			$listitem->add($item);
			$this->add($listitem);
		}
	}
	public function setTitulo($titulo){
		$this->titulo = $titulo;
	}

	public function addItemList($item){
		$item->class = "list-group-item";
		$this->add($item);
	}
}