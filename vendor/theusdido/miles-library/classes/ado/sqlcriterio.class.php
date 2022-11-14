<?php
require_once PATH_ADO . 'sqlexpressao.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe que cria os critérios (filtros) de uma consulta SQL 
    * Data de Criacao: 28/06/2012
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	

class SqlCriterio extends SqlExpressao {

	public $expressao;
	public $operador;
	public $propriedade;
	
	/*  
		* Método Add
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Adiciona uma expressão ao critério
		@parms $expressao (Objeto SqlExpressao)
		@parms $operador Operador Lógico da expressão
		
	*/		
	public function add(SqlExpressao $expressao,$operador = self::E_OPERADOR){
		// Na primeira vez não precisa de operador lógica para concatenar
		if (empty($this->expressao)){
			unset($operador);
		}
		
		try{
			// Vai dar um erro a primeira vez pois o mesmo não tem operador
			@$this->operador[] = $operador;
		}catch(Throwable $t){

		}
		// Agrega o resultado da expressão a lista de expressões
		$this->expressao[] = $expressao;
		
	}
	
	/*  
		* Método Dump 
	    * Data de Criacao: 28/06/2012
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna a expressão final
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
		* Método setPropriedade 
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
		* Método getPropriedade 
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
		* Método addFiltro 
	    * Data de Criacao: 28/05/2015
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Adiciona uma expressão sem precsar criar o objeto "sqlfiltro" na chamada do método
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
		* Método onlyActive 
	    * Data de Criacao: 27/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorno um filtro padrão para listar apenas os registros ativos
	*/		
	public function onlyActive(){
		$sql = tdClass::Criar("sqlcriterio");
		$filtroZero = tdClass::Criar("sqlfiltro",array("inativo","=",0));
		$filtroNull = tdClass::Criar("sqlfiltro",array("inativo","IS",NULL));
		$sql->add($filtroZero);
		$sql->add($filtroNull,OU);		
		$this->add($sql);
		return $sql;
	}
	
	/*  
		* Método onlyOne 
	    * Data de Criacao: 27/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorno um filtro padrão para listar apenas um registro
	*/
	public function onlyOne(){
		$this->setPropriedade("limit",1);
	}
	/*  
		* Método desc 
	    * Data de Criacao: 27/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Ordena os registro por ordem descentente
	*/
	public function desc($atributo = "id"){
		$this->setPropriedade("order",$atributo . " DESC");
	}
	/*  
		* Método asc 
	    * Data de Criacao: 27/06/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Ordena os registro por ordem ascendente
	*/
	public function asc($atributo = "id"){
		$this->setPropriedade("order",$atributo . " ASC");
	}	
	/*  
		* Método limit 
	    * Data de Criacao: 15/12/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Limita a quantidade e a posição inicial do registro
	*/
	public function limit($length,$init = 0){
		$this->setPropriedade("limit","$init,$length");
	}

	/*  
		* Método isTrue
	    * Data de Criacao: 31/01/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Filtra um atributo booleano para true
	*/
	public function isTrue($atributo,$isnull = false){
		// true = 1
		$this->addFiltro($atributo,"=",1);

		// Filtra os nulos
		if ($isnull) $this->addFiltro($atributo,"IS",NULL);
	}

	/*  
		* Método isFalse
	    * Data de Criacao: 31/01/2022
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Filtra um atributo booleano para false
	*/
	public function isFalse($atributo,$isnull = true){
		// false = 0
		$this->addFiltro($atributo,"=",0);

		// Filtra os nulos
		if ($isnull) $this->addFiltro($atributo,"IS",NULL);
	}	
}
