<?php
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Bootstrap
    * Data de Criacao: 26/06/2017
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

*/	
class Bootstrap
{
	protected $csstag;
	protected $jstag;

	/* 
		* Método construct 
	    * Data de Criacao: 26/06/2017
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria um elemeneto HTML da biblioteca BootStrap
	*/
	public function __construct(){

		$css = tdClass::Criar("link");
		$css->href = PATH_LIB . 'bootstrap/3.3.1/css/bootstrap.css';
		$css->rel = 'stylesheet';
		$css->type = "text/css";
		$this->csstag = $css;

		$js = tdClass::Criar("script");
		$js->src = PATH_LIB . "bootstrap/3.3.1/js/bootstrap.js";
		$js->language = "JavaScript";
		$this->jstag = $js;

	}
	/* 
		* Método construct 
	    * Data de Criacao: 26/06/2017
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o objeto da tag CSS do BootStrap
	*/
	public function getCSSTag(){
		return $this->csstag;
	}
	/* 
		* Método construct 
	    * Data de Criacao: 26/06/2017
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o objeto da tag JS do BootStrap
	*/
	public function getJSTag(){		
		return 	$this->jstag;
	}
	/* 
		* Método construct 
	    * Data de Criacao: 26/06/2017
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Exibe a tag CSS do BootStrap
	*/

	public function showCSSTag(){
		$this->csstag->mostrar();
	}
	/* 
		* Método construct 
	    * Data de Criacao: 26/06/2017
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Exibe a tag JS do BootStrap
	*/

	public function showJSTag(){
		$this->jstag->mostrar();
	}
}