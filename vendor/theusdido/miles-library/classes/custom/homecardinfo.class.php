<?php
include_once PATH_TDC . 'elemento.class.php';

/*
    * Framework MILES
    * @license : Teia Online.
    * @link http://www.teia.online

    * Classe HomeCardInfo
    * Data de Criacao: 13/12/2018
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

*/	
class HomeCardInfo Extends Elemento {
	public $body = null;
	public $footer = null;
	private $background = "#27a9e3";
	private $width = "25%";
	private $icon;
	private $numero;
	private $texto;
	private $textorodape;
	/* 
		* Método construct 
	    * Data de Criacao: 13/12/2018
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Construct
	*/
	public function __construct(){		
		parent::__construct('div');
		$this->body = tdClass::Criar("div");
		$this->footer = tdClass::Criar("div");
	}
	public function body($obj){
		$this->body->class = "homecardinfo-body";
		$this->body->add($obj);
	}
	public function footer($obj){
		$this->footer->class = "homecardinfo-footer";
		$this->footer->add($obj);
	}
	public function mostrar(){
		$this->class = "homecardinfo";
		$this->add($this->style());
		if ($this->body->qtde_filhos>0) $this->add($this->body);
		if ($this->footer->qtde_filhos>0) $this->add($this->footer);
		parent::mostrar();
	}
	public function style(){
		$style = tdClass::Criar("style");
		$style->type = "text/css";
		$style->add('
			.homecardinfo{
				background-color:'.$this->getBackground().';
				width:'.$this->getWidth().';
				height:125px;
			}
		');
		return $style;
	}
	function setBackground($color){
		if ($color != "" && $color != null){
			$this->background = $color;
		}	
	}
	function getBackground(){
		return $this->background;
	}
	function setWidth($width){
		if ($width != "" && $width != null){
			$this->width = $width;
		}
	}
	function getWidth(){
		return $this->width;
	}
	function setIcon($icon){
		if ($this->icon == ""){
			$this->icon = $icon;
			$this->body($icon);
		}
	}
	function setNumero($numero){
		if ($numero != "" && $numero != null){
			$this->numero = $numero;
			$this->body($numero);
		}
	}
	function getNumero(){
		return $this->numero;
	}
	function setTexto($texto){
		if ($texto != "" && $texto != null){
			$this->texto = $texto;
			$this->body($texto);
		}
	}
	function getTexto(){
		return $this->texto;
	}
	function setTextoRodape($textorodape){
		if ($textorodape != "" && $textorodape != null){
			$this->textorodape = $textorodape;
			$this->footer($textorodape);
		}
	}
	function getTextoRodape(){
		return $this->textorodape;
	}	
}