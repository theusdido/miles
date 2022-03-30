<?php
include_once PATH_TDC . 'elemento.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Modal
    * Data de Criacao: 17/03/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Alert Extends Elemento {
	public $type = "alert-success";
	public $onclick="";
	public $exibirfechar = true;
	public $alinhar = "right";
	public $weight = "bold";
	/*  
		* Método construct 
	    * Data de Criacao: 17/03/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Mensagem de alerta
	*/		
	public function __construct($msg=""){		
		parent::__construct('div');		
		$this->add($msg);
		$this->role = "alert";		
	}	
	public function botaoFechar(){
		
		$button = tdClass::Criar("button");
		$button->class="close";
		#$button->data_dismiss = "alert";
		$button->aria_label = "Close";		
		$span_button = tdClass::Criar("span");
		$span_button->aria_hidden = "true";
		$span_button->add("&times;");
		$button->add($span_button);
		if ($this->onclick!="") $button->onclick = $this->onclick;
		return $button;
	}
	public function mostrar(){		
		$this->class = "alert alert-dismissible " . $this->type;
		$this->style = "text-align:{$this->alinhar};font-weight:{$this->weight}";
		if ($this->exibirfechar) $this->add($this->botaoFechar());
		parent::mostrar();
	}
	/*
		* Método showMessage 
	    * Data de Criacao: 28/12/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Exibe uma mensagem
	*/
	public static function showMessage($msg, $tipo='alert-danger'){
		$alert 					= tdc::o('alert',array($msg));
		$alert->type 			= $tipo;
		$alert->alinhar			= 'center';
		$alert->exibirfechar 	= false;
		$alert->mostrar();
	}
}