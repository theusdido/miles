<?php 	
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Teia Tecnologia WEB
    * @link http://teia.tec.br

    * Classe Seleção Única
    * Data de Criacao: 24/08/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

*/	
class SelecaoUnica Extends Elemento{	
	private $entidade;
	private $filtro;
	private $options = array();
	/*  
		* Método construct 
		* Data de Criacao: 24/08/2015
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Cria um elemento SELECT com os dados de uma entidade
		@parms
			1 - Entidade
	*/			
	function __construct($entidade,$filtro=null){
		parent::__construct('select');
		$this->entidade = $entidade;
		$this->filtro 	= $filtro;
	}
	private function montar(){
		$sql 				= tdClass::Criar("sqlcriterio");
		$entidade 			= tdClass::Criar("persistent",array(ENTIDADE,$this->entidade));				
		$campodescricao 	= tdClass::Criar("persistent",array(ATRIBUTO,$entidade->contexto->campodescchave));		
		$dataset 			= tdClass::Criar("repositorio",array($entidade->contexto->nome))->carregar($sql);		
		$this->setOptions($dataset);
	}
	public function mostrar(){
		$this->montar();
		parent::mostrar();
	}
	public function setOptions($dataset){
		foreach($dataset as $dado){
			$option 		= tdClass::Criar("option");
			$option->value 	= $dado->id;
			$option->add($dado->{$campodescricao->contexto->nome});
			if ($dado->id == $this->filtro) $option->selected = "true";
			$this->add($option);
			array_push($this->options,$option);
		}
	}
	public function getOptions(){
		return $this->options;
	}
}