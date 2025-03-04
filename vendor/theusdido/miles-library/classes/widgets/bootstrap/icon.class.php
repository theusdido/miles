<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Icon
    * Data de Criacao: 20/09/2017
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
	Tag Icon
*/
class Icon Extends Elemento {
	/*
		* Método construct
	    * Data de Criacao: 20/09/2017
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag Icon
	*/
	public function __construct($class = ""){
		parent::__construct('i');
		if ($class != ""){
			$this->class = $class;
		}
	}
}