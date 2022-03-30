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
		* Método setLinha 
	    * Data de Criacao: 29/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Atribui valores a determinadas colunas no banco de dados que serão inseridas
		@parms $coluna
		@parms $valor	
	*/	
	public function setLinha($coluna,$valor){
		if (is_string($valor)){
			if (is_date($valor)){
				$this->colunaValor[$coluna] = "'".dateToMysqlFormat($valor)."'";	
			}else if (is_money($valor)){	
					$this->colunaValor[$coluna] = moneyToFloat($valor);
			}else{	
				$valor = addslashes($valor);
				$this->colunaValor[$coluna] = "'$valor'";
			}	
		}else if (is_bool($valor)){
			$this->colunaValor[$coluna] = $valor ? 'TRUE' : 'FALSE';
		}else if (is_date($valor)){
			$this->colunaValor[$coluna] = addslashes(date('Y-m-d'));			
		}else if (isset($valor)){
			$this->colunaValor[$coluna] = $valor;
		}else{
			$this->colunaValor[$coluna] = "NULL";
		}
	}	
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
		return utf8charset($this->sql);
	}	
}