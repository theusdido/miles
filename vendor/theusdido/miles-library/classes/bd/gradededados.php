<?php

$op = tdc::r("op");

if ($op == "get_form"){

	// Validar JS
	$jsValidar = tdClass::Criar("script");
	$jsValidar->src = URL_SYSTEM . "validar.js";
	$jsValidar->mostrar();

	$jsScript = tdClass::Criar("script");
	$jsScript->add('funcionalidade = "emmassa";');
	$jsScript->mostrar();

	// Formulário JS	
	$jsFormulario = tdClass::Criar("script");
	$jsFormulario->src = PATH_SYSTEM . "formulario.js";
	$jsFormulario->mostrar();

	$atributo = tdc::r("atributo");

	$form = tdClass::Criar("tdformulario");
	$form->ncolunas 		= 1;
	$form->fp 				= true;
	$form->funcionalidade 	= "cadastro";

	$sql = tdClass::Criar("sqlcriterio");
	$sql->add(tdClass::Criar("sqlfiltro",array('id','=',$atributo)));

	$dataset = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql);
	$form->camposHTML($dataset);

	$form->mostrar();
	exit;
}
if ($op == "atualizar_emmassa"){
	$atributo 			= tdc::p(ATRIBUTO,tdc::r("atributo"));
	$entidade 			= tdc::p(ENTIDADE,$atributo->entidade);
	$registros			= explode(",",tdc::r("registros"));
	$entidadeprincipal 	= tdc::r("entidadeprincipal");

	// Tratamento para imagens em massa
	if ($atributo->tipohtml == 19){
		$valorJSON = json_decode(tdc::r("valor"));
		$valorJSON->isexcluirtemp = false;
		$valor = json_encode($valorJSON);
	}else{
		$valor = tdc::r("valor");
	}

	if ($entidadeprincipal == $entidade->id){
		foreach($registros as $reg){
			$registro = tdc::p($entidade->nome,$reg);
			$registro->{$atributo->nome} = Campos::Integridade($entidade->id,$atributo->nome,$valor,$reg);
			$registro->armazenar();
		}
	}else{
		$relacionamento = tdc::p(RELACIONAMENTO,tdc::r("relacionamento"));
		$atributoRel 	= tdc::p(ATRIBUTO,$relacionamento->atributo)->nome;
		$dataset 		= tdc::d($entidade->nome,tdc::f($atributoRel,"IN",$registros));
		foreach($dataset as $d){
			$reg 		= $d->id;
			$registro 	= tdc::p($entidade->nome,$reg);			
			$registro->{$atributo->nome} = Campos::Integridade($entidade->id,$atributo->nome,$valor,$reg);
			$registro->armazenar();
		}
	}

	// Exclui o arquivo temporário
	if ($atributo->tipohtml == 19) unlink($valorJSON->src);

	$conn = Transacao::Fechar();
	echo 1;
	exit;
}
if ($op == "get_atributos"){
	echo json_encode(tdc::da(ATRIBUTO,tdc::f(ENTIDADE,"=",tdc::r("entidade"))));
	exit;
}
if ($_POST["entidade"] == "" || (int)$_POST["entidade"] <= 0){
	echo 'Entidade de chave estrangeira não configurada';
	exit;
}

$entidade = tdClass::Criar("persistent",array(ENTIDADE,$_POST["entidade"]));
$max_registros = tdc::r("qtdademaximaregistro",10);
$bloco = tdClass::Read("bloco");
$ini_reg = (($max_registros * $bloco) - $max_registros);

// Campos do Cabeçalho
$sql = tdClass::Criar("sqlcriterio");
$sql->addFiltro(ENTIDADE,"=",$entidade->contexto->id);
if (tdClass::Criar("persistent",array(CONFIG,1))->contexto->tipogradedados == "table"){
	$sql->addFiltro("exibirgradededados","=",1);
}	
$sql->setPropriedade("order","ordem ASC");
$dataset = tdClass::Criar("repositorio",array(ATRIBUTO))->carregar($sql);
$campos_nome = "id";
$campos_descricao = "ID";
$campos_tipo = "int";
$campos_html = "3";
$campos_fk = "0";
foreach ($dataset as $dado){
	$campos_nome .= "," . $dado->nome;
	$campos_descricao .= "," . $dado->descricao;
	$campos_tipo .= "," . $dado->tipo;
	$campos_html .= "," . $dado->tipohtml;
	$campos_fk .= "," . $dado->chaveestrangeira;
}

// Carrega Dados
$sql = tdClass::Criar("sqlcriterio");
$sql->setPropriedade("limit",$ini_reg.",".$max_registros);

if ($order = tdc::r("order",[])){
	$ordenacao = "";
	foreach($order as $o){
		$ordenacao .= ($ordenacao==""?"":",") . "{$o["campo"]} {$o["tipo"]}";
	}
	if ($ordenacao!=""){
		$sql->setPropriedade("order",$ordenacao);
	}	
}

// Quantidade total de dados
$sqlTotal = tdClass::Criar("sqlcriterio");

// Filtros
if (tdClass::read("filtros") != ""){
	$filtros = explode("~",tdClass::read("filtros"));	
	foreach($filtros as $ft){
		$f = explode("^",$ft);
		$campo_a = explode(" ",$f[0]);
		$camponome = $campo_a[0];

		if ($f[1] == "%" && $f[3] == "varchar"){
			$sql->addFiltro($camponome,"like",'%' . tdc::utf8($f[2],2) . '%');
			$sqlTotal->addFiltro($camponome,"like",'%' . tdc::utf8($f[2],2) . '%');
		}else if ($f[3] == "datetime"){
			$dt = explode(" ",$f[2]);
			$sql->addFiltro($camponome,$f[1],$f[2]);
			$sqlTotal->addFiltro($camponome,$f[1],$f[2]);
		}else if ($f[1] == ","){
			$sql->addFiltro($camponome,"in",explode(",",$f[2]));
			$sqlTotal->addFiltro($camponome,"in",explode(",",$f[2]));
		}else if ($f[1] == "-"){

			$filtroNulo1 = tdc::ft($camponome,"is" . ($f[2]==1?" not ":""),null);
			$filtroNulo2 = tdc::ft($camponome,($f[2]==1?" <> ":" = "),'');

			$sqlFiltrosNulo = tdClass::Criar("sqlcriterio");
			$sqlFiltrosNulo->add($filtroNulo1);
			$sqlFiltrosNulo->add($filtroNulo2,OU);

			$sql->add($sqlFiltrosNulo);
			$sqlTotal->add($sqlFiltrosNulo);
		}else{
			$sql->addFiltro($camponome,$f[1],$f[2]);
			$sqlTotal->addFiltro($camponome,$f[1],$f[2]);
		}		
	}
}
// Filtro
if (tdClass::read("filtro") != ""){
	$filtro = explode("^",tdClass::read("filtro"));
	if (sizeof($filtro) > 0){
		$campo_a = explode(" ",$filtro[0]);
		$camponome = $campo_a[0];
		
		if ($filtro[2] == "int"){
			$sql->addFiltro($camponome,"=",$filtro[1]);
			$sqlTotal->addFiltro($camponome,"=",$filtro[1]);		
		}else{
			$sql->addFiltro($camponome,"like",'%' . tdc::utf8($filtro[1],2) . '%');
			$sqlTotal->addFiltro($camponome,"like",'%' . tdc::utf8($filtro[1],2) . '%');
		}
	}
}

// FiltroNN
$filtroNN = tdClass::read("filtroNN");
if ($filtroNN != ""){
    $parametros     = explode("^",$filtroNN);
    $entidadepai    = $parametros[0];
    $entidadefilho  = $parametros[2];
    $regpai         = $parametros[1];
    $lista  = getListaRegFilho($entidadepai,$entidadefilho,$regpai);
    $ids    = array();
    foreach ($lista as $l){
        array_push($ids,$l->regfilho);
    }
    $sql->addFiltro("id","in",$ids);
}

#Debug::log($entidade->contexto->nome . " " . $sql->dump());

$dataset = tdClass::Criar("repositorio",array($entidade->contexto->nome))->carregar($sql);
$dados = "";
$camposhtml = explode(",",$campos_html);
$camposfk = explode(",",$campos_fk);
$dados_array = $dados_array_reais = array();
$idRegIndice = 1;
foreach($dataset as $dado){
	$array_campos_nome = explode(",",$campos_nome);
	$campos_dados = $campos_dados_reais = array();
	$campos_dados = array();
	$i = $attrRel = 0;
	$idRegistro = 0;
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
		$campos_dados[$c] 		= getHTMLTipoFormato($camposhtml[$i],$valor_campo,$entidade->contexto->id,$c,$dado->id);
		$campos_dados_reais[$c] = $dado->{$c};
		$i++;
	}
	$dados_array[$idRegIndice] 			= $campos_dados;
	$dados_array_reais[$idRegIndice] 	= $campos_dados_reais;
	$idRegIndice++;
}

$total_registros 		= tdClass::Criar("repositorio",array($entidade->contexto->nome))->quantia($sqlTotal);
$retorno["entidade"] 	= $entidade->contexto->id;
$retorno["dados"] 		= $dados_array;
$retorno["dadosreais"] 	= $dados_array_reais;
$retorno["total"] 		= $total_registros;


echo json_encode($retorno);