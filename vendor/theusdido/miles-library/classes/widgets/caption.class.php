<?php 
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Caption
    * Data de Criacao: 19/06/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/
class Caption Extends Elemento {
	/*
		* Método construct
	    * Data de Criacao: 19/06/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag Caption
	*/
	public function __construct(){
		parent::__construct('caption');
	}
}