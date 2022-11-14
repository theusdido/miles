<?php
				
// Flag para generalização
if ($panel == ""){
	$panel = tdClass::Criar("div");
	$panel->class = "panel panel-link panel-flag-generalizacao";
	$panel_head = tdClass::Criar("div");
	$panel_head->class = "panel-heading";
	$panel_head->add("Tipo de " . $entidade->contexto->descricao);
	$panel_body = tdClass::Criar("div");
	$panel_body->class = "panel-body";			
	
	// Cria um SELECT para opção de escolha
	$select = tdClass::Criar("select");
	$select->class = "form-control input-sm select-flag-generalizacao";
	$select->id = "select-generalizacao-unica";
}
	
$ent_filho = tdClass::Criar("persistent",array(ENTIDADE,$rel->filho));	# Entidade filho do relacionamento
$descricaoRel = $rel->descricao==""?$ent_filho->contexto->descricao:$rel->descricao; # Label do Relacionamento


// Itens da Flag de generalização
$op = tdClass::Criar("option");
$op->add($descricaoRel);
$op->value = $ent_filho->contexto->id;
$op->data_entidade_nome = $ent_filho->contexto->nome;


if ($ent_filho->contexto->atributogeneralizacao != ""){
	$op->data_atributo_rel = tdClass::Criar("persistent",array(ATRIBUTO,$ent_filho->contexto->atributogeneralizacao))->contexto->nome;
	$select->add($op);		
}else{
	echo 'Entidade <b>[ '.$ent_filho->contexto->descricao.' ]</b> não possui atributo de generalização<br/>';
}

// Div para adicionar o form do relacionamento
$div_rel = tdClass::Criar("div");
$div_rel->class = "div-relacionamento-generalizacao";
$div_rel->id = "drv-".$ent_filho->contexto->nome;

$urlrequest = URL_MILES . '?controller=gerarcadastro&entidade='.$ent_filho->contexto->id . "&relacionamento=" . $rel->id;
$conteudo = getUrl($urlrequest);
if (!$conteudo){
	$div_rel->add('<div class="alert alert-danger" role="alert"><b>Ops!</b> Não foi possível criar esta página.</div>');
}else{
	$div_rel->add($conteudo);
}

array_push($abaArray,array($descricaoRel,$div_rel,'generalizacaoABA ' . $ent_filho->contexto->nome , ""));
$contEntGen++;