 <?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Fieldset
    * Data de Criacao: 27/12/2014
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)		
*/
class Fieldset Extends Elemento {
	/*
		* M�todo construct
	    * Data de Criacao: 27/12/2014
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag Fieldset
	*/
	function __construct(){
		parent::__construct('fieldset');
	}
}