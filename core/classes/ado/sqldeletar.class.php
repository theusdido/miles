<?php
require_once PATH_ADO . 'sqlinstrucao.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que implementa a instru��o DELETE do SQL
    * Data de Criacao: 28/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	

final class SqlDeletar extends sqlInstrucao{

	/*  
		* M�todo getInstrucao 
	    * Data de Criacao: 03/07/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Monta e retorna a instru��o SQL		
	*/		
	public function getInstrucao(){
		$valores = $colunas = "";
		$this->sql = "DELETE FROM {$this->entidade} ";
		
		if ($this->criterio){
			$expressao = $this->criterio->dump();
			if ($expressao){
				$this->sql .= ' WHERE '. $expressao;
			}
		}	
		return utf8charset($this->sql);
	}	
}