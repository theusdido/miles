<?php
require_once PATH_ADO . 'sqlexpressao.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que cria os crit�rios (filtros) de uma consulta SQL 
    * Data de Criacao: 28/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	

class SqlCriterio extends SqlExpressao {

	public $expressao;
	public $operador;
	public $propriedade;
	
	/*  
		* M�todo Add
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Adiciona uma express�o ao crit�rio
		@parms $expressao (Objeto SqlExpressao)
		@parms $operador Operador L�gico da express�o
		
	*/		
	public function add(SqlExpressao $expressao,$operador = self::E_OPERADOR){
		// Na primeira vez n�o precisa de operador l�gica para concatenar
		if (empty($this->expressao)){
			unset($operador);
		}
		
		// Vai dar um erro a primeira vez pois o mesmo n�o tem operador
		@$this->operador[] = $operador;
		
		// Agrega o resultado da express�o a lista de express�es
		$this->expressao[] = $expressao;
		
	}
	
	/*  
		* M�todo Dump 
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna a express�o final
	*/		
	public function dump(){
		$result = "";
		// Concatena a lista de express�es
		if (is_array($this->expressao)){
			foreach($this->expressao as $i => $expressao){
				$operador = $this->operador[$i];
				
				$aRetirar = array("'RETIRAR","RETIRAR'");
				$dump = str_replace($aRetirar,"",$expressao->dump());
				
				// Concatena os operadores com a respectiva express�o
				$result .= $operador . $dump . ' ';
			}
			$result = trim($result);
			return "({$result})";
		}
	}

	/*  
		* M�todo setPropriedade 
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Define o valor de cada propriedade
		@parms $propriedade 
		@parms $valor 
	*/	
	public function setPropriedade($propriedade,$valor){
		$this->propriedade[$propriedade] = $valor;
	}
	
	/*  
		* M�todo getPropriedade 
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna o valor de uma propriedade
		@parms $propriedade 

	*/	
	public function getPropriedade($propriedade){
		if (isset($this->propriedade[$propriedade])){
			return $this->propriedade[$propriedade];
		}	
	}
	
	/*  
		* M�todo addFiltro 
	    * Data de Criacao: 28/05/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Adiciona uma express�o sem precsar criar o objeto "sqlfiltro" na chamada do m�todo
		@parms $atributo 
		@parms $operador
		@parms $valor
		@parms $operadorlogico: 

	*/
	public function addFiltro($atributo,$operador,$valor,$operadorlogico = E){
		$filtro = tdClass::Criar("sqlfiltro",array($atributo,$operador,$valor));
		$this->add($filtro,$operadorlogico);
	}
	
	/*  
		* M�todo isactive 
	    * Data de Criacao: 27/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorno um filtro padr�o para listar apenas os registros ativos
	*/		
	public function isactive(){
		$sql = tdClass::Criar("sqlcriterio");
		$filtroZero = tdClass::Criar("sqlfiltro",array("inativo","=",0));
		$filtroNull = tdClass::Criar("sqlfiltro",array("inativo","IS",NULL));
		$sql->add($filtroZero);
		$sql->add($filtroNull,OU);		
		$this->add($sql);
		return $sql;
	}
	
	/*  
		* M�todo registrounico 
	    * Data de Criacao: 27/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorno um filtro padr�o para listar apenas um registro
	*/		
	public function registrounico(){
		$this->setPropriedade("limit",1);
	}
	/*  
		* M�todo desc 
	    * Data de Criacao: 27/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Ordena os registro por ordem descentente
	*/
	public function desc($atributo = "id"){
		$this->setPropriedade("order",$atributo . " DESC");
	}
	/*  
		* M�todo asc 
	    * Data de Criacao: 27/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Ordena os registro por ordem ascendente
	*/
	public function asc($atributo = "id"){
		$this->setPropriedade("order",$atributo . " ASC");
	}	
}