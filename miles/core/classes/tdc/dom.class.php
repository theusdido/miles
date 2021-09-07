<?php
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que auxilia a manipulação do DOMDOCUMENT do PHP
    * Data de Criacao: 14/08/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class Dom {
	private $dom;
	private $elementoraiz;
	/*
		* Método __construct
		* Data de Criacao: 14/08/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Método construtor da classe
	*/
	public function __construct($elementoraiz = null,$atributos = null){
		$this->dom = new DOMDocument('1.0','ISO-8859-1');
		$this->dom->formatOutput = true;
		if ($elementoraiz != null){
			if (gettype($elementoraiz) == "string"){
				$this->elementoraiz = $this->dom->createElement($elementoraiz);
				if ($atributos != null){
					$this->addAtributos($this->elementoraiz,$atributos);
				}	
			}else{
				$this->elementoraiz = $this->dom->importNode($elementoraiz);
			}
			$this->dom->appendChild($this->elementoraiz);
		}
		return $this->dom;
	}
	/*
		* Método mostrar
		* Data de Criacao: 14/08/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Exibe o código do Documento
	*/
	public function mostrar($tipo = 'xml'){
		switch($tipo){
			default:
				echo $this->retirarTAGXML();
		}
	}
	private function retirarTAGXML(){
		return trim(preg_replace('/<\?xml.*?>/','',$this->dom->saveXML()));
	}
	protected function getDom(){
		return $this->dom;
	}
	
	protected function addEmentoRaiz($elemento){
		$this->elementoraiz->appendChild($elemento);
	}
	
	public function add($elemento,$opcoes = null){
		if (gettype($opcoes) == "array"){
			$propriedades 	= isset($opcoes["propriedades"]) ? $opcoes["propriedades"] : null;
			$elementopai 	= isset($opcoes["elementopai"]) ? $opcoes["elementopai"] : $this->elementoraiz;
		}else if (gettype($opcoes) == "object"){
			$elementopai 	= $opcoes;
			$propriedades	= null;
		}else{
			$propriedades 	= null;
			$elementopai 	= $this->elementoraiz;
		}
		$e = $this->node($elemento,$propriedades);
		$elementopai->appendChild($e);		
		return $e;
	}
	public function node($tag,$atributos = null){		
		$elemento = $this->dom->createElement($tag);
		if ($atributos != null){
			$elemento = $this->addAtributos($elemento,$atributos);
		}
		return $elemento;
	}
	
	private function addAtributos($elemento,$atributos){
		$atributos = (object)$atributos;
		if ($atributos != null){			
			foreach($atributos as $k => $v){
				if ($k == "innerhtml"){
					$elemento = $this->addInnerHTML($elemento,$v);
				}else{
					$elemento->setAttribute($k,$v);
				}
			}
			if (isset($atributos->innerhtml)){
				$elemento = $this->addInnerHTML($elemento,$atributos->innerhtml);
			}
		}
		return $elemento;
	}
	private function addInnerHTML($elemento,$innerhtml){
		if (gettype($innerhtml) == "string" || is_numeric($innerhtml)){
			$elemento->textContent = $innerhtml;
		}else{
			if (gettype($innerhtml) == "array"){
				foreach($innerhtml as $n){
					$elemento->appendChild($n);
				}
			}else{
				if ($innerhtml != null){					
					$elemento->appendChild($innerhtml);
				}	
			}
		}
		return $elemento;
	}
	public function getHTML(){
		return $this->retirarTAGXML();
	}
}