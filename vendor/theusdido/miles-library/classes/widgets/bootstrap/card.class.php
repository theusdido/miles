<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link http://teia.tec.br

    * Classe que implementa componente card do Bootstrap
    * Data de Criacao: 29/02/2024
    * Author: @theusdido
*/
class Card Extends Elemento {
	private $header     = '';
	private $body       = '';
	private $footer     = '';
	public $tamanho     = '';
	public $nome        = '';
	/*
		* Método construct 
	    * Data de Criacao: 29/02/2024
	    * Author: @theusdido

        Método construtor da classe
	*/		
	public function __construct(){
		parent::__construct('div');		
		$this->class        = "card";
        $this->header       = tdc::html("div");
        $this->body         = tdc::html("div");
        $this->footer       = tdc::html("div");

        $this->header->class    = "card-header";
        $this->body->class      = "card-body";
        $this->footer->class    = "card-footer";
	}

	public function addHeader($titulo=''){
		if ($titulo != ''){
			$this->header->add($titulo);
		}
	}

	public function addBody($content){
		
		
		$this->body->add($content);
	}

	public function addFooter($content){

		
		$this->footer->add($content);
	}
	public function mostrar(){

		if ($this->header->qtde_filhos > 0)
            $this->add($this->header);

		if ($this->body->qtde_filhos > 0)
            $this->add($this->body);

		if ($this->footer->qtde_filhos > 0)
            $this->add($this->footer);

		parent::mostrar();
	}

	public function getHeader(){
		return $this->header;
	}
	public function getBody(){
		return $this->body;
	}
}