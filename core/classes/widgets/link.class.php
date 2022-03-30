 <?php	
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Link
    * Data de Criacao: 11/09/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Link Extends Elemento {
	/*  
		* M�todo construct 
	    * Data de Criacao: 11/09/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria um link para uma p�gina externa
	*/		
	public function __construct(){	
		parent::__construct('link');
	}	
}