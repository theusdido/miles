<?php
/*
    * Framework MILES
    * @license : Teia - Tecnologia WEB
    * @link http://www.teia.tec.br

    * Classe Grade
    * Data de Criacao: 08/05/2021
    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)

*/
class Grade {
	private $entidade;
	public $qtdadeMaximaRegistroPaginacao = 10;
	public $bloco = 1;
	private $sql;
	private $totalRegistros = 0;

	/*
		* Método __construct
	    * Data de Criacao: 08/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
		*	1 - $entidade: Nome da tabela ou ID da entidade
		* RETORNO
		*	[ void ]
		* FUNÇÃO
		* Inicializa a classe
	*/
	public function __construct($entidade = null){
		if ($entidade != null){
			$this->setEntidade($entidade);
		}
		$this->setSQL();
	}

	/*
		* Método setEntidade
	    * Data de Criacao: 08/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
		*	1 - $entidade: Nome da tabela ou ID da entidade
		* RETORNO
		*	[ void ]
		* FUNÇÃO
		* Adiciona um objeto persistent no atributo da entidade
	*/
	public function setEntidade($entidade){
		$this->entidade = tdc::e($entidade);
	}
	/*
		* Método setSQL
	    * Data de Criacao: 08/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* FUNÇÃO
		* Adiciona um objeto filtro na classe
	*/
	public function setSQL(){
		$this->sql 		= tdc::f();
		$this->sqlTotal = tdc::f();
	}

	/*
		* Método initRegistro
	    * Data de Criacao: 08/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* RETORNO
		*	[ int ] - Posição inicial da consulta
	*/
	public function initRegistro(){
		return (($this->qtdadeMaximaRegistroPaginacao * $this->bloco) - $this->qtdadeMaximaRegistroPaginacao);
	}
	/*
		* Método ordenacao
	    * Data de Criacao: 08/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
		*	1 - $order: Array com os campos a serem ordenados
		* RETORNO
		*	[ string ]
		* FUNÇÃO
		* Retorna uma string separada por virgula para ser usada no SQL
	*/
	public function ordenacao($order = []){		
		$ordenacao = '';
		foreach($order as $o){
			$ordenacao .= ($ordenacao==""?"":",") . "{$o["campo"]} {$o["tipo"]}";
		}
		if ($ordenacao != ''){
			$this->sql->setPropriedade("order",$ordenacao);
		}
		return $ordenacao;
	}
	
	/*
		* Método filtros
	    * Data de Criacao: 08/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
		*	1 - $filtros: 
		* FUNÇÃO
		* Monta as condições do SQL
	*/
	public function filtros($filtroParams = ''){
		if ($filtroParams != ""){
			$filtros = explode("~",$filtroParams);
			foreach($filtros as $ft){
				$f 			= explode("^",$ft);
				$campo_a 	= explode(" ",$f[0]);
				$camponome 	= $campo_a[0];

				if ($f[1] == "%" && $f[3] == "varchar"){
					$this->sql->addFiltro($camponome,"like",'%' . utf8charset($f[2],2) . '%');
				}else if ($f[3] == "datetime"){
					$dt = explode(" ",$f[2]);
					$this->sql->addFiltro($camponome,$f[1],$f[2]);
					$sqlTotal->addFiltro($camponome,$f[1],$f[2]);
				}else if ($f[1] == ","){
					$this->sql->addFiltro($camponome,"in",explode(",",$f[2]));
				}else if ($f[1] == "-"){

					$filtroNulo1 = tdc::ft($camponome,"is" . ($f[2]==1?" not ":""),null);
					$filtroNulo2 = tdc::ft($camponome,($f[2]==1?" <> ":" = "),'');

					$sqlFiltrosNulo = tdClass::Criar("sqlcriterio");
					$sqlFiltrosNulo->add($filtroNulo1);
					$sqlFiltrosNulo->add($filtroNulo2,OU);

					$this->sql->add($sqlFiltrosNulo);
				}else{
					$this->sql->addFiltro($camponome,$f[1],$f[2]);
				}
			}
		}
	}
	
	/*
		* Método filtro
	    * Data de Criacao: 08/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
		*	1 - $filtro: 
		* FUNÇÃO
		* Monta condição do SQL
	*/
	public function filtro($filtroParams = ""){
		if ($filtroParams != ""){
			$filtro = explode("^",$filtroParams);
			if (sizeof($filtro) > 0){
				$campo_a = explode(" ",$filtro[0]);
				$camponome = $campo_a[0];
				
				if ($filtro[2] == "int"){
					$this->sql->addFiltro($camponome,"=",$filtro[1]);
					//$sqlTotal->addFiltro($camponome,"=",$filtro[1]);		
				}else{
					$this->sql->addFiltro($camponome,"like",'%' . utf8charset($filtro[1],2) . '%');
					//$sqlTotal->addFiltro($camponome,"like",'%' . utf8charset($filtro[1],2) . '%');
				}
			}
		}
	}
	/*
		* Método filtroNN
	    * Data de Criacao: 08/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* PARAMETROS
		*	1 - $filtro: 
		* FUNÇÃO
		* Monta condição do SQL para relacionamentos na LISTA
	*/
	public function filtroNN($filtroParams){
		if ($filtroParams != ""){
			$parametros     = explode("^",$filtroParams);
			$entidadepai    = $parametros[0];
			$entidadefilho  = $parametros[2];
			$regpai         = $parametros[1];
			$lista  		= getListaRegFilho($entidadepai,$entidadefilho,$regpai);
			$ids    		= array();
			foreach ($lista as $l){
				array_push($ids,$l->regfilho);
			}
			$this->sql->addFiltro("id","in",$ids);
		}
	}
	/*
		* Método cabecalho
	    * Data de Criacao: 08/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* FUNÇÃO
		*	Seta os dados do cabecalho
	*/
	public function cabecalho(){
		$sql = tdClass::Criar("sqlcriterio");
		$sql->addFiltro(ENTIDADE,"=",$this->entidade->id);
		if (tdClass::Criar("persistent",array(CONFIG,1))->contexto->tipogradedados == "table"){
			$sql->addFiltro("exibirgradededados","=",1);
		}
		$sql->setPropriedade("order","ordem ASC");
		$dataset 			= tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql);
		$campos_nome 		= "id";
		$campos_descricao 	= "ID";
		$campos_tipo 		= "int";
		$campos_html 		= "3";
		$campos_fk 			= "0";
		foreach ($dataset as $dado){
			$campos_nome 		.= "," . $dado->nome;
			$campos_descricao 	.= "," . $dado->descricao;
			$campos_tipo 		.= "," . $dado->tipo;
			$campos_html 		.= "," . $dado->tipohtml;
			$campos_fk 			.= "," . $dado->chaveestrangeira;
		}
		$cabecalho = array(
			"nome" 			=> explode(",",$campos_nome),
			"descricao" 	=> explode(",",$campos_descricao),
			"ipo" 			=> explode(",",$campos_tipo),
			"html" 			=> explode(",",$campos_html),
			"fk" 			=> explode(",",$campos_fk)
		);
		return $cabecalho;
	}
	
	/*
		* Método corpo
	    * Data de Criacao: 08/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* FUNÇÃO
		*	Seta os dados do corpo
	*/
	public function corpo(){

		// Total de registro encontrado
		$this->totalRegistros = sizeof(tdc::da($this->entidade->nome,$this->sql));

		// Limite para paginação
		$this->sql->setPropriedade("limit",$this->initRegistro().",".$this->qtdadeMaximaRegistroPaginacao);

		// Dataset
		$dataset 		= tdClass::Criar("repositorio",array($this->entidade->nome))->carregar($this->sql);

		$dados 			= "";
		$cabecalho		= $this->cabecalho();
		$camposhtml 	= $cabecalho["html"];
		$camposfk 		= $cabecalho["fk"];
		$dados_array 	= $dados_array_reais = array();
		$idRegIndice 	= 1;
		$registros		= array();
		foreach($dataset as $dado){
			$valores			= array();
			$array_campos_nome 	= $cabecalho["nome"];
			$campos_dados 		= $campos_dados_reais = $campos_dados = array();
			$i = $attrRel 		= $idRegistro = 0;
			foreach($array_campos_nome as $c){
				if ($camposfk[$i] != "0" && $camposfk[$i] != ""){
					$entRel = tdClass::Criar("persistent",array(ENTIDADE,$camposfk[$i]));
					if ($entRel->contexto->campodescchave!="" && $entRel->contexto->campodescchave > 0){
						$attrRel = tdClass::Criar("persistent",array(ATRIBUTO,$entRel->contexto->campodescchave))->contexto->nome;
					}else{
						$sqlAttrRelVazio = tdClass::Criar("sqlcriterio");
						$sqlAttrRelVazio->addFiltro(ENTIDADE,"=",$entRel->contexto->id);
						$sqlAttrRelVazio->addFiltro("exibirgradededados","=",1);
						$sqlAttrRelVazio->setPropriedade("limit",1);
						$datasetAttrRelVazio = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sqlAttrRelVazio);

						if (sizeof($datasetAttrRelVazio)>0){
							$attrRel = $datasetAttrRelVazio[0]->nome;
						}
					}
					if ($dado->{$c} != ""){
						$valor_campo = tdClass::Criar("persistent",array($entRel->contexto->nome,$dado->{$c}))->contexto->{$attrRel};
					}
				}else{
					$valor_campo = $dado->{$c};
				}
				$reg =  getHTMLTipoFormato($camposhtml[$i],$valor_campo,$entidade->contexto->id,$c,$dado->id);
				$campos_dados[$c] = $reg;
				$campos_dados_reais[$c] = $dado->{$c};
				array_push($valores, $reg);
				$i++;
			}
			$dados_array[$idRegIndice] 			= $campos_dados;
			$dados_array_reais[$idRegIndice] 	= $campos_dados_reais;
			array_push($registros,array(
				"id" => $dado->id,
				"dados" => $valores
			));
			$idRegIndice++;
		}
		
		return (object)array(
			"dados" 		=> $dados_array,
			"dados_reais" 	=> $dados_array_reais,
			"registros"		=> $registros
		);
	}
	
	/*
		* Método json
	    * Data de Criacao: 08/05/2021
	    * @author Edilson Valentim dos Santos Bitencourt (Theusdido)
		* RETORNO
		*	[ json ] - Retorna os dados em formato JSON
	*/	
	public function json(){
		$corpo 		= $this->corpo();
		$retorno 	= array (
			"cabecalho" 	=> $this->cabecalho(),
			"entidade"		=> $this->entidade->id,
			"dados" 		=> $corpo->dados,
			"dadosreais"	=> $corpo->dados_reais,
			"registros"		=> $corpo->registros,
			"total"			=> $this->totalRegistros
		);
		return json_encode($retorno);
	}
}