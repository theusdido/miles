 <?php 
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Div
    * Data de Criacao: 05/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Head Extends Elemento {
	/*
		* M�todo construct 
	    * Data de Criacao: 05/01/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Tag HEAD
	*/
	public function __construct(){
		parent::__construct('head');
	}
}