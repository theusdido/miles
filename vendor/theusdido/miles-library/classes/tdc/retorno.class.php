<?php 
include_once PATH_WIDGETS . 'div.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe Retorno
    * Data de Criacao: 17/03/2015
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
*/	
class Retorno extends Div {
	/*  
		* Método construct 
	    * Data de Criacao: 17/03/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
				
	*/		
	public function __construct(){
		parent::__construct();	
		$this->class = "retorno";
		$this->style = "display:none";
		
	}	
	
	public function mensagem(){
		$alerta = tdClass::Criar("alert");		
		$alerta->onclick = "fecharAlerta();";		
		$strong = tdClass::Criar("strong");
		
		$alerta->add($strong);
		$this->add($alerta);			
	}
	public function mostrar(){
		$this->mensagem();
		parent::mostrar();
	}
}