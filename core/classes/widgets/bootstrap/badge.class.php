<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link http://www.teia.tec.br

    * Classe Badge
    * Data de Criacao: 19/05/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
	Componente Badge do Bootstrap
*/
class Badge Extends Elemento {
	/*
		* Método construct
	    * Data de Criacao: 19/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	*/
	public function __construct($valor = null){
		parent::__construct('span');
		$this->class = "badge";
		if ($valor != null) $this->add($valor);
	}
}