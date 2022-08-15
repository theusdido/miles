<?php
require_once PATH_ADO . 'sqlinstrucao.class.php';
include_once PATH_SYSTEM . 'funcoes.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que implementa a instrução INSERT do SQL
    * Data de Criacao: 28/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	

final class SqlInserir extends sqlInstrucao{

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
				if (strtoupper($valor) == 'DATE_NULL'){
					$valor = 'NULL';
				}else{
					$valor = addslashes($valor);
					$this->colunaValor[$coluna] = "'$valor'";
				}
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
		* Método setCriterio 
	    * Data de Criacao: 29/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Não existe no contexto dessa classe, logo, irá lançar um erro quando executado
		@parms $criterio - Objeto SQL Criterio
	*/		
	public function setCriterio(SqlCriterio $criterio){
		throw new Exepection ("Não pode chamar Criterio de " . __CLASS__);
	}
	
	/*  
		* M�todo getInstrucao 
	    * Data de Criacao: 29/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Monta e retorna a instrução SQL		
	*/			
	public function getInstrucao(){
		$valores = $colunas = "";
		$this->sql = "INSERT INTO {$this->entidade} (";
		$colunas .= implode(",",array_keys($this->colunaValor));
		$valores .= implode(",",array_values($this->colunaValor));
		$this->sql .= $colunas . ")";
		$this->sql .= " VALUES ({$valores})";
		
		return utf8charset($this->sql);
	}	
}