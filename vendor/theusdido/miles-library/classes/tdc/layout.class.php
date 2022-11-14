<?php 
include_once PATH_WIDGETS . 'div.class.php';	
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Layout
    * Data de Criacao: 14/01/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Layout extends Div {
	public $header;
	public $article;
	public $footer;
	public $script;
	public $exibir_blocos = true;
	public $fluido = false;
	/*  
		* Método construct 
		* Data de Criacao: 14/01/2015
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)			
	*/			
	function __construct(){
		parent::__construct();
		$this->header 	= tdClass::Criar("header");
		$this->article 	= tdClass::Criar("article");
		$this->footer 	= tdClass::Criar("footer");
		$this->script	= tdClass::Criar('script');

		$this->header->class = $this->article->class = $this->footer->class = "container" . (CURRENT_THEME=='desktop'?'-fluid':'');
	}

	/*  
		* Método addCabecalho 
		* Data de Criacao: 14/01/2015
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona um elemento na tag header da página	
	*/			
	public function addCabecalho(){
		$linha 			= tdClass::Criar("div");
		$linha->class 	= "row";
		
		if (func_num_args() > 0){
			foreach (func_get_args() as $obj){
				$linha->add($obj);
			}
		}
		$this->header->add($linha);
	}	
	/*  
		* Método addCorpo 
		* Data de Criacao: 14/01/2015
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona um elemento na tag article da página	
	*/			
	public function addCorpo(){
		
		$linha = tdClass::Criar("div");
		$linha->class = "row";
		foreach (func_get_args() as $obj){			
			$linha->add($obj);
		}				
		$this->article->add($linha);
	}	
	/*  
		* Método addRodape 
		* Data de Criacao: 14/01/2015
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona um elemento na tag footer da página	
	*/			
	public function addRodape($obj){		
		$this->footer->add($obj);
	}

	public function mostrar(){
		if ($this->exibir_blocos) 	$this->addBloco();		

		if (!empty($this->header)) 	parent::add($this->header);
		if (!empty($this->article)) parent::add($this->article);
		if (!empty($this->footer)) 	parent::add($this->footer);

		// Adiciona script inicial
		parent::add($this->addJsInicial());		
		parent::mostrar();
	}
	/*  
		* Método Blocos 
		* Data de Criacao: 14/01/2015
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona blocos no layout
	*/			
	public function addBloco(){
		if ($this->exibir_blocos){
			$blocos = array("logo","info","logon","menu","headersystem","dadosconfigprojeto","rodape","JSinicial");
			foreach($blocos as $b){
				if (file_exists(PATH_MVC_CONTROLLER . $b . '.php')){
					include (PATH_MVC_CONTROLLER . $b . '.php');	
				}else if (file_exists(PATH_SYSTEM . PATH_MVC_CONTROLLER . $b . '.php')){
					include (PATH_SYSTEM . PATH_MVC_CONTROLLER . $b . '.php');	
				}
			}
			$this->addCabecalho($logo,$info,$logon);
			$this->fluido = false;
			$this->addCabecalho($menu);
			$this->addCabecalho(@$headersystem);
			$this->fluido = true;
			$this->addCabecalho($dadosconfigprojeto);			
			$this->addRodape($rodape);
		}
	}

	/*  
		* Método jsInicial
		* Data de Criacao: 04/10/2022
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Adiciona javascript principal no sistema
	*/
	public function addJsInicial(){
		include (PATH_MVC_CONTROLLER . 'JSinicial.php');
		return $JSinicial;
	}

}