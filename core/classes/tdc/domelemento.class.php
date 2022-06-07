<?php
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que auxilia a manipulação do DOMELEMENT do PHP
    * Data de Criacao: 14/08/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class DomElemento extends Dom {
	public function __construct($elemento){
		parent::__construct($elemento);
	}
	/*
		* Método add
		* Data de Criacao: 14/08/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Cria e aetorna um objeto do tipo DOMELEMENT
	*/
	public function add($elemento, $propriedades = null, $elementopai = null){

		return parent::add($elemento, $propriedades = null, $this);
		/*
		
		if ($elementopai == null){
			parent::addEmentoRaiz($e);
		}else{
			$elementopai->appendChild($e);
		}
		return $this;
		*/
	}
}