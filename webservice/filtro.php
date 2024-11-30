<?php
/*
[
	{
		"expressao":
		{
			"atributo":"id",
			"operador":"=",
			"valor":1
		}
	},
	{
		,
		"operador":"AND"
		"expressao":
		{
			"atributo":"id",
			"operador":"in",
			"valor":[1,10],
			"tipo":"date"
		}
	}
]

// Exemplo Buscando em mais de um campo
[{expressao:{atributo:["nome","descricao"],operador:"%",valor:this.termopesquisa}}]
*/
#$i = 1;
$filtroJSON = json_decode($_GET["filtros"]);
#$instrucao = "( ";
$criterio = null;
foreach($filtroJSON as $f){
	
	$expressao = $f->expressao;	
	$operadorlogico = isset($f->operador)?($f->operador=="OR"?OU:E):E;
	/*
	$operador = isset($f->operador)?$f->operador:"AND";

	try {
		$tipo = @$expressao->tipo;
	} catch (Exception $e) {
		$tipo = '';
	}

	switch($tipo){
		case 'date':
			$expressao->atributo = "date_format({$expressao->atributo},'%Y-%m-%d')";
			$expressao->valor = "date_format('{$expressao->valor}','%Y-%m-%d')";
		break;
		case 'datetime':
			$expressao->atributo = "date_format({$expressao->atributo},'%Y-%m-%d %H:%i:%s')";
			$expressao->valor = "date_format('{$expressao->valor}','%Y-%m-%d %H:%i:%s')";
		break;		
	}

	switch($expressao->operador){
		case '%':
			$expression = " {$expressao->atributo} LIKE ('%{$expressao->valor}%') ";
		break;
		case "..":
			$valor1 = $expressao->valor[0];
			$valor2 = $expressao->valor[1];
			$expression = " {$expressao->atributo} BETWEEN $valor1 AND $valor2";
		break;
		case "in":
			$valorin = implode(",",$expressao->valor);
			$expression = " {$expressao->atributo} IN ($valorin) ";
		break;
		default:
			$valorexpressao = gettype($expressao->valor) == "string"?($tipo=="date"?"{$expressao->valor}":"'{$expressao->valor}'"):$expressao->valor;
			$expression = " {$expressao->atributo} {$expressao->operador} {$valorexpressao} ";
	}
	$instrucao .= ($i==1?'':$operador) . $expression;
	$i++;
	*/
	$criterio = tdc::f($expressao->atributo,$expressao->operador,$expressao->valor,$operadorlogico);
}
#$instrucao .= " )";