<?php 	
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe LabelEdit
    * Data de Criacao: 15/12/2014
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class LabelEdit Extends Elemento{	
	public $label;
	public $input;	
	/*  
		* Método construct 
		* Data de Criacao: 30/08/2012
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Cria um elemento label e input agrupados
		@parms
			1 - Id do Label e do Edit do sistema
			2 - Texto do Label
	*/			
	function __construct(){
		parent::__construct('div');
		$this->label = tdClass::Criar("label");
		$this->input = tdClass::Criar("input",array("text"));
		$this->class = "form-group";
		
		$this->add($this->label);
		$this->add($this->input);		
	}		
}