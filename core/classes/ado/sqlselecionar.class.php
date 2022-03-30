<?php
require_once PATH_ADO . 'sqlinstrucao.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que implementa a instru��o SELECT do SQL
    * Data de Criacao: 28/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	

final class SqlSelecionar extends sqlInstrucao{
	private $colunas = array();
	/*  
		* M�todo addColuna 
	    * Data de Criacao: 03/07/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Adiciona uma coluna a ser adiciona pelo SELECT
		@coluna = Coluna que ser� retornada
	*/		
	public function addColuna($coluna){
		array_push($this->colunas,$coluna);
	}
	
	/*  
		* M�todo getInstrucao 
	    * Data de Criacao: 03/07/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Monta e retorna a instru��o SQL		
	*/		
	public function getInstrucao(){
		$this->sql = 'SELECT ';
		$this->sql .= implode(',',$this->colunas);
		$this->sql .= " FROM " . $this->entidade;
		
		if ($this->criterio){
			$expressao = $this->criterio->dump();
			if ($expressao){
				$this->sql .= " WHERE " . $expressao; 
			}
			$group = $this->criterio->getPropriedade("group");
			$order = $this->criterio->getPropriedade("order");
			$limit = $this->criterio->getPropriedade("limit");
			$offset = $this->criterio->getPropriedade("offset");
			if ($group) $this->sql .= " GROUP BY " . $group;
			if ($order) $this->sql .= " ORDER BY " . $order;
			if ($limit) $this->sql .= " LIMIT " . $limit;
			if ($offset) $this->sql .= " OFFSET " . $offset;
		}		
		return $this->sql;
	}	
}