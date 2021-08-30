<?php
require_once PATH_TDC . 'tdclass.class.php';
/*
    * Framework MILES
    * @license : Estilo Site Ltda.
    * @link http://www.estilosite.com.br
		
    * Classe curta para chamadas de classes personalizadas
    * Data de Criacao: 24/04/2020
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
*/	
class tdc Extends tdClass{
	/*  
		* Método p
		* Data de Criacao: 24/04/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		* Criar a Classe do tipo persistent

	*/		
	public static function p($classe,$id = null)
	{
		if (is_numeric_natural($classe)){
			$classe = tdc::e($classe)->nome;
		}
		if ($id == null){
			return tdClass::Criar("persistent",array($classe))->contexto;
		}else if(is_numeric($id)){
			return tdClass::Criar("persistent",array($classe,$id))->contexto;
		}else{
			return false;
		}
	}
	/*
		* Método w
		* Data de Criacao: 24/04/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Imprime String ( Substitui o comando "echo","print" e "var_dump")
	*/	
	public static function w($parms,$retorno = false){
		return tdClass::Write($parms,$retorno);
	}
	
	/*
		* Método r
		* Data de Criacao: 24/04/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Lê String ( Substitui o comando "$_GET","$_POST" e "$_FILES" )
	*/	
	public static function r($parms,$retornavazio = null){
		if (($conteudo = tdClass::Read($parms)) != false){
			return $conteudo;
		}else{
			if ($retornavazio == null){
				return '';
			}else if ($retornavazio != ""){
				return $retornavazio;
			}else{
				return false;
			}
		}
	}
	
	/*
		* Método d
		* Data de Criacao: 24/04/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Carrega e Retorna um DataSet do banco de dados
	*/
	public static function d($entidade,$sql = null){
		if (!$sql || $sql == null){
			$dataset = tdClass::Criar("repositorio",array($entidade))->carregar();
		}else{
			if (is_array($sql)){
				if (sizeof($sql) == 3){
					$sql = tdc::f($sql[0],$sql[1],$sql[2]);
				}else if(sizeof($sql) == 4){
					$sql = tdc::f($sql[0],$sql[1],$sql[2],$sql[3]);
				}
			}
			return tdClass::Criar("repositorio",array($entidade))->carregar($sql);
		}
		return $dataset;
	}
	/*
		* Método f
		* Data de Criacao: 24/04/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna um Filtro do banco de dados
	*/	
	public static function f($campo = null,$operador = null,$valor = null,$operadorlogico = E){
		$criterio = tdClass::Criar("sqlcriterio");
		
		if ($campo == null && $operador == null && $valor == null){
			return $criterio;
		}	
		if (is_array($campo) && $operador == null && $valor == null){
			foreach($campo as $f){
				$criterio->addFiltro($f[0],$f[1],$f[2],(isset($f[3])?$f[3]:$operadorlogico));
			}
		}else if (is_array($campo) && $operador != null && $valor != null){
			foreach($campo as $f => $c){
				$criterio->addFiltro($c,$operador,$valor,$operadorlogico);
			}
		}else{
			$criterio->addFiltro($campo,$operador,$valor,$operadorlogico);
		}
		return $criterio;
	}

	/*
		* Método c
		* Data de Criacao: 25/04/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna a quantidade de registro
	*/	
	public static function c($entidade,$sql = null){		
		return (int)tdClass::Criar("repositorio",array($entidade))->quantia($sql==null?new SqlCriterio():$sql);
	}
	
	/*  
		* Método o
		* Data de Criacao: 30/04/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		* Criar a Classe de objeto geral

	*/		
	public static function o($classe,$params = "")
	{
		if ($classe != ""){
			return tdClass::Criar($classe,$params);
		}else{
			return false;
		}
	}
	
	
	/*  
		* Método e
		* Data de Criacao: 30/04/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		* Retorna uma Classe de Persistent para Entidade
	*/
	public static function e($entidadeParams,$id = 0)
	{
		if (is_numeric_natural($entidadeParams)){
			$entidade = tdc::p(ENTIDADE,(int)$entidadeParams);
		}else if (is_string($entidadeParams)){
			$entidade = tdc::p(ENTIDADE,getEntidadeId($entidadeParams));
		}
		if ((int)$id == 0){
			return $entidade;
		}else{	
			return tdc::p($entidade->nome,(int)$id);
		}
	}

	/*  
		* Método html
		* Data de Criacao: 30/04/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		* Criar Objetos HTML

	*/
	public static function html($elemento,$body = null,$id = null,$class = null)
	{
		$e = tdc::o($elemento);		
		if ($body != null){
			$e->add($body);
		}
		if ($id != null){
			$e->id = $id;
		}
		if ($class != null){
			$e->class = $class;
		}
		return $e;
	}
	
	/*  
		* Método a
		* Data de Criacao: 01/05/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		* Retorna uma Classe de Persistent para Atributo
	*/
	public static function a($atributoParams,$entidadeParams = 0)
	{
		if (is_numeric_natural($atributoParams)){
			return tdc::p(ATRIBUTO,(int)$atributoParams);
		}else if (is_string($atributoParams)){			
			$atributo = tdc::p(ATRIBUTO,getAtributoId($entidadeParams,$atributoParams));
		}
		return $atributo;
	}
	/*
		* Método dh
		* Data de Criacao: 01/05/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		* Returna um DataSet do banco de dados com as entidade filho
	*/
	public static function dh($entidade,$id = 0, $tiposrelacionamentos){
		$entidade = tdc::e(getEntidadeId($entidade));
		$dados[$entidade->id] = tdc::p($entidade->nome,$id);
		if (sizeof($tiposrelacionamentos) > 0){
			foreach (getRelacionamentos($entidade->id,$tiposrelacionamentos) as $r){				
				if ($r->tipo == 1){
					$lista = getListaRegFilho($r->pai,$r->filho,$id);
					$dados[$r->filho] = tdc::p($r->filho,$lista[0]->regfilho);
				}
			}
		}
		return $dados;
	}
	/*
		* Método da
		* Data de Criacao: 22/06/2020
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna um DataSet do banco de dados no formato de uma Array
	*/	
	public static function da($entidade,$sql = null){
		if (!$sql || $sql == null){
			return tdClass::Criar("repositorio",array($entidade))->getDataArray();
		}else{
			if (is_array($sql)){
				if (sizeof($sql) == 3){
					$sql = tdc::f($sql[0],$sql[1],$sql[2]);
				}else if(sizeof($sql) == 4){
					$sql = tdc::f($sql[0],$sql[1],$sql[2],$sql[3]);
				}
			}
			return tdClass::Criar("repositorio",array($entidade))->getDataArray($sql);
		}
	}

	/*  
		* Método ft
	    * Data de Criacao: 07/11/2020
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna um objeto SQL Filtro
	*/		
	public static function ft($atributo,$operador,$valor){
		return tdClass::Criar("sqlfiltro",array($atributo,$operador,$valor));
	}
	
	/*
		* Método dj
		* Data de Criacao: 17/04/2021
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna um DataSet do banco de dados no formato JSON
	*/	
	public static function dj($entidade,$sql = null){
		return json_encode(tdc::da($entidade,$sql));
	}

	/*
		* Método de
		* Data de Criacao: 24/04/2021
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Deleta um dataset do banco de dados
	*/
	public static function de($entidade,$sql = null){
		if (!$sql || $sql == null){
			return tdClass::Criar("repositorio",array($entidade))->deletar();
		}else{
			if (is_array($sql)){
				if (sizeof($sql) == 3){
					$sql = tdc::f($sql[0],$sql[1],$sql[2]);
				}else if(sizeof($sql) == 4){
					$sql = tdc::f($sql[0],$sql[1],$sql[2],$sql[3]);
				}
			}
			return tdClass::Criar("repositorio",array($entidade))->deletar($sql);
		}
	}
	/*
		* Método pa
		* Data de Criacao: 26/05/2021
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna um registro persistente do banco no formato de uma Array
		$parametros: 1, Entidade:String. 2, id:Int
	*/
	public static function pa(string $entidade,int $id){
		$retorno = array();
		if ($id > 0 && $entidade != ""){
			$registro = tdc::da($entidade,tdc::f("id","=",$id));
			if (sizeof($registro) > 0){
				$retorno = $registro[0];
			}
		}
		return $retorno;
	}	
}