<?php
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Sql Instrução
    * Data de Criacao: 28/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	
	Super classe que da suporte para as instruções SQL ( INSERT, UPDATE , DELETE e SELECT ) 
*/	

abstract class SqlInstrucao {
	protected $sql;
	protected $criterio;

	/*  
		* Método setEntidade 
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Define a tabela(entidade) do banco de dados
		@parms $entidade 
	*/		
	public function setEntidade($entidade){
		$this->entidade = $entidade;
	}
	
	/*  
		* Método getEntidade 
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna a tabela(entidade) do banco de dados		
	*/			
	public function getEntidade(){
		return $this-entidade;
	}
	
	/*  
		* Método setCriterio
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Define um critério de seleção dos dados através da composição de um objeto do tipo sqlCriterio, que oferece uma interface para a definição de critério
		@parms $criterio = objeto do tipo sqlCriterio() 
	*/	
	public function setCriterio(SqlCriterio $criterio){
		$this->criterio = $criterio;
	}
	
	/*  
		* Método getInstrucao 
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Colocado como abstrato obrigamos sua declaração nas classes filhas, uma vez seu comportamento será distinto, configurando poliformismo
	*/		
	abstract function getInstrucao();
}