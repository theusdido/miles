 <?php	
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Imagem
    * Data de Criacao: 31/08/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Imagem Extends Elemento {
	private $src;

	/*  
		* M�todo construct 
	    * Data de Criacao: 31/08/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Localiza��o da imagem
		@params src
	*/		
	public function __construct(){		
		parent::__construct('img');
	}	

}