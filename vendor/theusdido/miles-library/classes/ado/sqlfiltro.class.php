<?php
require_once PATH_ADO . 'sqlexpressao.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que prov� interface para a defini��o de filtros de sele��o (SQL)
    * Data de Criacao: 28/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	

class SqlFiltro extends SqlExpressao {
	
	private $variavel;
	private $operador;
	private $valor;
	private $valorreal;
	
	/*  
		* M�todo construtor
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
	*/			
	public function __construct($variavel,$operador,$valor){
		$this->variavel 	= $variavel;
		$this->operador 	= $operador;
		$this->valor		= $this->transformar($valor);
		$this->valorreal	= $valor;
	}
	
	/*  
		* Método Transformar 
	    * Data de Criacao: 19/01/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Recebe um valor e faz modificações necessárias para que ele ser interpretado pelo banco de dados podendo ser um integer/string/boolean ou array.
	*/		
	public static function transformar($valor){
		if (is_array($valor)){
			if (empty($valor)) return "(0)";
			foreach ($valor as $x){
				if (is_integer($x)){
					$foo[] = $x;
				}else if(is_string($x)){
					$foo[] = "'$x'";
				}
			}
			$result = '('. implode(',',$foo) . ')';
		}else if (is_datetime($valor)){
			$dt = explode(" ",$valor);
			$result = "DATE_FORMAT('".dateToMysqlFormat($dt[0])." ". $dt[1]."','%Y-%m-%d %H:%i:%s')";
		}else if (is_date($valor) == 1){
			$result = "DATE_FORMAT('".dateToMysqlFormat($valor)."','%Y-%m-%d')";
		}else if(is_string($valor)){
			$result = "'$valor'";
		}else if (is_null($valor)){
			$result = 'NULL';
		}else if (is_bool($valor)){
			$result = $valor?'TRUE':'FALSE';
		}else if (is_numeric($valor)){
			$result = $valor;
		}else{
			$result = $valor;
		}
		return $result;
	}

	/*  
		* M�todo Dump 
	    * Data de Criacao: 19/01/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o filtro em forma de express�o
	*/		
	public function dump(){
		switch($this->operador){
			case '%':
				return "{$this->variavel} LIKE '%{$this->valorreal}%' ";
			break;
			default:
				return "{$this->variavel} {$this->operador} {$this->valor}";
		}
	}
}