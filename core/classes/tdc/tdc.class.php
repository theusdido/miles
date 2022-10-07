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
		$classe = getTableName($classe);
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
		$entidade = getTableName($entidade);
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
			$dataset = tdClass::Criar("repositorio",array($entidade))->carregar($sql);
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
		return (int)tdClass::Criar("repositorio",array(getTableName($entidade)))->quantia($sql==null?new SqlCriterio():$sql);
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
		if (gettype($body) == 'array'){
			foreach($body as $key => $value){
				$e->{$key} = $value;
			}
		}else{
			if ($body != null){
				$e->add($body);
			}
			if ($id != null){
				$e->id = $id;
			}
			if ($class != null){
				$e->class = $class;
			}
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
	public static function dh($entidade,$id = 0, $tiposrelacionamentos = []){
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
		$entidade = getTableName($entidade);
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
		try{
			$dados = json_encode(tdc::da(getTableName($entidade),$sql));
		}catch(Throwable $t){
			$dados = json_encode([]);
		}

		if (IS_SHOW_ERROR_MESSAGE && json_last_error() != JSON_ERROR_NONE){
			Debug::console($entidade . ' => ' . $sql->dump(),'SQL');
			var_dump(json_last_error_msg());
			var_dump(json_last_error());
		}
		
		return $dados;
	}

	/*
		* Método de
		* Data de Criacao: 24/04/2021
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Deleta um dataset do banco de dados
	*/
	public static function de($entidade,$sql = null){
		$entidade = getTableName($entidade);
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
	public static function pa($entidade,$id){
		$retorno = array();
		if ($id > 0 && $entidade != ""){
			$registro = tdc::da($entidade,tdc::f("id","=",$id));
		 	if (sizeof($registro) > 0){
		 		$retorno = $registro[0];
		 	}
		}
		return $retorno;
	}
	/*
		* Método pj
		* Data de Criacao: 31/12/2021
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		
		Retorna um registro persistente do banco no formato de JSON
		$parametros: 1, Entidade:String. 2, id:Int
	*/
	public static function pj($entidade,$id){
		$retorno = array();
		if ($id > 0 && $entidade != ''){
			$data = tdc::dj($entidade,tdc::f("id","=",$id));
			$data = gettype($data)=='string'?json_decode($data):$data;
			if (is_array($data) && sizeof($data) > 0){
				$registro = json_decode(tdc::dj($entidade,tdc::f("id","=",$id)));
				if (sizeof($registro) > 0){
					$retorno = $registro[0];
				}
			}
		}
		return $retorno;
	}
	/*
		* Método opt
		* Data de Criacao: 10/12/2021
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna uma array de objetos do tipo Options
		$parametros: 	entidade_name [String]
						valor selecionado
	*/
	public static function opt($entidade_name,$selected = null){
		$retorno 			= array();
		$entidade			= tdc::e($entidade_name);
		$dataset 			= tdc::d($entidade_name);
		$campodescricao		= tdc::a($entidade->campodescchave)->nome;
		foreach($dataset as $dado){
			$option 		= tdClass::Criar("option");
			$option->value 	= $dado->id;
			$option->add($dado->{$campodescricao});
			if ($dado->id == $selected) $option->selected = "true";
			array_push($retorno,$option);
		}
		return $retorno;
	}
	/*
		* Método ru
		* Data de Criacao: 08/01/2021
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna um objeto persistent com um único registro
		$parametros: 	entidade_name [String]

	*/
	public static function ru($entidade_name,$registro_id = 1){
		return tdc::p($entidade_name,$registro_id);
	}

	/*
		* Método rua
		* Data de Criacao: 02/07/2022
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Retorna uma array persistent com um único registro
		$parametros: 	entidade_name [String]

	*/
	public static function rua($entidade_name,$registro_id = 1){
		return tdc::pa($entidade_name,$registro_id);
	}	
	/*  
		* Método openId
		* Data de Criacao: 07/07/2022
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Tenta abrir um registro pelo ID, caso não encontre retorna um objeto vazio
	*/	
	public static function openId($entidade,$id)
	{
		$_entidade = tdc::p($entidade,$id);
		if (!$_entidade->hasData()){
			$new_entidade 		= tdc::p($entidade);
			$new_entidade->id 	= $id;
			$new_entidade->setIsNew();
			return $new_entidade;
		}
		return $_entidade;
	}
	/*  
		* Método wj
		* Data de Criacao: 18/09/2022
		* @author Edilson Valentim dos Santos Bitencourt (Theusdido)

		Exibe uma mensagem formata em JSON
	*/
	public static function wj($message)
	{
		echo json_encode($message);
	}

	public static function utf8($str){
		return isutf8($str) ? $str : utf8charset($str,'E');
	}
	
}