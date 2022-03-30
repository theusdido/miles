<?php
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Sql Instru��o
    * Data de Criacao: 28/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	
	Super classe que da suporte para as instru��es SQL ( INSERT, UPDATE , DELETE e SELECT ) 
*/	

abstract class SqlInstrucao {
	protected $sql;
	protected $criterio;

	/*  
		* M�todo setEntidade 
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Define a tabela(entidade) do banco de dados
		@parms $entidade 
	*/		
	public function setEntidade($entidade){
		$this->entidade = $entidade;
	}
	
	/*  
		* M�todo getEntidade 
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna a tabela(entidade) do banco de dados		
	*/			
	public function getEntidade(){
		return $this-entidade;
	}
	
	/*  
		* M�todo setCriterio
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Define um crit�rio de sele��o dos dados atrav�s da compasi��o de um objeto do tipo sqlCriterio, que oferece uma interface para a defini��o de crit�rio
		@parms $criterio = objeto do tipo sqlCriterio() 
	*/	
	public function setCriterio(SqlCriterio $criterio){
		$this->criterio = $criterio;
	}
	
	/*  
		* M�todo getInstrucao 
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Colocado como abstrato obrigamos sua declara��o nas classes filhas, uma vez seu comportamento ser� distinto, configurando poliformismo
	*/		
	abstract function getInstrucao();
}