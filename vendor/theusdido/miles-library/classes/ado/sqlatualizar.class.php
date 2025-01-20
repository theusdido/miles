<?php
require_once PATH_ADO . 'sqlinstrucao.class.php';
include_once PATH_SYSTEM . 'funcoes.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que implementa a instru��o UPDATE do SQL
    * Data de Criacao: 28/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	

final class SqlAtualizar extends sqlInstrucao{
	/*  
		* Método getInstrucao 
	    * Data de Criacao: 29/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Monta e retorna a instrução SQL
	*/		
	public function getInstrucao(){
		$valores = $colunas = "";
		$this->sql = "UPDATE {$this->entidade} ";
		
		if ($this->colunaValor){
			foreach ($this->colunaValor as $coluna=>$valor){
				$set[] = "{$coluna} = {$valor}";
			}
		}
		$this->sql .= " SET " . implode(", ",$set);
		if ($this->criterio){
			$this->sql .= " WHERE " . $this->criterio->dump();
		}
		return tdc::utf8($this->sql);
	}	
}